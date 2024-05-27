<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lecturer extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    use HasFactory;
    use Authenticatable;
    public $timestamps = false;
//    protected $fillable = ['lecturer_name', 'email', 'phone', 'password', 'specializes_id'];
//    public function specializes(){
//        return $this->belongsTo(Specialize::class);
//    }
    public function index()
    {
        $query = DB::table('lecturers')
            ->join('specializes', 'lecturers.specializes_id', '=', 'specializes.id')
            ->select(['lecturers.*',
                'specializes.specialized_name AS specialized_name'
            ])
            ->get();

        return $query;
    }

    public function store()
    {
        DB::table('lecturers')
            ->insert([
                'lecturer_name' => $this->lecturer_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => $this->password,
                'specializes_id' => $this->specializes_id

            ]);
    }

    public function edit()
    {
        $lecturers = DB::table('lecturers')
            ->where('id', $this->id)
            ->get();
        return $lecturers;
    }

    public function updateLecturer()
    {
        DB::table('lecturers')
            ->where('id', $this->id)
            ->update([
                'lecturer_name' => $this->lecturer_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => $this->password,
                'specializes_id' => $this->specializes_id
            ]);
    }

    public function deleteLecturer()
    {
        DB::table('lecturers')
            ->where('id', $this->id)
            ->delete();
    }
    public function specializes(){
        return $this->belongsTo(Specialize::class);
    }
}
