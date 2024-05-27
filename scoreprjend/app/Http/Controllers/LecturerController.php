<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Http\Requests\StoreLecturerRequest;
use App\Http\Requests\UpdateLecturerRequest;
use App\Models\Specialize;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $specializes = Specialize::all();
        $query = Lecturer::query()->join('specializes', 'lecturers.specializes_id', '=', 'specializes.id')
            ->select([
                'lecturers.*',
                'specializes.specialized_name AS specialized_name'
            ]);

        // Thêm điều kiện tìm kiếm nếu có tên giáo viên được nhập
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('lecturer_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('specialized_name', 'like', '%' . $searchTerm . '%');
        }

        // Lấy số lượng giáo viên trên mỗi trang từ request hoặc sử dụng giá trị mặc định (ví dụ: 10)
        $perPage = $request->input('per_page', 10);

        // Thực hiện phân trang và lấy danh sách giáo viên
        $lecturers = $query->paginate($perPage);

        // Truyền danh sách giáo viên và các thông tin phân trang vào view
        return view('lecturer.index', [
            'lecturers' => $lecturers,
            'perPage' => $perPage,
            'specializes' => $specializes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objSpecialize = new Specialize();
        $specialize = $objSpecialize->index();
        return view('lecturer.create', [
            'specializes' => $specialize
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLecturerRequest $request)
    {
        $password = Hash::make($request->input('password'));

        // Lưu mật khẩu đã được mã hóa vào database
        DB::table('lecturers')->insert([
            'lecturer_name' => $request->input('lecturer_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => $password,
            'specializes_id' => $request->input('specializes_id'),
        ]);
        return Redirect::route('lecturer.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lecturer $lecturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lecturer $lecturer, Request $request)
    {
        $objSpecialize = new Specialize();
        $specializes = $objSpecialize->index();
        $objLecturer = new Lecturer();
        $objLecturer->id = $request->id;
        $lecturers = $objLecturer->edit();
        return view('lecturer.edit', [
            'specializes' => $specializes,
            'lecturers' => $lecturers,
            'id' => $objLecturer->id
        ]);
    }

    public function showForgotPasswordForm()
    {
        return view('lecturer.forgot-password');
    }



    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::exists('lecturers')->where(function ($query) use ($request) {
                    return $query->where('phone', $request->phone);
                }),
            ],
            'phone' => 'required|exists:lecturers,phone',
        ]);

        // Generate a random password
        $newPassword = Str::random(8);

        // Update user's password in the database
        $user = Lecturer::where('email', $request->email)->first();
        $user->password = bcrypt($newPassword);
        $user->save();

        // Pass success message to the view
        return redirect()->route('lecturer.forgotPasswords')->with('success', 'Password reset successful. New password: ' . $newPassword);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLecturerRequest $request, $id)
    {
        // Mã hóa mật khẩu mới
        $password = Hash::make($request->input('password'));
        $email = $request->email;

        $emailexists = Lecturer::where('email', $email)
            ->where('id', '!=', $id)
            ->first();

        if($emailexists){

            Session::flash('error', 'Email already exists or information of lecturer not change.');
        }else {
            // Cập nhật thông tin sinh viên
            DB::table('lecturers')->where('id', $id)->update([
                'lecturer_name' => $request->input('lecturer_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => $password, // Lưu mật khẩu đã được mã hóa
                'specializes_id' => $request->input('specializes_id'),
            ]);
//            dd($request->all());
            Session::flash('success', 'Updated Record');
        }
        return redirect()->route('lecturer.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecturer $lecturer, Request $request)
    {
        $obj = new Lecturer();
        $obj->id = $request->id;

        try {
            // Thử xóa bản ghi
            $obj->deleteLecturer();
            // Nếu xóa thành công, hiển thị thông báo thành công
            Session::flash('success', 'Deleted Record');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi chung
            Session::flash('error', 'Failed to delete the record. Please try again later.');
        }
        return Redirect::route('lecturer.index');
    }

    public function login(){
        return view('lecturer.login');
    }

    public function loginProcess(Request $request){
        $accountLecturer = $request->only('email', 'password');
        if (Auth::guard('lecturer')->attempt($accountLecturer)){

            $lecturer = Auth::guard('lecturer')->user();
            Auth::login($lecturer);
            session(['lecturer' =>  $lecturer]);
            return Redirect::route('division.show');
        } else {
            return Redirect::back()->withInput();
        }
    }

    public function logout(){
        Auth::guard('lecturer')->logout();
        \Illuminate\Support\Facades\Session::forget('lecturer');

        return Redirect::route('lecturer.login');
    }

//    public function profile()
//    {
//        $lecturer = Auth::guard('lecturer')->user();
//        return view('lecturer.profile', compact('lecturer'));
//    }

//    public function updateProfile(Request $request)
//    {
//        $lecturer = Auth::guard('lecturer')->user();
//
//        // Validation logic for the update form here
//        $request->validate([
//            'lecturer_name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:lecturers,email,' . $lecturer->id,
//            'phone' => 'required|string|max:20',
//            'password' => 'nullable|string|min:6|confirmed',
//        ]);
//
//        // Update the lecturer's information
//        $lecturer->lecturer_name = $request->input('lecturer_name');
//        $lecturer->email = $request->input('email');
//        $lecturer->phone = $request->input('phone');
//
//        // Update password if it's provided
//        if ($request->input('password')) {
//            $lecturer->password = Hash::make($request->input('password'));
//        }
//
//        $lecturer->save();
//
//        return redirect()->route('lecturer.profile')->with('success', 'Profile updated successfully!');
//    }
}
