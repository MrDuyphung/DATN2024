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
                <h1 class="card-title">School Year</h1>
                <form method="GET" action="{{ route('sy.index') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by name, numbers..." name="search">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
                <a class="btn btn-success btn-fw; menu-icon mdi mdi-creation  " href="{{route('sy.create')}}">Add</a>
                    <table class="table table-striped " >

        <tr>
            <th>ID</th>
            <th>School Year</th>
            <th></th>
            <th></th>
        </tr>
        @foreach($school_years as $school_year)
            <tr>
                <td>
                    {{$school_year->sy_name}}
                </td>
                <td class="text-danger">
                    {{$school_year->sy_start}}-{{$school_year->sy_end}}
                </td>

                <td>
                    <a  class="btn btn-inverse-primary" href="{{ route('sy.edit', $school_year->id) }} ">Edit</a>
                </td>
                <td>
                    <form method="post" action="{{ route('sy.destroy', $school_year->id) }}">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-inverse-danger"  type="submit" onclick="return confirmDelete();">Delete</button>
                    </form>
                    <script>
                        function confirmDelete() {
                            // Sử dụng hộp thoại xác nhận
                            return confirm('Do you want to delete??');
                        }
                    </script>
                </td>
            </tr>
        @endforeach
    </table>


@endsection
