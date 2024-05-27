@extends('layout.master')
@section('content')
    <form method="post" action="{{route('subject.update', $id)}}">
        @csrf
        @method('PUT')
        @foreach($subjects as $subject)
            <div class="card">
                <div class="card-body">

                    <div class=""><h2 class="text-left mb-4">Edit Subject</h2></div>
                    <table class="table table-striped " >
                        <div class="row w-80">
                            <div class="col-lg-10 mx-auto">
                                <div class="auto-form-wrapper">
                                    <div class="form-group">
                                        <label class="label">Subject Name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Subject Name" value="{{$subject->subject_name}}" name="subject_name">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Duration</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Duration" value="{{$subject->duration}}" name="duration">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Specialized</label><br>
                                         <select name="specializes_id" class="input-group">
                                            @foreach($specializes as $specialize)
                                                <option value="{{$specialize -> id}}"
                                                @if($specialize->id == $subject->specializes_id)
                                                    {{'selected'}}
                                                    @endif
                                                >
                                                    {{$specialize->specialized_name}}
                                                </option>
                                            @endforeach
                                        </select><br>
                                    </div>


        @endforeach
        <button>Update</button>
    </form>
@endsection
