@extends('layout.master')
@section('content')
    <form method="post" action="{{route('subject.store')}}">
        <div class="card">
            <div class="card-body">

                <div class=""><h2 class="text-left mb-4">Create Subject</h2></div>
                <table class="table table-striped " >
                    @csrf

                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label class="label">Subject Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Subject Name" name="subject_name">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Duration</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Duration" name="duration">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Specialized</label >
                                    <div class="input-group">
                                         <select name="specializes_id" class="input-group">
                                            @foreach($specializes as $specialize)
                                                <option value="{{$specialize-> id}}">
                                                    {{$specialize->specialized_name}}
                                                </option>
                                            @endforeach
                                        </select><br>
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>

        <button class="btn btn-inverse-outline-success">Add</button>
    </form>
@endsection
