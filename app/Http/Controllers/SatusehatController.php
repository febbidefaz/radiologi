<?php

namespace App\Http\Controllers;

use App\Services\SatusehatService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SatusehatController extends Controller
{
    public function kirimEncounter($therapyID, SatusehatService $satusehat)
    {
        try {
    
            $row = DB::table('Therapy as t')
                ->join('PasienList as p', 't.Register', '=', 'p.RegNum')
                ->join('Dokter as d', 't.DokterID', '=', 'd.ID')
                ->join('Specialist as s', 't.SubLayanan', '=', 's.Spesialis')
                ->select(
                    't.ID',
                    't.Register',
                    't.SubLayanan',
                    't.TGL',
    
                    'p.Nama',
                    'p.NIK as NIKPasien',
                    'p.ihs_number as ihsPasien',
    
                    'd.Dokter',
                    'd.ihs_number as ihsDokter',
    
                    's.location_satu_sehat'
                )
                ->where('t.ID', $therapyID)
                ->first();
    
            if (!$row) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Therapy tidak ditemukan'
                ], 404);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | CEK ENCOUNTER SUDAH PERNAH DIKIRIM
            |--------------------------------------------------------------------------
            */
    
            $existing = DB::table('SatuSehatEcounter')
                ->where('TherapyID', $row->ID)
                ->first();
    
            if ($existing) {
                return response()->json([
                    'success' => true,
                    'message' => 'Encounter sudah pernah dikirim',
                    'therapy_id' => $row->ID,
                    'encounter_id' => $existing->EncounterID
                ]);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | RESOLVE IHS PASIEN
            |--------------------------------------------------------------------------
            */
    
            $ihsPasien = $row->ihsPasien;
    
            // Kalau IHS pasien belum ada di DB
            if (empty($ihsPasien)) {
    
                // NIK wajib ada untuk pencarian ke SATUSEHAT
                if (empty($row->NIKPasien)) {
                    return response()->json([
                        'success' => false,
                        'therapy_id' => $row->ID,
                        'nama_pasien' => $row->Nama,
                        'message' => 'IHS pasien kosong dan NIK pasien juga kosong'
                    ], 422);
                }
    
                // Cari pasien berdasarkan NIK ke SATUSEHAT
                $patientResult = $satusehat->getPatientByNik(
                    $row->NIKPasien
                );
    
                if (
                    !($patientResult['success'] ?? false) ||
                    empty($patientResult['ihs'])
                ) {
                    return response()->json([
                        'success' => false,
                        'therapy_id' => $row->ID,
                        'nama_pasien' => $row->Nama,
                        'nik' => $row->NIKPasien,
                        'message' => 'IHS pasien tidak ditemukan di SATUSEHAT',
                        'response' => $patientResult['response'] ?? null
                    ], 422);
                }
    
                // Ambil IHS hasil SATUSEHAT
                $ihsPasien = $patientResult['ihs'];
    
    
                /*
                |--------------------------------------------------------------------------
                | SIMPAN IHS KE DATABASE
                |--------------------------------------------------------------------------
                */
    
                DB::table('PasienList')
                    ->where('RegNum', $row->Register)
                    ->update([
                        'ihs_number' => $ihsPasien
                    ]);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | VALIDASI DATA LAIN
            |--------------------------------------------------------------------------
            */
    
            if (empty($row->ihsDokter)) {
                return response()->json([
                    'success' => false,
                    'therapy_id' => $row->ID,
                    'message' => 'IHS dokter belum tersedia'
                ], 422);
            }
    
            if (empty($row->location_satu_sehat)) {
                return response()->json([
                    'success' => false,
                    'therapy_id' => $row->ID,
                    'message' => 'Location SATUSEHAT belum tersedia'
                ], 422);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | FORMAT WAKTU
            |--------------------------------------------------------------------------
            */
    
            $waktuMulai = Carbon::parse(
                $row->TGL,
                'Asia/Jakarta'
            )->format('Y-m-d\TH:i:sP');
    
    
            /*
            |--------------------------------------------------------------------------
            | PAYLOAD ENCOUNTER
            |--------------------------------------------------------------------------
            */
    
            $data = [
                'no_kunjungan' => (string) $row->Register,
    
                'ihs_patient' => $ihsPasien,
    
                'nama_pasien' => $row->Nama,
    
                'ihs_practitioner' => $row->ihsDokter,
    
                'nama_dokter' => $row->Dokter,
    
                'location_id' => $row->location_satu_sehat,
    
                'nama_poli' => $row->SubLayanan,
    
                'waktu_mulai' => $waktuMulai,
            ];
    
    
            /*
            |--------------------------------------------------------------------------
            | KIRIM ENCOUNTER
            |--------------------------------------------------------------------------
            */
    
            $response = $satusehat->createEncounter($data);
    
    
            /*
            |--------------------------------------------------------------------------
            | SIMPAN ENCOUNTER ID
            |--------------------------------------------------------------------------
            */
    
            if (
                ($response['success'] ?? false) &&
                !empty($response['encounter_id'])
            ) {
    
                DB::table('SatuSehatEcounter')->insert([
                    'TherapyID' => $row->ID,
                    'EncounterID' => $response['encounter_id']
                ]);
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */
    
            return response()->json([
                'success' => $response['success'] ?? false,
    
                'therapy_id' => $row->ID,
    
                'register' => $row->Register,
    
                'nama' => $row->Nama,
    
                'ihs_pasien' => $ihsPasien,
    
                'encounter_id' =>
                    $response['encounter_id'] ?? null,
    
                'response' =>
                    $response['response'] ?? null,
            ]);
    
    
        } catch (\Throwable $e) {
    
            return response()->json([
                'success' => false,
                'therapy_id' => $therapyID,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // otomatis mendapatkan IHS
    private function resolveIhsPasien($item, SatusehatService $satusehat)
    {
        // Kalau IHS sudah ada di database
        if (!empty($item->IHSPasien)) {
            return [
                'success' => true,
                'ihs' => $item->IHSPasien,
                'source' => 'database'
            ];
        }
    
        // Kalau IHS kosong dan NIK juga kosong
        if (empty($item->NIKPasien)) {
            return [
                'success' => false,
                'ihs' => null,
                'message' => 'IHS pasien dan NIK pasien kosong'
            ];
        }
    
        // Cari ke SATUSEHAT
        $result = $satusehat->getPatientByNik(
            $item->NIKPasien
        );
    
        if (
            !($result['success'] ?? false) ||
            empty($result['ihs'])
        ) {
            return [
                'success' => false,
                'ihs' => null,
                'message' => 'IHS pasien tidak ditemukan di SATUSEHAT',
                'response' => $result['response'] ?? null
            ];
        }
    
        $ihs = $result['ihs'];
    
        // Simpan kembali IHS ke PasienList
        DB::table('PasienList')
            ->where('RegNum', $item->NoRegister)
            ->update([
                'ihs_number' => $ihs
            ]);
    
        return [
            'success' => true,
            'ihs' => $ihs,
            'source' => 'satusehat'
        ];
    }

    public function getIhsPasien($therapyID, SatusehatService $satusehat)
    {
        try {
    
            $data = DB::select(
                'EXEC dbo.WebSatuSehatRadByID_SP @TherapyID = ?',
                [$therapyID]
            );
    
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Therapy/Radiologi tidak ditemukan'
                ], 404);
            }
    
            $item = $data[0];
    
            $result = $this->resolveIhsPasien(
                $item,
                $satusehat
            );
    
            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'therapy_id' => $therapyID,
                    'nama_pasien' => $item->NamaPasien,
                    'nik' => $item->NIKPasien ?? null,
                    'message' => $result['message'],
                    'response' => $result['response'] ?? null
                ], 422);
            }
    
            return response()->json([
                'success' => true,
                'therapy_id' => $therapyID,
                'nama_pasien' => $item->NamaPasien,
                'ihs_pasien' => $result['ihs'],
                'source' => $result['source']
            ]);
    
        } catch (\Throwable $e) {
    
            return response()->json([
                'success' => false,
                'therapy_id' => $therapyID,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function kirimServiceRequest($therapyID, SatusehatService $satusehat)
    {
        try {
    
            /*
            |--------------------------------------------------------------------------
            | AMBIL DATA RADIOLOGI DARI SP
            |--------------------------------------------------------------------------
            */
    
            $data = DB::select(
                'EXEC dbo.WebSatuSehatRadByID_SP @TherapyID = ?',
                [$therapyID]
            );
    
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'therapy_id' => $therapyID,
                    'message' => 'Data radiologi tidak ditemukan'
                ], 404);
            }
    
            $hasil = [];
    
            /*
            |--------------------------------------------------------------------------
            | LOOP SEMUA PEMERIKSAAN RADIOLOGI
            |--------------------------------------------------------------------------
            */
    
            foreach ($data as $item) {
    
                /*
                |--------------------------------------------------------------------------
                | CEK SUDAH PERNAH DIKIRIM
                |--------------------------------------------------------------------------
                */
    
                $existing = DB::table('SatuSehatServiceRequest')
                    ->where('TherapyID', $item->TherapyID)
                    ->where('IDRad', $item->IDRad)
                    ->where('IDPemeriksaan', $item->IDPemeriksaan)
                    ->first();
    
                if ($existing) {
    
                    $hasil[] = [
                        'success' => true,
                        'status' => 'existing',
                        'therapy_id' => $item->TherapyID,
                        'id_rad' => $item->IDRad,
                        'id_pemeriksaan' => $item->IDPemeriksaan,
                        'nama_pemeriksaan' => $item->NamaPemeriksaan,
                        'service_request_id' =>
                            $existing->ServiceRequestID,
                        'message' =>
                            'ServiceRequest sudah pernah dikirim'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | VALIDASI DATA WAJIB
                |--------------------------------------------------------------------------
                */
    
                if (empty($item->EncounterID)) {
    
                    $hasil[] = [
                        'success' => false,
                        'therapy_id' => $item->TherapyID,
                        'id_rad' => $item->IDRad,
                        'nama_pemeriksaan' => $item->NamaPemeriksaan,
                        'message' => 'EncounterID belum tersedia'
                    ];
    
                    continue;
                }
    
                if (empty($item->IHSPasien)) {
    
                    $hasil[] = [
                        'success' => false,
                        'therapy_id' => $item->TherapyID,
                        'id_rad' => $item->IDRad,
                        'nama_pemeriksaan' => $item->NamaPemeriksaan,
                        'message' => 'IHS pasien belum tersedia'
                    ];
    
                    continue;
                }
    
                if (empty($item->IHSDokter)) {
    
                    $hasil[] = [
                        'success' => false,
                        'therapy_id' => $item->TherapyID,
                        'id_rad' => $item->IDRad,
                        'nama_pemeriksaan' => $item->NamaPemeriksaan,
                        'message' => 'IHS dokter belum tersedia'
                    ];
    
                    continue;
                }
    
                if (empty($item->LOINC)) {
    
                    $hasil[] = [
                        'success' => false,
                        'therapy_id' => $item->TherapyID,
                        'id_rad' => $item->IDRad,
                        'nama_pemeriksaan' => $item->NamaPemeriksaan,
                        'message' => 'Kode LOINC belum tersedia'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | FORMAT TANGGAL
                |--------------------------------------------------------------------------
                */
    
                $authoredOn = Carbon::parse(
                    $item->TanggalPelayanan,
                    'Asia/Jakarta'
                )->format('Y-m-d\TH:i:sP');
    
    
                /*
                |--------------------------------------------------------------------------
                | SIAPKAN DATA UNTUK SERVICE
                |--------------------------------------------------------------------------
                */
    
                $serviceData = [
    
                    'id_rad' =>
                        $item->IDRad . '-' . $item->IDPemeriksaan,
                    
                    'accession_no' =>
                        (string) $item->AccessionNo,    
    
                    'ihs_patient' =>
                        $item->IHSPasien,
    
                    'nama_pasien' =>
                        $item->NamaPasien,
    
                    'ihs_practitioner' =>
                        $item->IHSDokter,
    
                    'nama_dokter' =>
                        $item->NamaDokter,
    
                    'encounter_id' =>
                        $item->EncounterID,
    
                    'loinc' =>
                        $item->LOINC,
    
                    'nama_pemeriksaan' =>
                        $item->NamaPemeriksaan,
    
                    'authored_on' =>
                        $authoredOn,
                ];
    
    
                /*
                |--------------------------------------------------------------------------
                | KIRIM KE SATUSEHAT
                |--------------------------------------------------------------------------
                */
    
                $response =
                    $satusehat->createServiceRequest(
                        $serviceData
                    );
    
    
                /*
                |--------------------------------------------------------------------------
                | JIKA BERHASIL → SIMPAN KE DATABASE
                |--------------------------------------------------------------------------
                */
    
                if (
                    ($response['success'] ?? false) &&
                    !empty($response['service_request_id'])
                ) {
    
                    DB::table('SatuSehatServiceRequest')
                        ->insert([
                            'TherapyID' =>
                                $item->TherapyID,
                    
                            'IDRad' =>
                                $item->IDRad,
                    
                            'IDPemeriksaan' =>
                                $item->IDPemeriksaan,
                    
                            'AccessionNo' =>
                                (string) $item->AccessionNo,
                    
                            'ServiceRequestID' =>
                                $response['service_request_id']
                        ]);
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | SIMPAN HASIL UNTUK RESPONSE
                |--------------------------------------------------------------------------
                */
    
                $hasil[] = [
                    'success' =>
                        $response['success'] ?? false,
    
                    'status' =>
                        ($response['success'] ?? false)
                            ? 'sent'
                            : 'failed',
    
                    'therapy_id' =>
                        $item->TherapyID,
    
                    'id_rad' =>
                        $item->IDRad,
    
                    'id_pemeriksaan' =>
                        $item->IDPemeriksaan,
    
                    'nama_pemeriksaan' =>
                        $item->NamaPemeriksaan,
    
                    'loinc' =>
                        $item->LOINC,
    
                    'service_request_id' =>
                        $response['service_request_id'] ?? null,
    
                    'response' =>
                        $response['response'] ?? null,
                ];
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | RESPONSE FINAL
            |--------------------------------------------------------------------------
            */
    
            return response()->json([
                'success' => true,
                'therapy_id' => $therapyID,
                'jumlah_pemeriksaan' => count($data),
                'hasil' => $hasil
            ]);
    
    
        } catch (\Throwable $e) {
    
            return response()->json([
                'success' => false,
                'therapy_id' => $therapyID,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function kirimObservation($therapyID, SatusehatService $satusehat)
    {
        try {
    
            // Ambil data radiologi dari SP
            $data = DB::select(
                'EXEC dbo.WebSatuSehatRadByID_SP @TherapyID = ?',
                [$therapyID]
            );
    
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'therapy_id' => $therapyID,
                    'message' => 'Data radiologi tidak ditemukan'
                ], 404);
            }
    
            $hasil = [];
    
            foreach ($data as $item) {
    
                /*
                |--------------------------------------------------------------------------
                | CARI SERVICE REQUEST
                |--------------------------------------------------------------------------
                */
    
                $serviceRequest = DB::table('SatuSehatServiceRequest')
                    ->where('TherapyID', $item->TherapyID)
                    ->where('IDRad', $item->IDRad)
                    ->where('IDPemeriksaan', $item->IDPemeriksaan)
                    ->first();
    
                if (!$serviceRequest) {
    
                    $hasil[] = [
                        'success' => false,
                        'status' => 'failed',
                        'id_rad' => $item->IDRad,
                        'id_pemeriksaan' => $item->IDPemeriksaan,
                        'nama_pemeriksaan' => $item->NamaPemeriksaan,
                        'message' => 'ServiceRequest belum dikirim'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | CEK OBSERVATION SUDAH PERNAH DIKIRIM
                |--------------------------------------------------------------------------
                */
    
                if (!empty($serviceRequest->ObservationID)) {
    
                    $hasil[] = [
                        'success' => true,
                        'status' => 'existing',
                        'id_rad' => $item->IDRad,
                        'id_pemeriksaan' => $item->IDPemeriksaan,
                        'nama_pemeriksaan' => $item->NamaPemeriksaan,
                        'service_request_id' =>
                            $serviceRequest->ServiceRequestID,
                        'observation_id' =>
                            $serviceRequest->ObservationID,
                        'message' =>
                            'Observation sudah pernah dikirim'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | VALIDASI DATA
                |--------------------------------------------------------------------------
                */
    
                if (empty($item->EncounterID)) {
    
                    $hasil[] = [
                        'success' => false,
                        'status' => 'failed',
                        'id_rad' => $item->IDRad,
                        'id_pemeriksaan' => $item->IDPemeriksaan,
                        'message' => 'EncounterID belum tersedia'
                    ];
    
                    continue;
                }
    
                if (empty($item->IHSPasien)) {
    
                    $hasil[] = [
                        'success' => false,
                        'status' => 'failed',
                        'id_rad' => $item->IDRad,
                        'id_pemeriksaan' => $item->IDPemeriksaan,
                        'message' => 'IHS pasien belum tersedia'
                    ];
    
                    continue;
                }
    
                if (empty($item->IHSDokterRadiologi)) {

                    $hasil[] = [
                        'success' => false,
                        'status' => 'failed',
                        'id_rad' => $item->IDRad,
                        'id_pemeriksaan' => $item->IDPemeriksaan,
                        'nama_pemeriksaan' => $item->NamaPemeriksaan,
                        'message' => 'IHS dokter radiologi belum tersedia'
                    ];
                
                    continue;
                }
    
                if (empty($item->LOINC)) {
    
                    $hasil[] = [
                        'success' => false,
                        'status' => 'failed',
                        'id_rad' => $item->IDRad,
                        'id_pemeriksaan' => $item->IDPemeriksaan,
                        'nama_pemeriksaan' => $item->NamaPemeriksaan,
                        'message' => 'Kode LOINC belum tersedia'
                    ];
    
                    continue;
                }
    
                if (
                    !isset($item->HasilRadiologi) ||
                    trim((string) $item->HasilRadiologi) === ''
                ) {
    
                    $hasil[] = [
                        'success' => false,
                        'status' => 'failed',
                        'id_rad' => $item->IDRad,
                        'id_pemeriksaan' => $item->IDPemeriksaan,
                        'nama_pemeriksaan' => $item->NamaPemeriksaan,
                        'message' => 'Hasil radiologi belum tersedia'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | WAKTU OBSERVATION
                |--------------------------------------------------------------------------
                */
    
                $effectiveDateTime = Carbon::parse(
                    $item->TanggalPelayanan,
                    'Asia/Jakarta'
                )->format('Y-m-d\TH:i:sP');
    
    
                /*
                |--------------------------------------------------------------------------
                | SIAPKAN DATA OBSERVATION
                |--------------------------------------------------------------------------
                */
    
                $observationData = [

                    'identifier' =>
                        $item->IDRad . '-' . $item->IDPemeriksaan,
                
                    'ihs_patient' =>
                        $item->IHSPasien,
                
                    'nama_pasien' =>
                        $item->NamaPasien,
                
                    // DOKTER RADIOLOGI PEMBACA HASIL
                    'ihs_practitioner' =>
                        $item->IHSDokterRadiologi,
                
                    'nama_dokter' =>
                        $item->DokterRadiologi,
                
                    'encounter_id' =>
                        $item->EncounterID,
                
                    'service_request_id' =>
                        $serviceRequest->ServiceRequestID,
                
                    'loinc' =>
                        $item->LOINC,
                
                    'nama_pemeriksaan' =>
                        $item->NamaPemeriksaan,
                
                    'hasil' =>
                        trim((string) $item->HasilRadiologi),
                
                    'effective_datetime' =>
                        $effectiveDateTime,
                ];
    
    
                /*
                |--------------------------------------------------------------------------
                | KIRIM KE SATUSEHAT
                |--------------------------------------------------------------------------
                */
    
                $response = $satusehat->createObservation(
                    $observationData
                );
    
    
                /*
                |--------------------------------------------------------------------------
                | JIKA BERHASIL → UPDATE ObservationID
                |--------------------------------------------------------------------------
                */
    
                if (
                    ($response['success'] ?? false) &&
                    !empty($response['observation_id'])
                ) {
    
                    DB::table('SatuSehatServiceRequest')
                        ->where('TherapyID', $item->TherapyID)
                        ->where('IDRad', $item->IDRad)
                        ->where('IDPemeriksaan', $item->IDPemeriksaan)
                        ->update([
                            'ObservationID' =>
                                $response['observation_id']
                        ]);
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | RESPONSE PER PEMERIKSAAN
                |--------------------------------------------------------------------------
                */
    
                $hasil[] = [
                    'success' =>
                        $response['success'] ?? false,
    
                    'status' =>
                        ($response['success'] ?? false)
                            ? 'sent'
                            : 'failed',
    
                    'therapy_id' =>
                        $item->TherapyID,
    
                    'id_rad' =>
                        $item->IDRad,
    
                    'id_pemeriksaan' =>
                        $item->IDPemeriksaan,
    
                    'nama_pemeriksaan' =>
                        $item->NamaPemeriksaan,
    
                    'loinc' =>
                        $item->LOINC,
    
                    'service_request_id' =>
                        $serviceRequest->ServiceRequestID,
    
                    'observation_id' =>
                        $response['observation_id'] ?? null,
    
                    'response' =>
                        $response['response'] ?? null,
                ];
            }
    
    
            /*
            |--------------------------------------------------------------------------
            | RESPONSE FINAL
            |--------------------------------------------------------------------------
            */
    
            return response()->json([
                'success' => true,
                'therapy_id' => $therapyID,
                'jumlah_pemeriksaan' => count($data),
                'hasil' => $hasil
            ]);
    
        } catch (\Throwable $e) {
    
            return response()->json([
                'success' => false,
                'therapy_id' => $therapyID,
                'message' => $e->getMessage()
            ], 500);
        }
    }


}