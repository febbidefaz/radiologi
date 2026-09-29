<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RadController extends Controller
{
    
    
    
    // Cari Pasien
    public function cariPasien(Request $request)
    {
        $id = $request->id;

        $pasien = DB::selectOne(
            "EXEC dbo.WebCariPasienByID_SP ?",
            [$id]
        );

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $pasien
        ]);
    }
}
