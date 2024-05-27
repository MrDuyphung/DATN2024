<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Models\Classes;
use App\Models\Student;
use App\Models\Report;
use App\Models\Lecturer;
use App\Models\Division;
use App\Models\TranscriptDetail;
Route::middleware(['adminMiddleware'])->get('/', function () {
    $studentCount = Student::count();
//    $reportCount = Report::where('status','=', 0)->count();
    $lecturerCount = Lecturer::count();
    $divisionCount = Division::count();
    $re = null;


    $transcriptBelow5Count = TranscriptDetail::where('score', '<', 5)
        ->whereHas('transcript', function ($query) {
            $query->where('exam_times', 0);
        })
        ->count();

    $transcriptAbove5Count = TranscriptDetail::where('score', '>=', 5)
        ->whereHas('transcript', function ($query) {
            $query->where('exam_times', 0);
        })
        ->count();

    $transcriptNoScoreCount1 = TranscriptDetail::whereNull('score')
        ->where(function ($query) {
            $query->where('note', 2);
        })
        ->whereHas('transcript', function ($query) {
            $query->where('exam_times', 0);
        })
        ->count();
    $transcriptNoScoreCount2 = TranscriptDetail::whereNull('score')
        ->where(function ($query) {
            $query->where('note', 3);
        })
        ->whereHas('transcript', function ($query) {
            $query->where('exam_times', 0);
        })
        ->count();

    // Số lớp có sinh viên
    $classWithStudentsCount = Classes::has('student')->count();

    // Số lớp không có sinh viên
    $classWithoutStudentsCount = Classes::doesntHave('student')->count();


    return view('dashboard', [
        'studentCount' => $studentCount,
//        'reportCount' => $reportCount,
        'lecturerCount' => $lecturerCount,
        'divisionCount' => $divisionCount,
        'classWithStudentsCount' => $classWithStudentsCount,
        'classWithoutStudentsCount' => $classWithoutStudentsCount,
        'transcriptBelow5Count' => $transcriptBelow5Count,
        'transcriptAbove5Count' => $transcriptAbove5Count,
        'transcriptNoScoreCount1' => $transcriptNoScoreCount1,
        'transcriptNoScoreCount2' => $transcriptNoScoreCount2,
        'result' => $re,
        'abf'=> null,
        'abe' => null,
        'abd' => null,
        'abc'=> null
    ]);
});


Route::get('/login-admin', [\App\Http\Controllers\AdminController::class, 'login'])-> name('admin.login');
Route::post('/login-admin', [\App\Http\Controllers\AdminController::class, 'loginProcess'])-> name('admin.loginProcess');
Route::get('/logout-admin', [\App\Http\Controllers\AdminController::class, 'logout'])-> name('admin.logout');
Route::put('/query', [\App\Http\Controllers\TranscriptDetailController::class, 'query'])->name('query');


Route::get('/login-lecturer', [\App\Http\Controllers\LecturerController::class, 'login'])-> name('lecturer.login');
Route::post('/login-lecturer', [\App\Http\Controllers\LecturerController::class, 'loginProcess'])-> name('lecturer.loginProcess');
Route::get('/logout-lecturer', [\App\Http\Controllers\LecturerController::class, 'logout'])-> name('lecturer.logout');

Route::get('/login-student', [\App\Http\Controllers\StudentController::class, 'login'])-> name('student.login');
Route::post('/login-student', [\App\Http\Controllers\StudentController::class, 'loginProcess'])-> name('student.loginProcess');
Route::get('/logout-student', [\App\Http\Controllers\StudentController::class, 'logout'])-> name('student.logout');

Route::get('/forgot-password', [\App\Http\Controllers\StudentController::class, 'showForgotPasswordForm'])->name('student.forgotPassword');
Route::post('/forgot-password', [\App\Http\Controllers\StudentController::class, 'sendResetLinkEmail'])->name('student.sendResetLinkEmail');

Route::get('/forgot-passwords', [\App\Http\Controllers\LecturerController::class, 'showForgotPasswordForm'])->name('lecturer.forgotPasswords');
Route::post('/forgot-passwords', [\App\Http\Controllers\LecturerController::class, 'sendResetLinkEmail'])->name('lecturer.sendResetLinkEmails');

//Route::get('/demo',[\App\Http\Controllers\controllerprj::class,'index'])->name('demo');
//Route::get('/demo/login',[\App\Http\Controllers\controllerprj::class, 'login'])->name('login');
//Class Route
Route::middleware(['adminMiddleware'])->prefix('/sy')->group( function (){
    Route::get('/',[\App\Http\Controllers\SchoolYearController::class,'index'])->name('sy.index');

    Route::get('/create', [\App\Http\Controllers\SchoolYearController::class, 'create'])->name('sy.create');
    Route::post('/create', [\App\Http\Controllers\SchoolYearController::class, 'store'])->name('sy.store');

    Route::get('/{id}/edit', [\App\Http\Controllers\SchoolYearController::class, 'edit'])->name('sy.edit');
    Route::put('/{id}/edit', [\App\Http\Controllers\SchoolYearController::class, 'update'])->name('sy.update');
    Route::delete('/{id}', [\App\Http\Controllers\SchoolYearController::class, 'destroy'])->name('sy.destroy');
});

Route::middleware(['adminMiddleware'])->prefix('/specialized')->group( function (){
    Route::get('/',[\App\Http\Controllers\SpecializeController::class,'index'])->name('specialized.index');
    Route::get('/{id}/check-class', [\App\Http\Controllers\SpecializeController::class,'checkClass'])->name('specialized.checkClass');
    Route::get('/check-students/{class_id}', [\App\Http\Controllers\SpecializeController::class,'checkStudentFromClass'])->name('specialized.checkStudent');

    Route::get('/create', [\App\Http\Controllers\SpecializeController::class, 'create'])->name('specialized.create');
    Route::post('/create', [\App\Http\Controllers\SpecializeController::class, 'store'])->name('specialized.store');

    Route::get('/{id}/edit', [\App\Http\Controllers\SpecializeController::class, 'edit'])->name('specialized.edit');
    Route::put('/{id}/edit', [\App\Http\Controllers\SpecializeController::class, 'update'])->name('specialized.update');
    Route::delete('/{id}', [\App\Http\Controllers\SpecializeController::class, 'destroy'])->name('specialized.destroy');
});

Route::middleware(['adminMiddleware'])->prefix('/student')->group( function (){
    Route::get('/', [ \App\Http\Controllers\StudentController::class, 'index'])->name('student.index');
    Route::get('/create',[\App\Http\Controllers\StudentController::class, 'create'])->name('student.create');
    Route::post('/create',[\App\Http\Controllers\StudentController::class, 'store'])->name('student.store');
    Route::get('/{id}/edit',[\App\Http\Controllers\StudentController::class, 'edit'])->name('student.edit');
    Route::put('/{id}/edit',[\App\Http\Controllers\StudentController::class, 'update'])->name('student.update');
    Route::delete('/{id}',[\App\Http\Controllers\StudentController::class, 'destroy'])->name('student.destroy');
});

Route::middleware(['adminMiddleware'])->prefix('/subject')->group(function (){

    Route::get('/', [\App\Http\Controllers\SubjectController::class, 'index'])->name('subject.index');
    Route::get('/create', [\App\Http\Controllers\SubjectController::class, 'create'])->name('subject.create');
    Route::post('/create', [\App\Http\Controllers\SubjectController::class, 'store'])->name('subject.store');
    Route::get('/{id}/edit', [\App\Http\Controllers\SubjectController::class, 'edit'])->name('subject.edit');
    Route::put('/{id}/edit', [\App\Http\Controllers\SubjectController::class, 'update'])->name('subject.update');
    Route::delete('/{id}', [\App\Http\Controllers\SubjectController::class, 'destroy'])->name('subject.destroy');
});

Route::middleware(['adminMiddleware'])->prefix('/class')->group(function (){

    Route::get('/', [\App\Http\Controllers\ClassesController::class, 'index'])->name('class.index');
    Route::get('/create', [\App\Http\Controllers\ClassesController::class, 'create'])->name('class.create');
    Route::post('/create', [\App\Http\Controllers\ClassesController::class, 'store'])->name('class.store');
    Route::get('/{id}/edit', [\App\Http\Controllers\ClassesController::class, 'edit'])->name('class.edit');
    Route::put('/{id}/edit', [\App\Http\Controllers\ClassesController::class, 'update'])->name('class.update');
    Route::delete('/{id}', [\App\Http\Controllers\ClassesController::class, 'destroy'])->name('class.destroy');
});

Route::middleware(['adminMiddleware'])->prefix('/lecturer')->group(function (){

    Route::get('/', [\App\Http\Controllers\LecturerController::class, 'index'])->name('lecturer.index');
    Route::get('/create', [\App\Http\Controllers\LecturerController::class, 'create'])->name('lecturer.create');
    Route::post('/create', [\App\Http\Controllers\LecturerController::class, 'store'])->name('lecturer.store');
    Route::get('/{id}/edit', [\App\Http\Controllers\LecturerController::class, 'edit'])->name('lecturer.edit');
    Route::put('/{id}/edit', [\App\Http\Controllers\LecturerController::class, 'update'])->name('lecturer.update');
    Route::delete('/{id}', [\App\Http\Controllers\LecturerController::class, 'destroy'])->name('lecturer.destroy');
});

Route::middleware(['lecturerMiddleware'])->prefix('/transcript')->group(function (){

    Route::get('/', [\App\Http\Controllers\TranscriptController::class, 'index'])->name('transcript.index');
    Route::get('/create/{division_id}', [\App\Http\Controllers\TranscriptController::class, 'create'])->name('transcript.create');
    Route::post('/create', [\App\Http\Controllers\TranscriptController::class, 'store'])->name('transcript.store');
    Route::get('/{id}/edit', [\App\Http\Controllers\TranscriptController::class, 'edit'])->name('transcript.edit');
    Route::put('/{id}/edit', [\App\Http\Controllers\TranscriptController::class, 'update'])->name('transcript.update');
    Route::delete('/{id}', [\App\Http\Controllers\TranscriptController::class, 'destroy'])->name('transcript.destroy');
});

Route::middleware(['adminMiddleware'])->prefix('/division')->group(function (){

    Route::get('/', [\App\Http\Controllers\DivisionController::class, 'index'])->name('division.index');

    Route::get('/create', [\App\Http\Controllers\DivisionController::class, 'create'])->name('division.create');
    Route::post('/create', [\App\Http\Controllers\DivisionController::class, 'store'])->name('division.store');
    Route::get('/{id}/edit', [\App\Http\Controllers\DivisionController::class, 'edit'])->name('division.edit');
    Route::put('/{id}/edit', [\App\Http\Controllers\DivisionController::class, 'update'])->name('division.update');
    Route::delete('/{id}', [\App\Http\Controllers\DivisionController::class, 'destroy'])->name('division.destroy');
    Route::get('/{id}/check', [\App\Http\Controllers\DivisionController::class, 'checkTranscript'])->name('division.checkTranscript');
    Route::get('/check-students-point/{transcript_id}', [\App\Http\Controllers\DivisionController::class, 'checkTransDetail'])->name('division.checkTransDetail');

});

Route::middleware(['lecturerMiddleware'])->prefix('/divisions')->group(function (){
    Route::get('/show', [\App\Http\Controllers\DivisionController::class, 'show'])->name('division.show');

});

Route::middleware(['studentMiddleware'])->prefix('/transdetails')->group(function (){
    Route::get('/show', [\App\Http\Controllers\TranscriptDetailController::class, 'show'])->name('transdetail.show');
});


Route::middleware(['studentMiddleware'])->prefix('/report')->group(function (){
    Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('report.index');
    Route::get('/create', [\App\Http\Controllers\ReportController::class, 'showReportForm'])->name('report.create');
    Route::post('/create', [\App\Http\Controllers\ReportController::class, 'submitReport'])->name('report.store');
});

Route::middleware(['studentMiddleware'])->prefix('/reexamines')->group(function (){
    Route::get('/show', [\App\Http\Controllers\ReexamineController::class, 'show'])->name('reexamine.show');
});

Route::middleware(['lecturerMiddleware'])->prefix('/reexamine')->group(function (){
    Route::get('/', [\App\Http\Controllers\ReexamineController::class, 'index'])->name('reexamine.index');
    Route::get('/create', [\App\Http\Controllers\ReexamineController::class, 'create'])->name('reexamine.create');
    Route::post('/create', [\App\Http\Controllers\ReexamineController::class, 'store'])->name('reexamine.store');
    Route::get('/{report_id}/edit', [\App\Http\Controllers\ReexamineController::class, 'edit'])->name('reexamine.edit');
    Route::put('/{report_id}/edit', [\App\Http\Controllers\ReexamineController::class, 'update'])->name('reexamine.update');

});

Route::middleware(['adminMiddleware'])->prefix('/reports')->group(function (){
    Route::get('/receive', [\App\Http\Controllers\ReportController::class, 'show'])->name('report.receive');
    Route::put('/update-report/{id}', [\App\Http\Controllers\ReportController::class, 'updateReport'])->name('report.update');});

Route::middleware(['lecturerMiddleware'])->prefix('/reportss')->group(function () {
    Route::get('/received', [\App\Http\Controllers\ReportController::class, 'showLecturer'])->name('report.received');
});

    //Route::middleware('lecturer')->group(function () {
//    Route::get('/profile', [\App\Http\Controllers\LecturerController::class, 'profile'])->name('lecturer.profile');
//    Route::put('/profile/update',[\App\Http\Controllers\LecturerController::class, 'updateProfile'])->name('lecturer.profile.update');
//});

//Route::middleware(['lecturer'])->group(function () {
//    Route::get('/profile', [\App\Http\Controllers\LecturerController::class, 'profile'])->name('lecturer.profile');
//});

Route::middleware(['lecturerMiddleware'])->prefix('/transdetail')->group(function (){

    Route::get('/{transcript_id}', [\App\Http\Controllers\TranscriptDetailController::class, 'index'])->name('transdetail.index');
//    Route::get('/search', [\App\Http\Controllers\TranscriptDetailController::class, 'search'])->name('transdetail.search');
    Route::get('/create/{transcript_id}', [\App\Http\Controllers\TranscriptDetailController::class, 'create'])->name('transdetail.create');
    Route::post('/create', [\App\Http\Controllers\TranscriptDetailController::class, 'store'])->name('transdetail.store');
    Route::get('/created/{transcript_id}', [\App\Http\Controllers\TranscriptDetailController::class, 'created'])->name('transdetail.created');
    Route::post('/created', [\App\Http\Controllers\TranscriptDetailController::class, 'stored'])->name('transdetail.stored');

    Route::get('/{id}/edit', [\App\Http\Controllers\TranscriptDetailController::class, 'edit'])->name('transdetail.edit');
    Route::put('/{id}/edit', [\App\Http\Controllers\TranscriptDetailController::class, 'update'])->name('transdetail.update');
    Route::delete('/{id}', [\App\Http\Controllers\TranscriptDetailController::class, 'destroy'])->name('transdetail.destroy');
});



Route::prefix('/admin')->group(function (){

    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    Route::get('/create', [\App\Http\Controllers\AdminController::class, 'create'])->name('admin.create');
    Route::post('/create', [\App\Http\Controllers\AdminController::class, 'store'])->name('admin.store');
    Route::get('/{id}/edit', [\App\Http\Controllers\AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/{id}/edit', [\App\Http\Controllers\AdminController::class, 'update'])->name('admin.update');
    Route::delete('/{id}', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.destroy');
});
