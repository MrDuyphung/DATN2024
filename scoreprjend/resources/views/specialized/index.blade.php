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
    <div class="card">
        <h1 class="dropdown-item-title; d-flex justify-content-center" >Specialized Management</h1>
        <div class="card-body">
            <div class="mb-3">
    <form method="GET" action="{{ route('specialized.index') }}">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search by..." name="search">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
    <a class="btn btn-success btn-fw; menu-icon mdi mdi-creation" href="{{route('specialized.create')}}">Add</a>
                <table class="table table-striped " >
        <tr>
            <th>ID</th>
            <th>Specialized Name</th>
            <th>Lecturer</th>
            <th>Subject</th>
            <th>Class</th>
            <th>Total Students</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach($specializes as $specialize)
            <tr>
                <td>
                    {{$specialize->id}}
                </td>
                <td>
                    {{$specialize->specialized_name}}
                </td>
                <td >{{ $specialize->getNumberOfLecturer() }}</td>
                <td>{{ $specialize->getNumberOfSubject() }}</td>
                <td>{{ $specialize->getNumberOfClass() }}</td>
                <td>{{ $specialize->getNumberOfStudent() }}</td>

                <td>
                    <a class="btn btn-info" href="{{ route('specialized.checkClass', $specialize->id) }}">Check Class</a>
                </td>
                <td>
                    <a class="btn btn-inverse-primary" href="{{ route('specialized.edit', $specialize-> id) }}">Edit</a>
                </td>
                <td>
                    <form method="post" action="{{ route('specialized.destroy', $specialize->id) }}">
                        @method('DELETE')
                        @csrf
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
