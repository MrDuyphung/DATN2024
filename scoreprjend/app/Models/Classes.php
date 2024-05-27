<?php
//
////namespace App\Models;
////
////use Illuminate\Database\Eloquent\Factories\HasFactory;
////use Illuminate\Database\Eloquent\Model;
////
////class Classes extends Model
////{
////    use HasFactory;
////    public $timestamps = false;
////    protected $fillable = ['class_name', 'specializes_id', 'school_year_id'];
//    public function student(){
//       return $this -> hasMany(Student::class);
//    }
//
//    public function specializes(){
//        return $this -> belongsTo(Specialize::class);
//    }
//    public function school_year(){
//        return $this -> belongsTo(SchoolYear::class);
//    }
//}


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Classes extends Model
{

    public $timestamps = false;
    use HasFactory;

    public function index()
    {
        $classes = DB::table('classes')
            ->join('specializes', 'classes.specializes_id', '=', 'specializes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->select(['classes.*',
                'specializes.specialized_name AS specialized_name',
                'school_years.sy_start AS sy_start',
                'school_years.sy_end AS sy_end',
                'school_years.sy_name AS sy_name'
            ])


            ->get();

        return $classes;
    }

    public function store()
    {
        DB::table('classes')
            ->insert([
                'class_name' => $this->class_name,
                'specializes_id' => $this->specializes_id,
                'school_year_id' => $this->school_year_id
            ]);
    }

    public function edit()
    {
        $classes = DB::table('classes')
            ->where('id', $this->id)
            ->get();
        return $classes;
    }

    public function updateClass()
    {
        DB::table('classes')
            ->where('id', $this->id)
            ->update([
                'class_name' => $this->class_name,
                'specializes_id' => $this->specializes_id,
                'school_year_id' => $this->school_year_id
            ]);
    }

    public function deleteClass()
    {
        DB::table('classes')
            ->where('id', $this->id)
            ->delete();
    }

        public function student(){
       return $this -> hasMany(Student::class, 'class_id', 'id');
    }
    public function transcriptDetails()
    {
        return $this->hasMany(TranscriptDetail::class, 'student_id', 'id');
    }
    public function school_year(){
        return $this -> belongsTo(SchoolYear::class);
    }

    public function getNumberOfStudents()
    {
        return $this->hasMany(Student::class, 'class_id')->count();
    }

}
