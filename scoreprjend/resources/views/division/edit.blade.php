@extends('layout.master')
@section('content')
    <form method="post" action="{{route('division.update', $id)}}">
        @csrf
        @method('PUT')
        @foreach($divisions as $division)
            <div class="card">
                <div class="card-body">

                    <div class=""><h2 class="text-left mb-4">Edit Division</h2></div>
                    <table class="table table-striped " >
                        <div class="row w-80">
                            <div class="col-lg-10 mx-auto">
                                <div class="auto-form-wrapper">
{{--                                    <div class="form-group">--}}
{{--                                        <label class="label">Division Name</label>--}}
{{--                                        <div class="input-group">--}}
{{--                                            <input type="text" class="form-control" placeholder="Division Name" name="division_name" value="{{$division -> division_name}}">--}}
{{--                                            <div class="input-group-append">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="form-group">
                                        <label class="label">Semester</label>
                                        <div>
                                            <select name="semester" class="input-group">
                <option value="0" @if($division->semester == 0) {{ 'selected' }} @endif>Semester 1</option>
                <option value="1" @if($division->semester == 1) {{ 'selected' }} @endif>Semester 2</option>
                <option value="2" @if($division->semester == 2) {{ 'selected' }} @endif>Extra Semester</option>
            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Lecturer</label>
                                        <div>
                                            <select name="class_id" class="input-group"  disabled>
                                                @foreach($classes as $class)
                                                    <option value="{{$class-> id}}"
                                                    @if($class->id == $division->class_id)
                                                        {{'selected'}}
                                                        @endif
                                                    >
                                                        {{$class->class_name}}___({{$class->specialized_name}})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Lecturer</label>
                                        <div>
                                            <select name="lecturer_id" class="input-group"  disabled>
                @foreach($lecturers as $lecturer)
                    <option value="{{$lecturer-> id}}"
                    @if($lecturer->id == $division->lecturer_id)
                        {{'selected'}}
                        @endif
                    >
                        {{$lecturer->lecturer_name}}___({{$lecturer->specialized_name}})
                    </option>
                @endforeach
            </select>
                                        </div>
                                    </div>
{{--            Specialized: <select name="specializes_id" >--}}
{{--                @foreach($specializes as $specialize)--}}
{{--                    <option value="{{$specialize-> id}}"--}}
{{--                    @foreach($lecturers as $lecturer)--}}
{{--                        @if($specialize->id == $lecturer->specializes_id)--}}
{{--                            {{'selected'}}--}}
{{--                            @endif--}}


{{--                        >--}}
{{--                        {{$specialize->specialized_name}}--}}

{{--                    </option>--}}
{{--                @endforeach--}}
{{--                @endforeach--}}
{{--            </select><br>--}}
                                    <div class="form-group">
                                        <label class="label">Subject</label>
                                        <div>
                                            <select name="subject_id" class="input-group" disabled >
                @foreach($subjects as $subject)
                    <option value="{{$subject-> id}}"
                    @if($subject->id == $division->subject_id)
                        {{'selected'}}
                        @endif
                    >
                        {{$subject->subject_name}}___({{$subject->specialized_name}})
                    </option>
                @endforeach
            </select>
                                        </div>
                                    </div>



        @endforeach
        <button class="btn btn-outline-info">Update</button>
    </form>

@endsection
