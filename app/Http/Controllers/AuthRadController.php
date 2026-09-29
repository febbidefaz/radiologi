<?php

namespace App\Http\Controllers;

use App\Models\UserRad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRadController extends Controller
{
    public function showLogin()
    {
        if (session('userrad_id')) {
            return redirect()->route('rad.home');
        }

        return view('rad-auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'Username' => 'required|string',
            'Password' => 'required|string',
        ]);

        $user = UserRad::where('Username', $request->Username)
            ->where('Aktif', 1)
            ->first();

        if (!$user || !Hash::check($request->Password, $user->Password)) {
            return back()
                ->withInput($request->only('Username'))
                ->with('error', 'Username atau password salah.');
        }

        $request->session()->regenerate();

        session([
            'userrad_id'       => $user->ID,
            'userrad_nama'     => $user->Nama,
            'userrad_username' => $user->Username,
            'userrad_role'     => $user->Role,
        ]);

        return redirect()->route('rad.home');
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'userrad_id',
            'userrad_nama',
            'userrad_username',
            'userrad_role',
        ]);

        $request->session()->regenerateToken();

        return redirect()->route('rad.login');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'password_baru' => 'required|confirmed',
        ]);


        DB::table('Userrad')
            ->where('ID', $request->user_id)
            ->update([
                'Password' => Hash::make(
                    $request->password_baru
                ),
            ]);


        return back()->with(
            'success',
            'Password berhasil diperbarui.'
        );
    }
}
