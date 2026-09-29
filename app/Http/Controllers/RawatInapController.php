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
            [$id]
        );
    
        if (!$pasien) {
            abort(404, 'Pasien tidak ditemukan');
        }
    
        // LAB
        $lab = DB::select(
            "EXEC dbo.WeblaboratByIDReg_SP ?",
            [$id]
        );
    
        $labDetail = [];
    
        foreach ($lab as $l) {
            $labDetail[$l->IDLab] = DB::select(
                "EXEC dbo.WebLaboratDetailByIDLab_SP ?",
                [$l->IDLab]
            );
        }
    
        // LAIN-LAIN
        $lainlain = DB::select(
            "EXEC dbo.WebLainBillingByID_SP ?",
            [$id]
        );

        $dokterList = DB::select("
            SET NOCOUNT ON;
            EXEC dbo.cboDokter_SP
        ");

        $dokterSpPKList = DB::select("
            SET NOCOUNT ON;
            EXEC dbo.cboDokterSpPK_SP
        ");

        $spLabList = DB::select("
            SET NOCOUNT ON;
            EXEC dbo.cboSPLabNew_SP ?
        ", [
            $pasien->RegNum
        ]);

        $spPaList = DB::select("
            SET NOCOUNT ON;
            EXEC dbo.cboSPLabPA_SP
        ");

        //Get Upx
        $upxList = DB::select("EXEC dbo.cboUpx_sp");
            
        return view('rawatinap.inapdetail', compact(
            'pasien',
            'lab',
            'labDetail',
            'lainlain',
            'dokterList',
            'dokterSpPKList',
            'spLabList',
            'spPaList',
            'upxList'
        ));
        
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
}
