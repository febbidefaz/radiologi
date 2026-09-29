<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRadController extends Controller
{
    public function index()
    {
        $users = DB::table('UserRad')
            ->orderBy('Nama')
            ->get();

        return view('user-rad.user', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required',
            'Username' => 'required|unique:UserRad,Username',
            'Password' => 'required',
            'Role' => 'required',
        ]);


        DB::table('UserRad')->insert([

            'Nama' => 
                $request->Nama,

            'Username' => 
                $request->Username,

            'Password' => 
                Hash::make(
                    $request->Password
                ),

            'Role' => 
                $request->Role,

            'Aktif' => 
                $request->Aktif ?? 1,

            'CreatedAt' => 
                now(),

        ]);


        return redirect()
            ->route('userrad.index')
            ->with(
                'success',
                'User Radiologi berhasil ditambahkan.'
            );
    }



    public function update(Request $request, $id)
    {
        $request->validate([

            'Nama' => 
                'required',

            'Username' => 
                'required',

            'Role' => 
                'required',

            'Aktif' => 
                'required',

        ]);



        $data = [

            'Nama' => 
                $request->Nama,

            'Username' => 
                $request->Username,

            'Role' => 
                $request->Role,

            'Aktif' => 
                $request->Aktif,

        ];



        if ($request->filled('Password')) {

            $data['Password'] =
                Hash::make(
                    $request->Password
                );

        }



        DB::table('UserRad')
            ->where('ID', $id)
            ->update($data);



        return redirect()
            ->route('userrad.index')
            ->with(
                'success',
                'User Radiologi berhasil diperbarui.'
            );
    }




    public function destroy($id)
    {
        DB::table('UserRad')
            ->where('ID', $id)
            ->delete();


        return redirect()
            ->route('userrad.index')
            ->with(
                'success',
                'User Radiologi berhasil dihapus.'
            );
    }
}

