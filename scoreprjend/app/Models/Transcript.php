<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Transcript extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function index()
    {
        $transcript = DB::table('transcripts')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('classes', 'divisions.class_id', '=', 'classes.id')
            ->join('lecturers', 'divisions.lecturer_id', '=', 'lecturers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('specializes', 'subjects.specializes_id', '=', 'specializes.id')

            ->select(['transcripts.*',
                'divisions.*',
                'classes.class_name AS class_name',
                'lecturers.lecturer_name AS lecturer_name',
                'subjects.subject_name AS subject_name',
                'specializes.specialized_name AS specialized_name'

            ])


            ->get();

        return $transcript;
    }

    public function store()
    {

        DB::table('transcripts')

            ->insert([
                'transcript_name' => $this->transcript_name,
                'exam_times' => $this->exam_times,
                'division_id' => $this->division_id,


            ]);
    }

    public function edit()
    {
        $transcripts = DB::table('transcripts')
            ->where('id', $this->id)
            ->get();
        return $transcripts;
    }

    public function updateTranscript()
    {
        DB::table('transcripts')
            ->where('id', $this->id)
            ->update([
                'transcript_name' => $this->transcript_name,
                'exam_times' => $this->exam_times,
                'division_id' => $this->division_id,
            ]);
    }
    public function deleteTranscript()
    {
        DB::table('transcripts')
            ->where('id', $this->id)
            ->delete();
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function transcriptsByLecturer($lecturerId)
    {

        return $this->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('lecturers', 'divisions.lecturer_id', '=', 'lecturers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('specializes', 'subjects.specializes_id', '=', 'specializes.id')
            ->join('classes', 'divisions.class_id', '=', 'classes.id')
            ->where('lecturers.id', $lecturerId)
            ->select([
                'transcripts.*',
                'lecturers.lecturer_name AS lecturer_name',
                'subjects.subject_name AS subject_name',
                'specializes.specialized_name AS specialized_name',
                'classes.class_name AS class_name',
                // Các cột khác của bảng transcripts nếu cần
            ])
            ->get();

    }
    // Định nghĩa mối quan hệ với bảng Subject
//    public function subject()
//    {
//        return $this->belongsTo(Subject::class, 'subject_id', 'id');
//    }

    // Định nghĩa mối quan hệ với bảng Classes
//    public function class()
//    {
//        return $this->belongsTo(Classes::class, 'class_id', 'id');
//    }

    // Định nghĩa mối quan hệ với bảng Specialized (nếu có)
//    public function specialized()
//    {
//        return $this->belongsTo(Specialize::class, 'specialized_id', 'id');
//    }

    public function transcriptDetails()
    {
        return $this->hasMany(TranscriptDetail::class, 'transcript_id', 'id');
    }

    public function isFinish()
    {
        $divisionId = $this->division_id;
        $classId = Division::where('id', $divisionId)->value('class_id');
        $transcriptId = $this->id;

        // Kiểm tra số lượng sinh viên trong lớp
        $totalStudentsInClass = Student::where('class_id', $classId)->count();

        // Kiểm tra số lượng sinh viên đã nhận điểm trong transcript_detail
        $studentsWithScores = TranscriptDetail::where('transcript_id', $transcriptId)->count();

        // Nếu số lượng sinh viên với điểm bằng số lượng sinh viên trong lớp, transcript đã hoàn thành
        return $totalStudentsInClass > 0 && $totalStudentsInClass === $studentsWithScores;
    }
}
