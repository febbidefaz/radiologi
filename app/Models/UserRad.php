<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserRad extends Authenticatable
{
    protected $table = 'UserRad';

    protected $primaryKey = 'ID';

    public $timestamps = false;

    protected $fillable = [
        'Nama',
        'Username',
        'Password',
        'Role',
        'Aktif',
        'CreatedAt',
    ];

    protected $hidden = [
        'Password',
    ];

    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function getAuthIdentifierName()
    {
        return 'Username';
    }

    public function getNameAttribute()
    {
        return $this->Nama;
    }

    public function adminlte_image()
    {
        return asset(
            'vendor/adminlte/dist/img/user2-160x160.jpg'
        );
    }

    public function adminlte_desc()
    {
        return $this->Role;
    }
}