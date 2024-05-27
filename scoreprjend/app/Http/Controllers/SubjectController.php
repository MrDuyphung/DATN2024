<?php

namespace App\Http\Controllers;

use App\Models\Specialize;
use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::query()
            ->join('specializes', 'subjects.specializes_id', '=', 'specializes.id')
            ->select([
                'subjects.*',
                'specializes.specialized_name AS specialized_name'
            ]);

        // Thêm điều kiện tìm kiếm nếu có tên lớp học hoặc năm học được nhập
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('subject_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('specialized_name', 'like', '%' . $searchTerm . '%');
        }

        $subjects = $query->get();

        return view('subject.index', [
            'subjects' => $subjects
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $objSpecialize = new Specialize();
        $specialize = $objSpecialize->index();
        return view('subject.create', [
            'specializes' => $specialize
        ]);
//        $specializes = Specialize::all();
//        return view('subject.create', [
//            'specializes' =>$specializes
//        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $existingSubject = Subject::where('subject_name', $request->subject_name)
            ->exists();
        if ($existingSubject) {
            // Nếu specialized_name đã tồn tại, bạn có thể trả về một thông báo lỗi hoặc chuyển hướng người dùng về trang tương ứng.
            // Ví dụ:
            Session::flash('error', 'Record already exists.');
            return Redirect::route('subject.index');

        }
        $obj = new Subject();
        $obj->subject_name = $request->subject_name;
        $obj->duration = $request->duration;
        $obj->specializes_id = $request->specializes_id;
        $obj->store();
        Session::flash('success', 'Added New Record');
        return Redirect::route('subject.index');
//        Subject::create($request->all());
//        return Redirect::route('subject.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject, Request $request)
    {
        $objSpecialize = new Specialize();
        $specializes = $objSpecialize->index();
        $objSubject = new Subject();
        $objSubject->id = $request->id;
        $subjects = $objSubject->edit();
        return view('subject.edit', [
            'specializes' => $specializes,
            'subjects' => $subjects,
            'id' => $objSubject->id
        ]);
//        $specializes = Specialize::all();
//        return view('subject.edit', [
//            'subject' => $subject,
//            'specializes' => $specializes
//        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $existingSubject = Subject::where('subject_name', $request->subject_name)
            ->exists();
        if ($existingSubject) {
            // Nếu specialized_name đã tồn tại, bạn có thể trả về một thông báo lỗi hoặc chuyển hướng người dùng về trang tương ứng.
            // Ví dụ:
            Session::flash('error', 'Record already exists.');
            return Redirect::route('subject.index');

        }
        $obj = new Subject();
        $obj->id = $request->id;
        $obj->subject_name = $request->subject_name;
        $obj->duration = $request->duration;
        $obj->specializes_id = $request->specializes_id;
        $obj->updateSubject();
        Session::flash('success', 'Added New Record');
        return Redirect::route('subject.index');
//        $subject->update($request->all());
//        return Redirect::route('subject.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject, Request $request)
    {
        $obj = new Subject();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->deleteSubject();

            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Deleted Record');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Failed to delete the record. Please try again later.');
        }

        return Redirect::route('subject.index');
    }

//        $subject->delete();
//        return Redirect::route('subject.index');

}
