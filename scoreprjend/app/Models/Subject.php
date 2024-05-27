<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subject extends Model
{
    use HasFactory;

    public $timestamps = false;
    public function index()
    {
        $query = DB::table('subjects')
            ->join('specializes', 'subjects.specializes_id', '=', 'specializes.id')
            ->select(['subjects.*',
                'specializes.specialized_name AS specialized_name'
            ])
            ->get();

        return $query;
    }
    public function store()
    {
        DB::table('subjects')
            ->insert([
                'subject_name' => $this->subject_name,
                'duration' => $this->duration,
                'specializes_id' => $this->specializes_id
            ]);
    }

    public function edit()
    {
        $subjects = DB::table('subjects')
            ->where('id', $this->id)
            ->get();
        return $subjects;
    }

    public function updateSubject()
    {
        DB::table('subjects')
            ->where('id', $this->id)
            ->update([
                'subject_name' => $this->subject_name,
                'duration' => $this->duration,
                'specializes_id' => $this->specializes_id
            ]);
    }

    public function deleteSubject()
    {
        DB::table('subjects')
            ->where('id', $this->id)
            ->delete();
    }
//    protected $fillable = ['subject_name', 'specializes_id'];
    public function specializes(){
        return $this->belongsTo(Specialize::class);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($query) use ($searchTerm) {
            $query->where('subject_name', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('specializes', function ($query) use ($searchTerm) {
                    $query->where('specialized_name', 'like', '%' . $searchTerm . '%');
                });
        });
    }


}
