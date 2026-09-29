<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SatusehatService
{
    protected $clientId;
    protected $clientSecret;
    protected $organizationId;
    protected $authUrl;
    protected $fhirUrl;

    public function __construct()
    {
        $this->clientId =
            config('satu-sehat.client_id');
    
        $this->clientSecret =
            config('satu-sehat.client_secret');
    
        $this->organizationId =
            config('satu-sehat.organization_id');
    
        $this->authUrl =
            rtrim(config('satu-sehat.auth_url') ?? '', '/');
    
        $this->fhirUrl =
            rtrim(config('satu-sehat.fhir_url') ?? '', '/');
    }

    /**
     * Ambil Access Token SATUSEHAT
     */
    public function getToken()
    {
        $response = Http::asForm()
            ->post(
                $this->authUrl . '/accesstoken?grant_type=client_credentials',
                [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]
            );

        if (!$response->successful()) {
            throw new Exception(
                'Gagal mengambil token SATUSEHAT: ' .
                $response->body()
            );
        }

        return $response->json('access_token');
    }

    /**
     * Kirim Encounter Baru
     */
    public function createEncounter(array $data)
    {
        $token = $this->getToken();

        $payload = [
            'resourceType' => 'Encounter',

            'identifier' => [
                [
                    'system' =>
                        'http://sys-ids.kemkes.go.id/encounter/' .
                        $this->organizationId,

                    'value' => $data['no_kunjungan'],
                ]
            ],

            'status' => 'arrived',

            'class' => [
                'system' =>
                    'http://terminology.hl7.org/CodeSystem/v3-ActCode',

                'code' => 'AMB',

                'display' => 'ambulatory',
            ],

            'subject' => [
                'reference' =>
                    'Patient/' . $data['ihs_patient'],

                'display' =>
                    $data['nama_pasien'] ?? null,
            ],

            'participant' => [
                [
                    'type' => [
                        [
                            'coding' => [
                                [
                                    'system' =>
                                        'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',

                                    'code' => 'ATND',

                                    'display' => 'attender',
                                ]
                            ]
                        ]
                    ],

                    'individual' => [
                        'reference' =>
                            'Practitioner/' .
                            $data['ihs_practitioner'],

                        'display' =>
                            $data['nama_dokter'] ?? null,
                    ],
                ]
            ],

            'period' => [
                'start' => $data['waktu_mulai'],
            ],

            'location' => [
                [
                    'location' => [
                        'reference' =>
                            'Location/' .
                            $data['location_id'],

                        'display' =>
                            $data['nama_poli'] ?? null,
                    ]
                ]
            ],

            'statusHistory' => [
                [
                    'status' => 'arrived',

                    'period' => [
                        'start' =>
                            $data['waktu_mulai'],
                    ],
                ]
            ],

            'serviceProvider' => [
                'reference' =>
                    'Organization/' .
                    $this->organizationId,
            ],
        ];

        $response = Http::withToken($token)
            ->acceptJson()
            ->asJson()
            ->post(
                $this->fhirUrl . '/Encounter',
                $payload
            );

        return [
            'success' => $response->successful(),

            'http_code' => $response->status(),

            'encounter_id' =>
                $response->json('id'),

            'response' =>
                $response->json(),

            'payload' =>
                $payload,
        ];
    }

    public function getPatientByNik($nik)
    {
        $token = $this->getToken();

        $response = Http::withToken($token)
            ->acceptJson()
            ->get(
                $this->fhirUrl . '/Patient',
                [
                    'identifier' =>
                        'https://fhir.kemkes.go.id/id/nik|' . $nik
                ]
            );

        if (!$response->successful()) {
            return [
                'success' => false,
                'ihs' => null,
                'response' => $response->json(),
            ];
        }

        $json = $response->json();

        if (
            empty($json['entry']) ||
            empty($json['entry'][0]['resource']['id'])
        ) {
            return [
                'success' => false,
                'ihs' => null,
                'message' => 'Pasien tidak ditemukan di SATUSEHAT',
                'response' => $json
            ];
        }

        return [
            'success' => true,
            'ihs' => $json['entry'][0]['resource']['id'],
            'response' => $json
        ];
    }

    /**
     * Kirim ServiceRequest Radiologi ke SATUSEHAT
     */
    public function createServiceRequest(array $data)
    {
        $token = $this->getToken();

        $payload = [
            'resourceType' => 'ServiceRequest',

            'identifier' => [

                // Identifier internal ServiceRequest
                [
                    'system' =>
                        'http://sys-ids.kemkes.go.id/servicerequest/' .
                        $this->organizationId,
            
                    'value' =>
                        (string) $data['id_rad'],
                ],
            
                // Accession Number untuk DICOM / PACS
                [
                    'system' =>
                        'http://sys-ids.kemkes.go.id/acsn/' .
                        $this->organizationId,
            
                    'value' =>
                        (string) $data['accession_no'],
                ],
            
            ],

            'status' => 'active',

            'intent' => 'original-order',

            'category' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://snomed.info/sct',
                            'code' => '363679005',
                            'display' => 'Imaging'
                        ]
                    ]
                ]
            ],

            'code' => [
                'coding' => [
                    [
                        'system' => 'http://loinc.org',
                        'code' => $data['loinc'],
                        'display' => $data['nama_pemeriksaan']
                    ]
                ],
                'text' => $data['nama_pemeriksaan']
            ],

            'subject' => [
                'reference' =>
                    'Patient/' . $data['ihs_patient'],

                'display' =>
                    $data['nama_pasien'] ?? null,
            ],

            'encounter' => [
                'reference' =>
                    'Encounter/' . $data['encounter_id'],
            ],

            'authoredOn' =>
                $data['authored_on'],

            'requester' => [
                'reference' =>
                    'Practitioner/' . $data['ihs_practitioner'],

                'display' =>
                    $data['nama_dokter'] ?? null,
            ],

            'performer' => [
                [
                    'reference' =>
                        'Organization/' . $this->organizationId
                ]
            ],
        ];

        $response = Http::withToken($token)
            ->acceptJson()
            ->asJson()
            ->post(
                $this->fhirUrl . '/ServiceRequest',
                $payload
            );

        return [
            'success' => $response->successful(),

            'http_code' => $response->status(),

            'service_request_id' =>
                $response->json('id'),

            'response' =>
                $response->json(),

            'payload' =>
                $payload,
        ];
    }

    /**
     * Kirim Observation hasil radiologi
     */
    public function createObservation(array $data)
    {
        $token = $this->getToken();

        $payload = [
            'resourceType' => 'Observation',

            'identifier' => [
                [
                    'system' =>
                        'http://sys-ids.kemkes.go.id/observation/' .
                        $this->organizationId,

                    'value' => (string) $data['identifier'],
                ]
            ],

            'status' => 'final',

            'category' => [
                [
                    'coding' => [
                        [
                            'system' =>
                                'http://terminology.hl7.org/CodeSystem/observation-category',

                            'code' => 'imaging',

                            'display' => 'Imaging'
                        ]
                    ]
                ]
            ],

            'code' => [
                'coding' => [
                    [
                        'system' => 'http://loinc.org',

                        'code' =>
                            $data['loinc'],

                        'display' =>
                            $data['nama_pemeriksaan']
                    ]
                ],

                'text' =>
                    $data['nama_pemeriksaan']
            ],

            'subject' => [
                'reference' =>
                    'Patient/' . $data['ihs_patient'],

                'display' =>
                    $data['nama_pasien'] ?? null,
            ],

            'encounter' => [
                'reference' =>
                    'Encounter/' . $data['encounter_id']
            ],

            'effectiveDateTime' =>
                $data['effective_datetime'],

            'issued' =>
                $data['effective_datetime'],

            'performer' => [
                [
                    'reference' =>
                        'Practitioner/' .
                        $data['ihs_practitioner'],

                    'display' =>
                        $data['nama_dokter'] ?? null
                ]
            ],

            'basedOn' => [
                [
                    'reference' =>
                        'ServiceRequest/' .
                        $data['service_request_id']
                ]
            ],

            'valueString' =>
                $data['hasil'],
        ];

        $response = Http::withToken($token)
            ->acceptJson()
            ->asJson()
            ->post(
                $this->fhirUrl . '/Observation',
                $payload
            );

        return [
            'success' =>
                $response->successful(),

            'http_code' =>
                $response->status(),

            'observation_id' =>
                $response->json('id'),

            'response' =>
                $response->json(),

            'payload' =>
                $payload,
        ];
    }




}