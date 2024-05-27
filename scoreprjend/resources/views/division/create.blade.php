@extends('layout.master')
@section('content')
    <form method="post" action="{{route('division.store')}}">
        <div class="card">
            <div class="card-body">

                <div class=""><h2 class="text-left mb-4">Create Division</h2></div>
                <table class="table table-striped " >

                    @csrf

                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
{{--                                <div class="form-group">--}}
{{--                                    <label class="label">Division Name</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <input type="text" class="form-control" placeholder="Division Name" name="division_name">--}}
{{--                                        <div class="input-group-append">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
        <div class="form-group">
            <label class="label">Semester</label>
            <div>
                <select name="semester" class="input-group">
                    <option value="0">Semester 1</option>
                    <option value="1">Semester 2</option>
                    <option value="2">Extra Semester</option>
                </select>
            </div>
        </div>
                                <div class="form-group">
                                    <label class="label">Class</label>
                                    <div>
                                        <select name="class_id" class="input-group">
                                            @foreach($classes as $class)
                                                <option value="{{$class-> id}}">
                                                    {{$class->class_name}}_{{$class->sy_name}}___({{$class->specialized_name}})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Lecturer</label>
                                    <div>
         <select name="lecturer_id" class="input-group">
            @foreach($lecturers as $lecturer)
                <option value="{{$lecturer-> id}}">
                    {{$lecturer->lecturer_name}}___({{$lecturer->specialized_name}})
                </option>
            @endforeach
        </select>
                                    </div>
                                </div>
{{--        Specialized: <select name="specializes_id" disabled>--}}
{{--            @foreach($specializes as $specialize)--}}
{{--                <option value="{{$specialize-> id}}"--}}
{{--                @foreach($lecturers as $lecturer)--}}
{{--                @if($specialize->id == $lecturer->specializes_id)--}}
{{--                    {{'selected'}}--}}
{{--                    @endif--}}


{{--                >--}}
{{--                    {{$specialize->specialized_name}}--}}

{{--                </option>--}}
{{--            @endforeach--}}
{{--                @endforeach--}}
{{--        </select><br>--}}
                                <div class="form-group">
                                    <label class="label">Subject</label>
                                    <div>
        <select name="subject_id" class="input-group">
            @foreach($subjects as $subject)
                <option value="{{$subject-> id }}">
                    {{$subject->subject_name}}___({{$subject->specialized_name}})
                </option>
            @endforeach
        </select>
                                    </div>
                                </div>


{{--        Admin: <select name="admin_id">--}}
{{--            @foreach($admins as $admin)--}}
{{--                <option value="{{$admin-> id}}">--}}
{{--                    {{$admin->username}}--}}
{{--                </option>--}}
{{--            @endforeach--}}
{{--        </select><br>--}}
        <button class="btn btn-outline-success">Add</button>
    </form>
@endsection
