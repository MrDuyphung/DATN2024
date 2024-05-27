@extends('layout.master')
@section('content')
    <form method="post" action="{{route('class.store')}}">
        <div class="card">
            <div class="card-body">

                <div class=""><h2 class="text-left mb-4">Create Class</h2></div>
                <table class="table table-striped " >

                    @csrf

                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label class="label">Class Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Class Name" name="class_name">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    Specialized<br> <select name="specializes_id"  class="input-group" >
                                        @foreach($specializes as $specialize)
                                            <option value="{{$specialize-> id}}">
                                                {{$specialize->specialized_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    School Year<br> <select name="school_year_id" class="input-group">
                                        @foreach($school_years as $school_year)
                                            <option value="{{$school_year-> id}}">
                                                ({{$school_year->sy_start}}-{{$school_year->sy_end}})_{{$school_year->sy_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

        <button class="btn btn-inverse-outline-success">Add</button>
    </form>
@endsection
