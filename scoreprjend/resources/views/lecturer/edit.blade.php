@extends('layout.master')
@section('content')
    <form method="post" action="{{route('lecturer.update', $id)}}">
        @csrf
        @method('PUT')
        @foreach($lecturers as $lecturer)
            <div class="card">
                <div class="card-body">

                    <div class=""><h2 class="text-left mb-4">Edit Lecturer</h2></div>
                    <table class="table table-striped " >
                        <div class="row w-80">
                            <div class="col-lg-10 mx-auto">
                                <div class="auto-form-wrapper">
                                    <div class="form-group">
                                        <label class="label">Lecturer Name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Lecturer Name" value="{{$lecturer->lecturer_name}}" name="lecturer_name">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Email</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="email" placeholder="Email" value="{{$lecturer->email}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Phone</label>
                                        <div class="input-group">
                                            <input type="text" name="phone" class="form-control" placeholder="Phone Numbers" value="{{$lecturer->phone}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" placeholder="Password" value="{{$lecturer->password}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
            Specialized<br> <select name="specializes_id" class="input-group">
                @foreach($specializes as $specialize)
                    <option value="{{$specialize-> id}}"
                    @if($specialize->id == $lecturer->specializes_id)
                        {{'selected'}}
                        @endif
                    >
                        {{$specialize->specialized_name}}
                    </option>
                @endforeach
            </select><br>
                                </div>
        @endforeach
        <button class="btn btn-outline-info">Update</button>
    </form>
@endsection
