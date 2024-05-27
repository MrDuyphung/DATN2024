<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Division;
use App\Models\Lecturer;
use App\Models\SchoolYear;
use App\Models\Specialize;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Transcript;
use App\Http\Requests\StoreTranscriptRequest;
use App\Http\Requests\UpdateTranscriptRequest;
use App\Models\TranscriptDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TranscriptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lecturer = Auth::guard('lecturer')->user(); // Get the currently logged-in lecturer
        $lecturerId = $lecturer->id;

        // Tạo một đối tượng Transcript


        // Lấy danh sách transcripts liên quan đến Lecturer
        $transcripts = Transcript::join('divisions', 'transcripts.division_id', '=', 'divisions.id')
            ->join('lecturers', 'divisions.lecturer_id', '=', 'lecturers.id')
            ->join('subjects', 'divisions.subject_id', '=', 'subjects.id')
            ->join('specializes', 'subjects.specializes_id', '=', 'specializes.id')
            ->join('classes', 'divisions.class_id', '=', 'classes.id')
            ->join('school_years', 'classes.school_year_id', '=', 'school_years.id')
            ->where('lecturers.id', $lecturerId)
            ->select([
                'transcripts.*',
//                'divisions.division_name AS division_name',
                'divisions.semester AS semester',
                'lecturers.lecturer_name AS lecturer_name',
                'subjects.subject_name AS subject_name',
                'specializes.specialized_name AS specialized_name',
                'classes.class_name AS class_name',
                'school_years.sy_name AS sy_name'
                // Các cột khác của bảng transcripts nếu cần
            ])
            ->orderBy('divisions.semester', 'asc')
            ->get();


        foreach ($transcripts as $transcript) {
            $transcript->isFinish();
        }



        return view('transcript.index', [
            'transcripts' => $transcripts,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create($division_id)
    {
        $lecturer = Auth::guard('lecturer')->user(); // Get the currently logged-in lecturer
        $lecturerId = $lecturer->id;

        // Lấy danh sách divisions của giáo viên hiện tại
        $division = Division::find($division_id);
        $subjects = Subject::whereIn('id', $division->pluck('subject_id'))->get();

        // Lấy danh sách classes thuộc các subject trên
        $classes = Classes::whereIn('specializes_id', $subjects->pluck('specializes_id'))
            ->with('school_year')
                ->get();

        return view('transcript.create', [
            'division' => $division,
            'subjects' => $subjects,
            'classes' => $classes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTranscriptRequest $request)
    {
        $divisionId = $request->division_id;
        $examTimes = $request->exam_times;
        $transcriptId = $request->transcript_id;

        // Kiểm tra xem đã tồn tại transcript có exam_times bằng 0 và ở trạng thái "Finished" chưa
        $firstTimesFinished = Transcript::where('division_id', $divisionId)
            ->where('exam_times', 0)
            ->exists();


        $firstTimesFinished2 = Transcript::where('division_id', $divisionId)

            ->exists();

        // Kiểm tra xem có sinh viên nào có điểm dưới 5 hoặc điểm là null trong bản ghi transcript_details không
        $studentsWithLowScore = Transcript::where('division_id', $divisionId)
            ->where('exam_times', 0)
            ->whereHas('transcriptDetails', function ($query) {
                $query->where('score', '>=', 5);
            })

            ->exists();
        if ($examTimes == 1 && !$firstTimesFinished) {
            // Nếu exam_times là 1 và không có sinh viên nào có điểm dưới 5 hoặc điểm là null trong transcript_details của lần thi đầu tiên, hiển thị thông báo lỗi
            Session::flash('error', 'You have to finish with 1st times so you can start working with 2nd times.');

            // Chuyển hướng trở về trang transcript.index
            return redirect()->route('transcript.index');
        }
        // Kiểm tra xem đã hoàn thành lần thi đầu tiên và không có sinh viên nào có điểm dưới 5 hoặc điểm là null trong transcript_details không
        elseif ($examTimes == 0 && $studentsWithLowScore) {
            // Nếu đều đáp ứng điều kiện, hiển thị thông báo lỗi
            Session::flash('error', 'This division has completed, No one have to take a 2nd exams.');

            // Chuyển hướng trở về trang transcript.index
            return redirect()->route('transcript.index');
        } elseif (Transcript::where('division_id', $divisionId)
            ->where('exam_times', $examTimes)
            ->exists()) {
            // Nếu đã tồn tại bản ghi transcript với exam_times = 1 cho division này, hiển thị thông báo lỗi
            Session::flash('error', 'This division has already exists.');

            // Chuyển hướng trở về trang transcript.index
            return redirect()->route('transcript.index');
        } else {
            // Tạo bản ghi transcript mới và lưu vào cơ sở dữ liệu
            $transcript = new Transcript();
            $transcript->transcript_name = $request->transcript_name;
            $transcript->exam_times = $examTimes;
            $transcript->division_id = $divisionId;
            $transcript->save();

            Session::flash('success', 'Added New Record');

            // Chuyển hướng về trang transcript.index
            return redirect()->route('transcript.index');
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Transcript $transcript)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transcript $transcript, Request $request)
    {
        $lecturer = Auth::guard('lecturer')->user(); // Get the currently logged-in lecturer
        $lecturerId = $lecturer->id;

        // Lấy danh sách divisions của giáo viên hiện tại
        $divisions = Division::where('lecturer_id', $lecturerId)->get();

        $subjects = Subject::whereIn('id', $divisions->pluck('subject_id'))->get();

        // Lấy danh sách classes thuộc các subject trên
        $classes = Classes::whereIn('specializes_id', $subjects->pluck('specializes_id'))->get();

        $objTranscript = new Transcript();
        $objTranscript->id = $request->id;
        $transcripts = $objTranscript->edit();

        return view('transcript.edit', [
            'divisions' => $divisions,
            'classes' => $classes,
            'subjects' => $subjects,
            'transcripts' => $transcripts,
            'id' => $objTranscript->id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTranscriptRequest $request, Transcript $transcript)
    {

        $divisionId = $request->division_id;
        $exam_times = $request->exam_times;
        $transcriptId = $request->transcript_id;

        // Lấy subject_id từ Division

//        $classCount = Division::where('id', $divisionId)->value('class_count');

        // Kiểm tra xem subject_id từ Division khớp với subject_id từ Transcript không

        // Kiểm tra xem đã tồn tại bản ghi trong TranscriptDetail tương ứng với transcript đang được cập nhật và có student thuộc transcript_id và class_id không
//        $existingTranscriptDetail = TranscriptDetail::where('transcript_id', $transcript->id)
//            ->whereHas('student', function ($query) use ($transcript) {
//                $query->where('class_id', $transcript->class_id);
//
//            })
//            ->exists();
//
//        if ($existingTranscriptDetail) {
//            // Nếu đã tồn tại bản ghi trong TranscriptDetail có student thuộc transcript_id và class_id đó, hiển thị thông báo lỗi
//            return redirect()->route('transcript.index')->with('error', 'Cannot update transcript. Related TranscriptDetail record with corresponding student exists.');
//        }

        // Kiểm tra xem đã tồn tại bản ghi có division_id và class_id tương ứng chưa
        $existingRecord = Transcript::where('exam_times', $exam_times)
            ->first();

//        // Kiểm tra số lượng lớp học đã đăng ký bởi giáo viên cho division_id này
//        $registeredClassesCount = Transcript::where('division_id', $divisionId)
//            ->count();


        // Kiểm tra xem lớp học đã được đăng ký bởi giáo viên nào đó chưa
//        $isClassAssigned =  Transcript::where('class_id', $classId)
//            ->exists();

        if (!$existingRecord ) {
            // Nếu chưa tồn tại và lớp học chưa được đăng ký bởi giáo viên, tạo một bản ghi mới và lưu trữ nó
            $obj = new Transcript();
            $obj->id = $request->id;
            $obj->transcript_name = $request->transcript_name;
            $obj->exam_times = $exam_times;
            $obj->division_id = $divisionId;

            $obj->updateTranscript(); // Lưu trữ bản ghi

            Session::flash('success', 'Updated Record Successfully');
        } elseif ($existingRecord) {
            // Nếu lớp học đã tồn tại, hiển thị thông báo lỗi
            Session::flash('error', 'Transript already exists.');
        }
//        elseif ($isClassAssigned) {
//            // Nếu lớp học đã được đăng ký bởi giáo viên khác, hiển thị thông báo lỗi
//            Session::flash('error', 'Class already assigned to another lecturer.');
//        } else {
//            // Nếu số lượng lớp học đã vượt quá giới hạn, hiển thị thông báo lỗi
//            Session::flash('error', 'Something Gone Wrong.');
//        }

        return redirect()->route('transcript.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transcript $transcript, Request $request)
    {
        $obj = new Transcript();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->deleteTranscript();

            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Deleted Record');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Failed to delete the record. Please try again later.');
        }

        return redirect()->route('transcript.index');
    }
    public function isFinished(Request $request)
    {
        $divisionId = $request->division_id;
        $classId = Division::where('id', $divisionId)->value('class_id');
        $transcriptId = $request->id;
        // Kiểm tra số lượng sinh viên trong lớp
        $totalStudentsInClass = Student::where('class_id', $classId)->count();

        // Kiểm tra số lượng sinh viên đã nhận điểm trong transcript_detail
        $studentsWithScores = TranscriptDetail::where('transcript_id', $transcriptId)->count();

        // Nếu số lượng sinh viên với điểm bằng số lượng sinh viên trong lớp, transcript đã hoàn thành
        return $totalStudentsInClass > 0 && $totalStudentsInClass === $studentsWithScores;
    }
}
