<?php

namespace App\Http\Controllers;
use App\Models\Lecturer;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\Division;
use App\Models\Specialize;
use App\Models\Student;
use App\Models\Transcript;
use App\Models\TranscriptDetail;
use App\Http\Requests\StoreTranscriptDetailRequest;
use App\Http\Requests\UpdateTranscriptDetailRequest;
use Illuminate\Http\Request;
use  Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\Redirect;
use App\Models\Classes;
use App\Models\Subject;
class TranscriptDetailController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index($transcriptId)
    {
        $lecturer = Auth::guard('lecturer')->user(); // Lấy thông tin của lecturer đang đăng nhập
        $lecturerId = $lecturer->id;

        $transcript_details = TranscriptDetail::join('transcripts', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->join('students', 'transcript_details.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('lecturers', 'divisions.lecturer_id', '=', 'lecturers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('specializes', 'subjects.specializes_id', '=', 'specializes.id')
            ->where('transcript_id', $transcriptId)
            ->select([
                'transcript_details.*',
                'transcripts.transcript_name AS transcript_name',
                'transcripts.exam_times AS exam_times',
                'students.student_name AS student_name',
                'classes.class_name AS class_name',
//                'divisions.division_name AS division_name',
                'divisions.semester AS semester',
                'lecturers.lecturer_name AS lecturer_name',
                'subjects.subject_name AS subject_name',
                'specializes.specialized_name AS specialized_name',
                'school_years.sy_name AS sy_name'
                // Các cột khác của bảng transcripts nếu cần

            ])
//            ->orderBy('transcript.exam_times', 'asc')
            ->get();


        return view('transdetail.index' , ['transcript_details' => $transcript_details]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($transcriptId)
    {
        // Get the currently logged-in lecturer
        $lecturer = Auth::guard('lecturer')->user();
        $lecturerId = $lecturer->id;

        // Get divisions associated with the lecturer
        $divisions = Division::where('lecturer_id', $lecturerId)->pluck('id');

        // Lấy division_id từ transcript_id chỉ nếu nó thuộc danh sách division của giáo viên
        $divisionId = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->pluck('division_id')
            ->first();

        // Lấy danh sách sinh viên từ lớp học của transcript hiện tại và chưa có điểm trong lần thi này
        $students = Student::whereIn('class_id', function ($query) use ($divisionId, $transcriptId) {
            $query->select('class_id')
                ->from('divisions')
                ->where('id', $divisionId)
                ->whereNotExists(function ($subquery) use ($transcriptId) {
                    $subquery->select(DB::raw(1))
                        ->from('transcript_details')
                        ->whereRaw('transcript_details.student_id = students.id')
                        ->where('transcript_details.transcript_id', $transcriptId);
                });
        })->get();

        // Lấy chỉ một semester từ division_id
        $semester = Division::where('id', $divisionId)->value('semester');

        // Lấy exam_times từ transcript_id
        $examTimes = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->pluck('exam_times')
            ->first();

        // Lấy các bản ghi transcript tương ứng với division_id và exam_times
        $transcripts = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->get();


        // Lấy thông tin lớp học cho sinh viên
        $classes = Classes::whereIn('id', $students->pluck('class_id'))
            ->with('school_year')
            ->get();

        return view('transdetail.create', [
            'transcripts' => $transcripts,
            'students' => $students,
            'classes' => $classes,
            'semester' => $semester
        ]);
    }




    public function created($transcriptId)
    {
        // Get the currently logged-in lecturer
        $lecturer = Auth::guard('lecturer')->user();
        $lecturerId = $lecturer->id;

        // Get divisions associated with the lecturer
        $divisions = Division::where('lecturer_id', $lecturerId)->pluck('id');

        // Lấy giá trị transcript_id từ request
//        $transcriptId = $request->input('transcript_id');

        // Lấy division_id từ transcript_id chỉ nếu nó thuộc danh sách division của giáo viên
        $divisionId = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->pluck('division_id')
            ->first();

        // Lấy chỉ một semester từ division_id
        $semester = Division::where('id', $divisionId)->value('semester');

        // Lấy exam_times từ transcript_id


        // Lấy các bản ghi transcript tương ứng với division_id và exam_times
        $transcripts = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->get();

        // Lấy danh sách sinh viên từ lớp học của transcript hiện tại
        $students = Student::whereIn('class_id', function ($query) use ($divisionId) {
            $query->select('class_id')
                ->from('divisions')
                ->where('id', $divisionId);
        })
            ->whereIn('id', function ($query) use ($divisionId) {
                $query->select('student_id')
                    ->from('transcript_details')
                    ->whereIn('transcript_id', function ($subquery) use ($divisionId) {
                        $subquery->select('id')
                            ->from('transcripts')
                            ->whereIn('division_id', [$divisionId])
                            ->where('exam_times', 0);
                    })
                    ->where(function ($subquery) {
                        $subquery->where('score','<', 5)
                            ->orWhereNull('score');
                    });
            })
            ->get();


        // Lấy thông tin lớp học cho sinh viên
        $classes = Classes::whereIn('id', $students->pluck('class_id'))
            ->with('school_year')
            ->get();



        return view('transdetail.created', [
            'transcripts' => $transcripts,
            'students' => $students,
            'classes' => $classes,
            'semester' => $semester,

        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Nhận dữ liệu từ request
        $transcriptId = $request->input('transcript_id');
        $transcriptDetails = [];
        $lecturer = Auth::guard('lecturer')->user();
        $lecturerId = $lecturer->id;

        // Kiểm tra xem lecturer có quyền truy cập vào transcript này không
        $transcript = Transcript::where('id', $transcriptId)
            ->whereHas('division', function ($query) use ($lecturerId) {
                $query->where('lecturer_id', $lecturerId);
            })
            ->first();

        if (!$transcript) {
            return redirect()->route('transcript.index')->with('error', 'You do not have access to this transcript.');
        }

        // Get divisions associated with the lecturer
        $divisions = Division::where('lecturer_id', $lecturerId)
            ->where('id', $transcript->division_id)
            ->get();

        // Lấy danh sách sinh viên trong các lớp của giáo viên
        $students = Student::whereIn('class_id', $divisions->pluck('class_id'))
            ->get();

        // Lặp qua mảng sinh viên và kiểm tra và tạo dữ liệu cho mỗi sinh viên
        foreach ($students as $student) {
            // Kiểm tra xem sinh viên đã có điểm trong transcript_details của lần thi này chưa
            $existingTranscriptDetail = TranscriptDetail::where('transcript_id', $transcriptId)
                ->where('student_id', $student->id)
                ->first();

            if (!$existingTranscriptDetail) {
                // Kiểm tra điểm số có hợp lệ không
                $score = $request->input('score_' . $student->id);
                if ($score < 0 || $score > 10) {
                    return redirect()->route('transcript.index')->with('error', 'Minimum Score is 0 and Maximum Score is 10.');
                }

                $note = [];

                // Kiểm tra checkbox đã được chọn hay không
                if ($request->has('note_' . $student->id)) {
                    // Lấy giá trị của checkbox
                    $note[] = $request->input('note_' . $student->id);
                }

                // Thêm dữ liệu của sinh viên vào mảng transcriptDetails
                $transcriptDetails[] = [
                    'transcript_id' => $transcriptId,
                    'student_id' => $student->id,
                    'note' => implode(',', $note), // Chuyển đổi mảng thành chuỗi
                    'score' => $score,
                ];
            }
        }

        // Kiểm tra xem có bản ghi transcript_details bị bỏ trống không
        if (empty($note)) {
            return redirect()->route('transcript.index')->with('error', 'No new scores were added. Students might already have scores in this transcript.');
        }

        // Thêm nhiều bản ghi vào cơ sở dữ liệu
        TranscriptDetail::insert($transcriptDetails);

        // Chuyển hướng hoặc thông báo sau khi thêm thành công
        return redirect()->route('transcript.index')->with('success', 'Transcript details have been added successfully.');
    }



    public function stored(Request $request)
    {

        // Nhận dữ liệu từ request
        // Get divisions associated with the lecturer
        $lecturer = Auth::guard('lecturer')->user();
        $lecturerId = $lecturer->id;
        $divisions = Division::where('lecturer_id', $lecturerId)->pluck('id');

        // Lấy giá trị transcript_id từ request
        $transcriptId = $request->input('transcript_id');
        // Lấy các bản ghi transcript tương ứng với division_id và exam_times
        $transcripts = Transcript::whereIn('division_id', $divisions)
            ->where('id', $transcriptId)
            ->get();

        // Kiểm tra exam_times của transcript dựa trên transcript_id
        $examTimes = Transcript::where('id', $transcriptId)->value('exam_times');

        // Chỉ thêm bản ghi transcript_detail cho sinh viên có điểm dưới 5 và exam_times của transcript_id bằng 0
        if ($examTimes == 1) {
            // Lấy division_id từ transcript_id chỉ nếu nó thuộc danh sách division của giáo viên
            $divisionId = Transcript::whereIn('division_id', $divisions)
                ->where('id', $transcriptId)
                ->pluck('division_id')
                ->first();

            // Lấy chỉ một semester từ division_id
            $semester = Division::where('id', $divisionId)->value('semester');

            // Lấy exam_times từ transcript_id

            // Lấy danh sách sinh viên từ lớp học của transcript hiện tại
            $students = Student::whereIn('class_id', function ($query) use ($divisionId) {
                $query->select('class_id')
                    ->from('divisions')
                    ->where('id', $divisionId);
            })
                ->whereIn('id', function ($query) use ($divisionId) {
                    $query->select('student_id')
                        ->from('transcript_details')
                        ->whereIn('transcript_id', function ($subquery) use ($divisionId) {
                            $subquery->select('id')
                                ->from('transcripts')
                                ->whereIn('division_id', [$divisionId])
                                ->where('exam_times', 0);
                        })
                        ->where(function ($subquery) {
                            $subquery->where('score','<', 5)
                                ->orWhereNull('score');
                        });
                })
                ->get();

            // Tạo một mảng để chứa dữ liệu cần lưu vào cơ sở dữ liệu
            $transcriptDetails = [];

            // Lặp qua danh sách sinh viên và tạo dữ liệu cho mỗi sinh viên
            foreach ($students as $student) {

                $note = [];

                // Kiểm tra điểm số có hợp lệ không
                $score = $request->input('score_' . $student->id);
                if ($score < 0 || $score > 10) {
                    return redirect()->route('transcript.index')->with('error', 'Minimum Score is 0 and Maximum Score is 10.');
                }

                // Kiểm tra checkbox đã được chọn hay không
                if ($request->has('note_' . $student->id)) {
                    // Lấy giá trị của checkbox
                    $note[] = $request->input('note_' . $student->id);
                }

                // Thêm dữ liệu của sinh viên vào mảng transcriptDetails
                $transcriptDetails[] = [
                    'transcript_id' => $transcriptId,
                    'student_id' => $student->id,
                    'note' => implode(',', $note), // Chuyển đổi mảng thành chuỗi
                    'score' => $score,
                ];
            }

            // Kiểm tra xem có bản ghi transcript_details bị bỏ trống không
            if (empty($note)) {
                return redirect()->route('transcript.index')->with('error', 'Some students do not have scores. You must add points for them.');
            }

            // Thêm nhiều bản ghi vào cơ sở dữ liệu
            TranscriptDetail::insert($transcriptDetails);
        }

        // Chuyển hướng hoặc thông báo sau khi thêm thành công
        return redirect()->route('transcript.index')->with('success', 'Transcript details have been added successfully.');
    }




    /**
     * Display the specified resource.
     */
    public function show()
    {
        $student = Auth::guard('student')->user(); // Lấy thông tin của sinh viên đang đăng nhập
        $studentId = $student->id;

        // Tạo một đối tượng TranscriptDetail
        $transcriptDetailModel = new TranscriptDetail();

        // Lấy danh sách transcript details liên quan đến sinh viên
        $transcriptDetails = $transcriptDetailModel->transcriptDetailsByStudent($studentId);

        return view('transdetail.show', [
            'transcriptDetails' => $transcriptDetails
        ]);
    }


    public function search(Request $request)
    {

        $keyword = $request->input('keyword');

        // Sử dụng Query Builder để tạo truy vấn tìm kiếm
        $results = TranscriptDetail::where(function ($query) use ($keyword) {
            $query->where('student_id', 'like', '%' . $keyword . '%')
                ->orWhere('specializes_id', 'like', '%' . $keyword . '%')
                ->orWhere('class_id', 'like', '%' . $keyword . '%')
                ->orWhere('subject_id', 'like', '%' . $keyword . '%');
        })->get();

        return view('transdetail.show', compact('results'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TranscriptDetail $transcriptDetail, Request $request)
    {
        // Get the currently logged-in lecturer
        $lecturer = Auth::guard('lecturer')->user();
        $lecturerId = $lecturer->id;
        // Get divisions associated with the lecturer
        $divisions = Division::where('lecturer_id', $lecturerId)->get();

        // Get transcripts associated with the divisions
        $transcripts = Transcript::whereIn('division_id', $divisions->pluck('id'))->get();
        // Get students with scores
        $students = Student::whereIn('class_id', $divisions->pluck('class_id'))->get();
        $classes = Classes::whereIn('id', $students->pluck('class_id'))
            ->with('school_year')
            ->get();

        $objTransDetail = new TranscriptDetail();
        $objTransDetail-> id = $request-> id;
        $transcript_details = $objTransDetail->edit();
//        dd($transcriptDetail);

        return view('transdetail.edit', [
            'transcripts' => $transcripts,
            'students' => $students,
            'classes' => $classes,
            'transcript_details' => $transcript_details,
            'id' => $objTransDetail->id
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTranscriptDetailRequest $request, TranscriptDetail $transcriptDetail)
    {
        $note = $request->note;
        $score = $request->score;

    if (($note == 2 || $note == 3) && !empty($score)) {
        // Nếu exam_times là 2 hoặc 3 và score không rỗng, hiển thị thông báo lỗi
    Session::flash('error', 'Score should be empty if this student get banned or skipped the exams.');
    } elseif (( $note == 1) && empty($score)) {
        // Nếu exam_times là 1 hoặc 0 và score rỗng, hiển thị thông báo lỗi
        Session::flash('error', 'This Student passed the exams but why he/she doesnt have point??.');
    } elseif ($score > 10 ){
        Session::flash('error', 'Maximum Score is 10.');
    }elseif ($score < 0 ){
        Session::flash('error', 'Minimum Score is 0.');
    } else {
        $obj = new TranscriptDetail();
        $obj->id = $request->id;
        $obj->transcript_id = $request->transcript_id;
        $obj->student_id = $request->student_id;
        $obj->note = $request->note;
        $obj->score = $request->score;
        $obj->updateTransDetail();
        Session::flash('success', 'Updated Record Successfully');
    }

        return redirect()->route('transcript.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TranscriptDetail $transcriptDetail, Request $request)
    {
        $obj = new TranscriptDetail();
        $obj->id = $request-> id;
        $obj->deleteTransDetail();
        Session::flash('success', 'Deleted Record');

        return redirect()->route('transcript.index');

    }
    public function query(Request $request)
    {
        $classId = $request->input('class_id');
        $subjectId = $request->input('subject_id');
//            dump($classId, $subjectId);

        // Thực hiện truy vấn dựa trên $classId và $subjectId
        // Ví dụ:
        $result = DB::table('transcripts')
            ->join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('transcript_details', 'transcript_details.transcript_id', '=', 'transcripts.id')
            ->where('divisions.class_id', $classId)
            ->where('divisions.subject_id', $subjectId)
//            ->select('transcripts.*', 'exam_times') // Chọn tất cả các cột từ bảng transcripts và thêm cột exam_times
            ->get();


        $studentCount = Student::count();
//        $reportCount = Report::where('status','=', 0)->count();
        $lecturerCount = Lecturer::count();
        $divisionCount = Division::count();


        $transcriptBelow5Count = TranscriptDetail::where('score', '<', 5)
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_times', 0)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        $transcriptAbove5Count = TranscriptDetail::where('score', '>=', 5)
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_times', 0)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();


        // Đếm số lượng transcript_details có điểm null và note = 2 trong class và subject đã chọn
        $transcriptNoScoreCount1 = TranscriptDetail::whereNull('score')
            ->where(function ($query) {
                $query->where('note', 2);
            })
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_times', 0)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        // Đếm số lượng transcript_details có điểm null và note = 3 trong class và subject đã chọn
        $transcriptNoScoreCount2 = TranscriptDetail::whereNull('score')
            ->where(function ($query) {
                $query->where('note', 3);
            })
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_times', 0)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        $transcriptBelow5Count2nd = TranscriptDetail::where('score', '<', 5)
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_times', 1)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        $transcriptAbove5Count2nd = TranscriptDetail::where('score', '>=', 5)
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_times', 1)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        // Đếm số lượng transcript_details có điểm null và note = 2 trong class và subject đã chọn
        $transcriptNoScoreCount12nd = TranscriptDetail::whereNull('score')
            ->where(function ($query) {
                $query->where('note', 2);
            })
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_times', 1)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        // Đếm số lượng transcript_details có điểm null và note = 3 trong class và subject đã chọn
        $transcriptNoScoreCount22nd = TranscriptDetail::whereNull('score')
            ->where(function ($query) {
                $query->where('note', 3);
            })
            ->whereHas('transcript', function ($query) use ($classId, $subjectId) {
                $query->where('exam_times', 1)
                    ->whereHas('division', function ($query) use ($subjectId, $classId) {
                        $query->where('class_id', $classId)
                            ->whereHas('subject', function ($query) use ($subjectId) {
                                $query->where('id', $subjectId);
                            });
                    });
            })
            ->count();

        // Số lớp có sinh viên
        $classWithStudentsCount = Classes::has('student')->count();

        // Số lớp không có sinh viên
        $classWithoutStudentsCount = Classes::doesntHave('student')->count();


        return view('dashboard', [
            'studentCount' => $studentCount,
//            'reportCount' => $reportCount,
            'lecturerCount' => $lecturerCount,
            'divisionCount' => $divisionCount,
            'classWithStudentsCount' => $classWithStudentsCount,
            'classWithoutStudentsCount' => $classWithoutStudentsCount,
            'transcriptBelow5Count' => $transcriptBelow5Count,
            'transcriptAbove5Count' => $transcriptAbove5Count,
            'transcriptNoScoreCount1' => $transcriptNoScoreCount1,
            'transcriptNoScoreCount2' => $transcriptNoScoreCount2,
            'transcriptBelow5Count2nd' => $transcriptBelow5Count2nd,
            'transcriptAbove5Count2nd' => $transcriptAbove5Count2nd,
            'transcriptNoScoreCount12nd' => $transcriptNoScoreCount12nd,
            'transcriptNoScoreCount22nd' => $transcriptNoScoreCount22nd,
            'result' => $result,
            'abd' => $classId,
            'abc' => $subjectId
        ]);
    }

//    public function showReportForm()
//    {
//
//        $student = Auth::guard('student')->user(); // Lấy thông tin của sinh viên đang đăng nhập
//        $studentId = $student->id;
//
//        // Tạo một đối tượng TranscriptDetail
//        $transcriptDetailModel = new TranscriptDetail();
//
//        // Lấy danh sách transcript details liên quan đến sinh viên
//        $transcriptDetails = $transcriptDetailModel->transcriptDetailsByStudent($studentId);
//
//
//        return view('transdetail.report',['transcriptDetails' => $transcriptDetails]
//        );
//    }
//
//
//    public function submitReport(Request $request)
//    {
//        // Xử lý lưu khiếu nại vào cơ sở dữ liệu
//        // Hiển thị thông báo khi khiếu nại được gửi thành công
//        Session::flash('success', 'Report Has Been Sent');
//        return redirect()->route('transdetail.report');
//
//    }
}
