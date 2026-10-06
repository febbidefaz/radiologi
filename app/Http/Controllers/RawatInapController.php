<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RawatInapController extends Controller
{
    public function index()
    {
        return view('rawatinap');
    }

    public function data()
    {
        $data = DB::select('EXEC dbo.DaftarPasienRawatInap_SP');

        return response()->json([
            'data' => $data
        ]);
    }

    public function show($id)
    {
        return 'Detail pasien ID: ' . $id;
    }

    // Cek SEP
    public function sepDetail(Request $request)
    {
        $nosep = $request->query('nosep');

        $response = Http::timeout(10)->get('http://192.168.1.200:6000/api/findsep', [
            'nosep' => $nosep
        ]);

        return response()->json($response->json());
    }
    
    // Insert biaya lain
    public function insertLain(Request $request)
    {
        try {
            $request->validate([
                'ID'        => 'required|integer',
                'TGL'       => 'nullable|date',
                'Lain'      => 'required|string|max:50',
                'BiayaLain' => 'required|numeric',
                'Pot'       => 'nullable|numeric',
            ]);

            $tgl = $request->TGL
                ? Carbon::parse($request->TGL, 'Asia/Jakarta')
                    ->setTimeFrom(Carbon::now('Asia/Jakarta'))
                    ->format('Y-m-d H:i:s')
                : Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');

            $result = DB::select("
                EXEC dbo.WebInsertLainByID_SP
                    @ID = ?,
                    @TGL = ?,
                    @Lain = ?,
                    @BiayaLain = ?,
                    @Pot = ?
            ", [
                $request->ID,
                $tgl,
                $request->Lain,
                $request->BiayaLain ?? 0,
                ($request->Pot ?? 0) / 100,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Biaya lain-lain berhasil ditambahkan.',
                'lain_id' => $result[0]->Lain_ID_Baru ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Update Biaya Lain    
    public function updateLain(Request $request)
    {
        $request->validate([
            'ID' => 'required|integer',
            'Lain_ID' => 'required|integer',
            'TGL' => 'nullable|date',
            'Lain' => 'required|string|max:50',
            'BiayaLain' => 'required|numeric',
            'Pot' => 'nullable|numeric',
        ]);

        $tgl = $request->TGL
            ? Carbon::parse($request->TGL, 'Asia/Jakarta')
                ->setTimeFrom(Carbon::now('Asia/Jakarta'))
                ->format('Y-m-d H:i:s')
            : Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');

        DB::statement("
            EXEC dbo.WebUpdateLainByID_SP ?, ?, ?, ?, ?, ?
        ", [
            $request->ID,
            $request->Lain_ID,
            $tgl,
            $request->Lain,
            $request->BiayaLain,
            ($request->Pot ?? 0) / 100,
        ]);

        return response()->json(['success' => true]);
    }

    // Del Biaya Lain
    public function deleteLain(Request $request)
    {
        $request->validate([
            'ID' => 'required|integer',
            'Lain_ID' => 'required|integer',
        ]);

        DB::statement("EXEC dbo.WebDeleteLainByID_SP ?, ?", [
            $request->ID,
            $request->Lain_ID,
        ]);

        return response()->json(['success' => true]);
    }

    public function detail($id)
    {
        $pasien = DB::selectOne(
            "EXEC dbo.WebPasienRawatInapDetailByID_SP ?",
            [(int) $id]
        );
    
        if (!$pasien) {
            abort(404, 'Pasien tidak ditemukan');
        }
    
    
        // ============================================================
        // RADIOLOGI
        // ============================================================
    
        $rad = DB::select(
            "EXEC dbo.WebRadiologiByIDReg_SP ?",
            [(int) $id]
        );
    
        $radDetail = [];
    
        foreach ($rad as $r) {
    
            $radDetail[$r->IDRad] = DB::select(
                "EXEC dbo.WebRadiologiDetailByIDRad_SP ?",
                [(int) $r->IDRad]
            );
        }
    
    
        // ============================================================
        // LAIN-LAIN
        // ============================================================
    
        $lainlain = DB::select(
            "EXEC dbo.WebLainBillingByID_SP ?",
            [(int) $id]
        );
    
    
        // ============================================================
        // DOKTER
        // ============================================================
    
        $dokterList = DB::select("
            SET NOCOUNT ON;
            EXEC dbo.cboDokter_SP
        ");

        $alatList = DB::select("
            SET NOCOUNT ON;
            EXEC dbo.cboAlat_SP
        ");

        $spRadList = DB::select("
            SET NOCOUNT ON;
            EXEC dbo.cboSPRadNew_SP ?
        ", [
            $pasien->RegNum
        ]);
    
        // ============================================================
        // UPX
        // ============================================================
    
        $upxList = DB::select("
            SET NOCOUNT ON;
            EXEC dbo.cboUpx_sp
        ");
    
    
        return view(
            'rawatinap.inapdetail',
            compact(
                'pasien',
                'rad',
                'radDetail',
                'lainlain',
                'dokterList',
                'alatList',
                'spRadList',
                'upxList'
            )
        );
    }

    public function updatePxRS(Request $request, $id)
    {
        $request->validate([

            'uPx' =>
                'required|integer',

        ]);


        try {

            DB::statement("
                SET NOCOUNT ON;
                EXEC dbo.WebUpdatePxRSByID_SP ?, ?
            ", [

                (int) $id,

                (int) $request->input('uPx'),

            ]);


            return response()->json([

                'success' =>
                    true,

                'message' =>
                    'PxRS berhasil diperbarui.',

            ]);


        } catch (\Throwable $e) {

            return response()->json([

                'success' =>
                    false,

                'message' =>
                    $e->getMessage(),

            ], 500);
        }
    }

    public function printLabelTengah($id)
    {
        $rows = DB::select(
            'EXEC dbo.skotlet @ID = ?',
            [(int) $id]
        );
    
        if (empty($rows)) {
            abort(404, 'Data pasien tidak ditemukan.');
        }
    
        $row = $rows[0];
    
        $patient = [
            'ID' => $row->ID ?? '-',
            'RegNum' => $row->RegNum ?? '-',
            'Nama' => $row->Nama ?? '-',
            'Addr' => $row->Addr ?? '-',
            'Tanggal_Lahir' => $row->Tanggal_Lahir ?? null,
        ];
    
        return view(
            'rawatinap.label.label-tengah',
            compact('patient')
        );
    }

    public function printLabelSamping($id)
    {
        $rows = DB::select(
            'EXEC dbo.skotlet @ID = ?',
            [(int) $id]
        );
    
        if (empty($rows)) {
            abort(404, 'Data pasien tidak ditemukan.');
        }
    
        $row = $rows[0];
    
        $patient = [
            'ID' => $row->ID ?? '-',
            'RegNum' => $row->RegNum ?? '-',
            'Nama' => $row->Nama ?? '-',
            'Addr' => $row->Addr ?? '-',
            'Tanggal_Lahir' => $row->Tanggal_Lahir ?? null,
        ];
    
        return view(
            'rawatinap.label.label-samping',
            compact('patient')
        );
    }
}
