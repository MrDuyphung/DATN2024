@extends('layout.master')
@section('content')
    <form method="post" action="{{route('student.store')}}">
        <div class="card">
            <div class="card-body">

                <div class=""><h2 class="text-left mb-4">Create Student</h2></div>
                <table class="table table-striped " >

                    @csrf

                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label class="label">Student Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Student Name" name="student_name">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Email</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Email" name="email">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Phone</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Phone Numbers" name="phone">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" placeholder="Password" name="password">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>  <div class="form-group">
        Class<br> <select name="class_id" class="input-group">
            @foreach($classes as $class)
                <option value="{{$class-> id}}">
                    {{$class->class_name}}_{{$class->sy_name}}
                </option>
            @endforeach
        </select><br> </div>


        <button class="btn btn-inverse-outline-success">Add</button>
    </form>
@endsection

{{--@extends('layout.master')--}}
{{--@section('content')--}}
{{--    <form method="post" action="{{route('student.store')}}">--}}
{{--        @csrf--}}
{{--        Student Name: <input type="text" name="student_name"><br>--}}
{{--        Email: <input type="text" name="email"><br>--}}
{{--        Phone: <input type="text" name="phone"><br>--}}
{{--        Password: <input type="text" name="password"><br>--}}
{{--        Class: <select name="class_id">--}}
{{--            @foreach($classes as $class)--}}
{{--                <option value="{{$class-> id}}">--}}
{{--                    {{$class->class_name}}--}}
{{--                </option>--}}
{{--            @endforeach--}}
{{--        </select><br>--}}
{{--        <button class="btn btn-outline-success">Add</button>--}}
{{--    </form>--}}
{{--@endsection--}}
