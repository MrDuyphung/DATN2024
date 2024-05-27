@extends('layout.master')
@section('content')
    <form method="post" action="">
        @method('PUT')
        @csrf
        @foreach($school_years as $school_year)
            <div class="card">
                <div class="card-body">

                    <div class=""><h2 class="text-left mb-4">Edit School Year</h2></div>
                    <table class="table table-striped " >
            <div class="row w-80">
                <div class="col-lg-10 mx-auto">
                    <div class="auto-form-wrapper">
                        <div class="form-group">
                            <label class="label">School Year Start</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="School Year Start" value="{{ $school_year->sy_start }}" name="sy_start">
                                <div class="input-group-append">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">School Year End</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="School Year End" value="{{ $school_year->sy_end }}" name="sy_end">
                                <div class="input-group-append">
                                </div>
                            </div>
                        </div>
{{--            School Year <input type="text " name ="sy_number" value="{{ $school_year->sy_number }}"><br>--}}
{{--            Name <input type="text " name ="sy_name" value="{{ $school_year->sy_name }}"><br>--}}
            <div class="form-group">
                <label class="label">School Year Name</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="School Year Name" value="{{ $school_year->sy_name }}" name="sy_name">
                    <div class="input-group-append">
                    </div>
                </div>
            </div>
        @endforeach
        <button class="btn btn-outline-info">Update</button>
    </form>
@endsection
