<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Division extends Model
{
    public $timestamps = false;
    use HasFactory;

    public function index()
    {
        $division = DB::table('divisions')
            ->join('classes', 'divisions.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.sy_id', '=', 'school_years.id')
            ->join('lecturers', 'divisions.lecturer_id', '=', 'lecturers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('specializes', 'subjects.specializes_id', '=', 'specializes.id')
            ->join('admins', 'divisions.admin_id', '=', 'admins.id')
            ->select(['divisions.*',
                'classes.*',
                'school_years.*',
                'lecturers.lecturer_name AS lecturer_name',
                'specializes.specialized_name AS specialized_name',
                'subjects.subject_name AS subject_name',
                'admins.username AS username'

            ])


            ->get();

        return $division;
    }

    public function store()
    {
        DB::table('divisions')
            ->insert([
//                'division_name' => $this->division_name,
                'semester' => $this->semester,
                'class_id' => $this->class_id,
                'lecturer_id' => $this->lecturer_id,
                'subject_id' => $this->subject_id,
                'admin_id' => $this->admin_id
            ]);
    }

    public function edit()
    {
        $divisions = DB::table('divisions')
            ->where('id', $this->id)
            ->get();
        return $divisions;
    }

    public function updateDivision()
    {
        DB::table('divisions')
            ->where('id', $this->id)
            ->update([
//                'division_name' => $this->division_name,
                'semester' => $this->semester,
                'class_id' => $this->class_id,
//                'lecturer_id' => $this->lecturer_id,
//                'subject_id' => $this->subject_id,
                'admin_id' => $this->admin_id
            ]);
    }
    public function updateDivision2()
    {
        DB::table('divisions')
            ->where('id', $this->id)
            ->update([
//                'division_name' => $this->division_name,
                'semester' => $this->semester,
                'class_count' => $this->class_count,
                'admin_id' => $this->admin_id
            ]);
    }

    public function deleteDivision()
    {
        DB::table('divisions')
            ->where('id', $this->id)
            ->delete();
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }
    public function transcript()
    {
        return $this->hasMany(Transcript::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->join('lecturers', 'divisions.lecturer_id', '=', 'lecturers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('classes', 'divisions.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('specializes', 'subjects.specializes_id', '=', 'specializes.id')
            ->join('admins', 'divisions.admin_id', '=', 'admins.id')
            ->where('divisions.admin_id', auth('admin')->user()->id)
            ->where(function($innerQuery) use ($searchTerm) {
                $innerQuery->where('lecturers.lecturer_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('specializes.specialized_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('subjects.subject_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('classes.class_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('school_years.sy_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('admins.username', 'like', '%' . $searchTerm . '%');
            })
            ->select([
                'divisions.*',
                'lecturers.lecturer_name AS lecturer_name',
                'specializes.specialized_name AS specialized_name',
                'subjects.subject_name AS subject_name',
                'classes.class_name AS class_name',
                'school_years.sy_name AS sy_name',
                'admins.username AS username'
            ]);
    }


    // ... (các trường và các phương thức khác của model Division)



//    public function getStatus()
//    {
//        $transcripts = Transcript::where('division_id', $this->id)->get();
//        $hasUnfinishedTranscript = false;
//
//        foreach ($transcripts as $transcript) {
//            // Kiểm tra xem có student nào có điểm dưới 5 và không có bản ghi trong TranscriptDetail không
//            $studentsCount = Student::where('class_id', $transcript->class_id)->count();
//            $transcriptDetailCount = TranscriptDetail::where('transcript_id', $transcript->id)->count();
//
//            if ($transcript->exam_times == 0 && $studentsCount > 0 && $transcriptDetailCount === 0) {
//                // Nếu có student có điểm dưới 5 và không có bản ghi trong TranscriptDetail
//                $hasUnfinishedTranscript = true;
//                break;
//            }
//
//            if ($transcript->exam_times == 1 && $transcript->isFinish() === false) {
//                // Nếu có bản ghi Transcript với exam_times là 1 và chưa hoàn thành (isFinish() === false)
//                $hasUnfinishedTranscript = true;
//                break;
//            }
//        }
//
//        if ($hasUnfinishedTranscript) {
//            return 'Working';
//        } elseif ($transcripts->isEmpty()) {
//            return 'Not Working';
//        } else {
//            return 'Job Done';
//        }
//    }
    public function getStatus()
    {
        $transcripts = Transcript::where('division_id', $this->id)->get();

        // Kiểm tra xem có transcript nào có exam_times bằng 0 và các transcript_detail tương ứng có điểm là 1, 2 hoặc 3
        $hasCompletedTranscript0 = $transcripts->contains(function ($transcript) {
            return $transcript->exam_times == 0 && $transcript->transcriptDetails->isNotEmpty() &&
                $transcript->transcriptDetails->every(function ($transcriptDetail) {
                    $note = $transcriptDetail->note;
                    return $note == 2 || $note == 3;
                });
        });

// Kiểm tra xem có transcript nào có exam_times bằng 1 và có ít nhất một transcript_detail có điểm là 1, 2 hoặc 3
        $hasPendingTranscript0 = $transcripts->contains(function ($transcript) {
            return $transcript->exam_times == 1 && $transcript->transcriptDetails->isNotEmpty() &&
                $transcript->transcriptDetails->contains(function ($transcriptDetail) {
                    $note = $transcriptDetail->note;
                    return $note == 2 || $note == 3;
                });
        });

        // Kiểm tra xem có transcript nào có exam_times bằng 0 và các transcript_detail tương ứng có điểm trên hoặc bằng 5
        $hasCompletedTranscript = $transcripts->contains(function ($transcript) {
            return $transcript->exam_times == 0 && $transcript->transcriptDetails->isNotEmpty() &&
                $transcript->transcriptDetails->every(function ($transcriptDetail) {
                    return !empty($transcriptDetail->score) && $transcriptDetail->score >= 5;
                });
        });

        // Kiểm tra xem có transcript nào có exam_times bằng 0 và các transcript_detail tương ứng có điểm trên hoặc bằng 5 hoặc note bằng 2 hoặc 3
        $hasCompletedTranscript2 = $transcripts->contains(function ($transcript) {
            return $transcript->exam_times == 0 && $transcript->transcriptDetails->isNotEmpty() &&
                $transcript->transcriptDetails->contains(function ($transcriptDetail) {
                    return !empty($transcriptDetail->score) && $transcriptDetail->score >= 5 || in_array($transcriptDetail->note, [2, 3]);
                });
        });

        // Kiểm tra xem có transcript nào có exam_times bằng 1 và có ít nhất một transcript_detail có điểm trên hoặc bằng 5
        $hasPendingTranscript = $transcripts->contains(function ($transcript) {
            return $transcript->exam_times == 1 && $transcript->transcriptDetails->isNotEmpty() &&
                $transcript->transcriptDetails->contains(function ($transcriptDetail) {
                    return !empty($transcriptDetail->score) && $transcriptDetail->score ;
                });
        });

        // Kiểm tra xem có transcript nào có exam_times bằng 1 và có ít nhất một transcript_detail có điểm trên hoặc bằng 5 hoặc note bằng 2 hoặc 3
        $hasPendingTranscript2 = $transcripts->contains(function ($transcript) {
            return $transcript->exam_times == 1 && $transcript->transcriptDetails->isNotEmpty() &&
                $transcript->transcriptDetails->contains(function ($transcriptDetail) {
                    return !empty($transcriptDetail->score) && $transcriptDetail->score || in_array($transcriptDetail->note, [2, 3]);
                });
        });

        // Kiểm tra xem có transcript nào có exam_times bằng 0 và chưa có transcript_detail
        $hasUnfinishedTranscript1st= $transcripts->contains(function ($transcript) {
            return $transcript->exam_times == 0  && $transcript->transcriptDetails->isEmpty();
        });

        $hasUnfinishedTranscript2nd = $transcripts->contains(function ($transcript) {
            return $transcript->exam_times == 1  && $transcript->transcriptDetails->isEmpty();
        });

        if ($hasCompletedTranscript || $hasPendingTranscript) {
            // Nếu có transcripts với exam_times bằng 0 và có transcript_details có điểm trên hoặc bằng 5
            // hoặc có transcripts với exam_times bằng 1 và có ít nhất một transcript_detail có điểm trên hoặc bằng 5
            return 'Job Done';
        }

        if ($hasCompletedTranscript2 && !$hasPendingTranscript2) {
            // Nếu có transcripts với exam_times bằng 0 và có transcript_details có điểm trên hoặc bằng 5
            // hoặc có transcripts với exam_times bằng 1 và có ít nhất một transcript_detail có điểm trên hoặc bằng 5
            return 'Working';
        }

        if ($hasCompletedTranscript2 && $hasPendingTranscript2) {
            // Nếu có transcripts với exam_times bằng 0 và có transcript_details có điểm trên hoặc bằng 5
            // hoặc có transcripts với exam_times bằng 1 và có ít nhất một transcript_detail có điểm trên hoặc bằng 5
            return 'Job Done';
        }

        if ($hasPendingTranscript0) {
            // Nếu có transcripts với exam_times bằng 0 và có transcript_details có điểm trên hoặc bằng 5
            // hoặc có transcripts với exam_times bằng 1 và có ít nhất một transcript_detail có điểm trên hoặc bằng 5
            return 'Job Done';
        }

        if ($hasCompletedTranscript0) {
            // Nếu có transcripts với exam_times bằng 0 và có transcript_details có điểm trên hoặc bằng 5
            // hoặc có transcripts với exam_times bằng 1 và có ít nhất một transcript_detail có điểm trên hoặc bằng 5
            return 'Working';
        }

        if ($hasUnfinishedTranscript1st) {
            // Nếu có transcripts với exam_times bằng 1 và chưa có transcript_details
            return 'Working';
        }

        if ($hasUnfinishedTranscript2nd) {
            // Nếu có transcripts với exam_times bằng 1 và chưa có transcript_details
            return 'Working';
        }
        if ($transcripts->isEmpty()) {
            // Nếu không có transcript nào tương ứng với division
            return 'Not Working';
        }

        // Nếu không có transcript nào thỏa mãn các điều kiện trên
        // thì status của division sẽ là Not Working
        return 'Working';
    }

}







