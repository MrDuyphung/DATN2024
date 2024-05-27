<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\Specialize;
//use App\Http\Requests\StoreSpecializeRequest;
//use App\Http\Requests\UpdateSpecializeRequest;
//use Illuminate\Support\Facades\Redirect;
//
//class SpecializeController extends Controller
//{
//    /**
//     * Display a listing of the resource.
//     */
//    public function index()
//    {
//        $specializes = Specialize::all();
//        return view('specialized.index', [
//            'specializes' => $specializes
//        ]);
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     */
//    public function create()
//    {
//        return view('specialized.create');
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     */
//    public function store(StoreSpecializeRequest $request)
//    {
//        $data = $request->all();
//        Specialize::create($data);
//        return Redirect::route('specialized.index');
//    }
//
//    /**
//     * Display the specified resource.
//     */
//    public function show(Specialize $specialize)
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     */
//    public function edit(Specialize $specialize)
//    {
//        return view('specialized.edit', [
//            'specialize' => $specialize
//        ]);
//    }
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(UpdateSpecializeRequest $request, Specialize $specialize)
//    {
//        $data = $request->all();
//        $specialize->update($data);
//        return Redirect::route('specialized.index');
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(Specialize $specialize)
//    {
//        $specialize->delete();
//        return Redirect::route('specialized.index');
//    }
//}


namespace App\Http\Controllers;

use App\Http\Requests\StoreSpecializeRequest;
use App\Http\Requests\UpdateSpecializeRequest;
use App\Models\Classes;
use App\Http\Requests\StoreClassesRequest;
use App\Http\Requests\UpdateClassesRequest;
use App\Models\Lecturer;
use App\Models\Specialize;
use App\Models\Student;
use App\Models\Subject;
use http\Env\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class SpecializeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $searchTerm = $request->input('search');

        $specializes = Specialize::when($searchTerm, function ($query, $searchTerm) {
            return $query->search($searchTerm);
        })->get();

        // Eager load the 'students' relationship to avoid N+1 query issue
        $specializes->load('students');

        return view('specialized.index', [
            'specializes' => $specializes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('specialized.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpecializeRequest $request)
    {
        $existingSpecialize = Specialize::where('specialized_name', $request->specialized_name)->exists();

        if ($existingSpecialize) {
            // Nếu specialized_name đã tồn tại, bạn có thể trả về một thông báo lỗi hoặc chuyển hướng người dùng về trang tương ứng.
            // Ví dụ:
            Session::flash('error', 'Record already exists.');
            return Redirect::route('specialized.index');

        }

        // Nếu specialized_name không tồn tại, bạn có thể tiếp tục lưu dữ liệu vào database.
        $obj = new Specialize();
        $obj->specialized_name = $request->specialized_name;
        $obj->store();

        Session::flash('success', 'Added New Record');
        return Redirect::route('specialized.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialize $specializes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     */
    public function edit(Specialize $specializes, \Illuminate\Http\Request $request)
    {

        $obj = new Specialize();
        $obj->id = $request->id;

        $specializes = $obj->edit();

        return view('specialized.edit', [
            'specializes' => $specializes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpecializeRequest $request, Specialize $specializes)
    {
        $existingSpecialize = Specialize::where('specialized_name', $request->specialized_name)->exists();

        if ($existingSpecialize) {
            // Nếu specialized_name đã tồn tại, bạn có thể trả về một thông báo lỗi hoặc chuyển hướng người dùng về trang tương ứng.
            // Ví dụ:
            Session::flash('error', 'Record already exists.');
            return Redirect::route('specialized.index');

        }
        $obj = new Specialize();
        $obj->id = $request->id;
        $obj->specialized_name = $request->specialized_name;
        $obj->updateSpecialize();
        Session::flash('success', 'Added New Record');
        return Redirect::route('specialized.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialize $specializes, \Illuminate\Http\Request $request)
    {
        $obj = new Specialize();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->destroySpecialize();

            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Deleted Record');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Failed to delete the record. Please try again later.');
        }
        return Redirect::route('specialized.index');
    }

    public function checkClass($id)
    {
        // Lấy thông tin specialized dựa trên ID
        $specialized = Specialize::findOrFail($id);

        // Lấy danh sách lớp học thuộc specialized đó
        $classes = Classes::where('specializes_id', $id)->get();

        // Lấy danh sách sinh viên thuộc các lớp đó
        $students = Student::whereIn('class_id', $classes->pluck('id'))->get();

        return view('specialized.checkClass', [
            'specialized' => $specialized,
            'classes' => $classes,
            'students' => $students,
        ]);
    }

    public function checkStudentFromClass($class_id)
    {
        // Get the class based on the class_id
        $class = Classes::findOrFail($class_id);

        // Get the students for the specific class
        $students = Student::where('class_id', $class_id)->get();

        return view('specialized.checkStudent', [
            'class' => $class,
            'students' => $students,
        ]);
    }


}
