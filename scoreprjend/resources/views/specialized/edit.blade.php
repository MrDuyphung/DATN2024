@extends('layout.master')
@section('content')
    <form method="post" action="">
        @method('PUT')
        @csrf
        @foreach($specializes as $specialize)
            <div class="card">
                <div class="card-body">

                    <div class=""><h2 class="text-left mb-4">Edit Specialized</h2></div>
                    <table class="table table-striped " >
                        <div class="row w-80">
                            <div class="col-lg-10 mx-auto">
                                <div class="auto-form-wrapper">
                                    <div class="form-group">
                                        <label class="label">Specialized Name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Specialized Name" value="{{ $specialize->specialized_name }}" name="specialized_name">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>

        @endforeach
        <button class="btn btn-outline-info ">Update</button>
    </form>
@endsection
