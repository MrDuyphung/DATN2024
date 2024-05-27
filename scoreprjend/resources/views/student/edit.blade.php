@extends('layout.master')
@section('content')
    <form method="post" action="{{route('student.update', $id)}}">
        @csrf
        @method('PUT')
        @foreach($students as $student)
            <div class="card">
                <div class="card-body">

                    <div class=""><h2 class="text-left mb-4">Edit Student</h2></div>
                    <table class="table table-striped " >
                        <div class="row w-80">
                            <div class="col-lg-10 mx-auto">
                                <div class="auto-form-wrapper">
                                    <div class="form-group">
                                        <label class="label">Student Name</label>
                                        <div class="input-group">
                                            <input type="text" name="student_name" class="form-control" placeholder="Student Name" value="{{$student->student_name}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Email</label>
                                        <div class="input-group">
                                            <input type="text" name="email" class="form-control" placeholder="Email" value="{{$student->email}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Phone</label>
                                        <div class="input-group">
                                            <input type="text" name="phone" class="form-control" placeholder="Phone Numbers" value="{{$student->phone}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Password</label>
                                        <div class="input-group">
                                             <input type="password" name="password" class="form-control" placeholder="Password" value="{{$student->password}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
            Class<br> <select name="class_id" class="input-group">
                @foreach($classes as $class)
                    <option value="{{$class-> id}}"
                    @if($class->id == $student->class_id)
                        {{'selected'}}
                        @endif
                    >
                        {{$class->class_name}}{{$class->sy_name}}
                    </option>
                @endforeach
            </select><br>
                                </div>



        @endforeach
        <button class="btn btn-outline-info">Update</button>
                                </div>
                            </div>
                        </div>
                    </table>
                </div>
            </div>
    </form>
@endsection

{{--@extends('layout.master')--}}
{{--@section('content')--}}
{{--    <form method="post" action="{{route('student.update', $student)}}">--}}
{{--        @csrf--}}
{{--        @method('PUT')--}}

{{--            Student Name: <input type="text" name="student_name" value="{{$student->student_name}}"><br>--}}
{{--            Email: <input type="text" name="email" value="{{$student->email}}"><br>--}}
{{--            Phone: <input type="text" name="phone" value="{{$student->phone}}"><br>--}}
{{--            Password: <input type="text" name="password" value="{{$student->password}}"><br>--}}
{{--            Class: <select name="class_id">--}}
{{--                @foreach($classes as $class)--}}
{{--                    <option value="{{$class-> id}}"--}}
{{--                    @if($class->id == $student->class_id)--}}
{{--                        {{'selected'}}--}}
{{--                        @endif--}}
{{--                    >--}}
{{--                        {{$class->class_name}}--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select><br>--}}
{{--        <button class="btn btn-outline-info">Update</button>--}}
{{--    </form>--}}
{{--@endsection--}}
