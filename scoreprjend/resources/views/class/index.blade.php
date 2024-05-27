@extends('layout.master')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger" id="myText">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" id="myText">
            {{ session('success') }}
        </div>
    @endif
    <div class="col-lg-12 grid-margin stretch-card">


        <div class="card">

            <div class="card-body">

                <div class="mb-3">
                    <h1 class="card-title">Class</h1>
    <div class="card-body">
        <div class="mb-3">
            <form method="GET" action="{{ route('class.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search by name, id school year, ..." name="search">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    <a class="btn btn-success btn-fw; menu-icon mdi mdi-creation" href="{{ route('class.create') }}" class="btn btn-light">Add a class</a>
        <table class="table table-striped " >
        <tr>
            <th>ID Class</th>
            <th>Class Name</th>
            <th>Students</th>
            <th>Specialized</th>
            <th>School Year</th>
            <th></th>
            <th></th>
        </tr>
        @foreach($classes as $class)
            <tr>
                <td>
                    {{$class->id}}
                </td>
                <td>
                    {{$class->class_name}}_{{$class->sy_name}}
                </td>
                <td>
                    {{ $class->getNumberOfStudents() }}
                </td>
                <td>
                    {{$class->specialized_name}}
                </td>
                <td>
                    {{$class->sy_start}}-{{$class->sy_end}}
                </td>
                <td>
                    <a class="btn btn-inverse-primary" href="{{route('class.edit', $class->id)}}" class="btn btn-app">Edit</a>
                </td>
                <td>
                    <form method="post" action="{{route('class.destroy', $class->id)}}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-inverse-danger" type="submit" onclick="return confirmDelete();">Delete</button>
                    </form>
                    <script>
                        function confirmDelete() {
                            // Sử dụng hộp thoại xác nhận
                            return confirm('Do you want to delete?');
                        }
                    </script>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
