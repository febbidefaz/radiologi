<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IGDController extends Controller
{
    public function index()
    {
        return view('igd');
    }

    public function data(Request $request)
    {
        try {
            $request->validate([
                'tgl_awal'  => 'nullable|date',
                'tgl_akhir' => 'nullable|date|after_or_equal:tgl_awal',
            ]);
    
            $tglAwal = $request->tgl_awal ?? date('Y-m-d');
            $tglAkhir = $request->tgl_akhir ?? date('Y-m-d');
    
            $data = DB::select("
                SET NOCOUNT ON;
                EXEC dbo.WebDaftarPasienRawatJalanIGD_SP ?, ?
            ", [
                $tglAwal,
                $tglAkhir
            ]);
    
            return response()->json([
                'data' => $data ?: []
            ]);
    
        } catch (\Exception $e) {
            Log::error('IGD DATA ERROR : ' . $e->getMessage());
    
            return response()->json([
                'data' => [],
                'message' => 'Data tidak ditemukan atau gagal dimuat.'
            ], 200);
        }
    }

    public function show($id)
    {
        return 'Detail pasien ID: ' . $id;
    }
}
