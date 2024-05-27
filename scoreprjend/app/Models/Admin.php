<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    use HasFactory;
    use Authenticatable;
    protected $fillable = ['username', 'password'];
    public $timestamps = false;
    public function index()
    {

        $admin = DB::table('admins')->get();
        return $admin;
    }

    public function edit()
    {
        $admins = DB::table('admins')
            ->where('id', $this->id)
            ->get();
        return $admins;
    }

    public function deleteAdmin()
    {
        DB::table('admins')
            ->where('id', $this->id)
            ->delete();
    }
}
