<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//
//class Specialize extends Model
//{
//    use HasFactory;
//    public $timestamps = false;
//    protected $fillable = ['specialized_name'];
//    public function subject(){
//        return $this->hasMany(Subject::class);
//    }
//    public function classes(){
//        return $this -> hasMany(Classes::class);
//    }
//    public function lecturer(){
//        return $this -> hasMany(Lecturer::class);
//    }
//}


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Specialize extends Model
{
    public $timestamps = false;
    use HasFactory;


    public function index()
    {

        $specialize = DB::table('specializes')->get();
        return $specialize;
    }

    public function store()
    {
        DB::table('specializes')
            ->insert([
                'specialized_name' => $this->specialized_name,
            ]);
    }

    public function edit()
    {
        $specialize = DB::table('specializes')
            ->where('id', $this->id)
            ->get();
        return $specialize;
    }

    public function updateSpecialize()
    {
        DB::table('specializes')
            ->where('id', $this->id)
            ->update([
                'specialized_name' => $this->specialized_name,

            ]);
    }

    public function destroySpecialize()
    {
        DB::table('specializes')
            ->where('id', $this->id)
            ->delete();

    }
    public function students()
    {
        // Define the relationship between Specialize and Student through Classes
        return $this->hasManyThrough(
            Student::class,
            Classes::class,
            'specializes_id', // Foreign key on Classes table
            'class_id',       // Foreign key on Student table
            'id',             // Local key on Specialize table
            'id'              // Local key on Classes table
        );
    }
    public function subject(){
        return $this->hasMany(Subject::class);
    }
    public function lecturer(){
        return $this -> hasMany(Lecturer::class);
    }
    public function classes(){
        return $this -> hasMany(Classes::class);
    }

    public function getNumberOfLecturer()
    {
        return $this->hasMany(Lecturer::class, 'specializes_id')->count();
    }
    public function getNumberOfSubject()
    {
        return $this->hasMany(Subject::class, 'specializes_id')->count();
    }
    public function getNumberOfClass()
    {
        return $this->hasMany(Classes::class, 'specializes_id')->count();
    }
    public function getNumberOfStudent()
    {
        return $this->students->count();
    }
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('specialized_name', 'like', '%' . $searchTerm . '%');
    }
}
