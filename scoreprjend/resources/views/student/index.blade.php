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
            <h1 class="dropdown-item-title; d-flex justify-content-center" >Student Management</h1>
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" action="{{ route('student.index') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by name, email, class ..." name="search">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>

            <div class="mb-3">
                <label for="per_page" class="form-label">Records per page:</label>
                <select class="form-select" id="per_page" name="per_page">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>

            <!-- Danh sách sinh viên -->
            <table class="table table-striped">
                <!-- Hiển thị thông tin sinh viên -->

                <!-- Hiển thị nút phân trang -->
                <div class="d-flex justify-content-center">
                    {{ $students->links() }}
                </div>
            </table>
    <a class="btn btn-success btn-fw; menu-icon mdi mdi-creation" href="{{ route('student.create') }}" >Add a student</a>
            <table class="table table-striped " >
        <tr>
            <th>ID Student</th>
            <th>Student Name</th>
            <th>Class</th>
            <th>Email</th>
            <th>Phone</th>
{{--            <th>Password</th>--}}

            <th></th>
            <th></th>
        </tr>
        @foreach($students as $student)
            <tr>
                <td>
                    {{$student->id}}
                </td>
                <td>
                    {{$student->student_name}}
                </td>
                <td>
                    {{$student->class_name}}{{$student->sy_name}}
                </td>
                <td>
                    {{$student->email}}
                </td>
                <td>
                    {{$student->phone}}
                </td>
{{--                <td>--}}
{{--                    {{$student->password}}--}}
{{--                </td>--}}
                <td>
                    <a href="{{route('student.edit', $student->id)}}" class="btn btn-primary btn-fw">Edit</a>
                </td>
                <td>
                    <form method="post" action="{{route('student.destroy', $student->id)}}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-fw"  type="submit" onclick="return confirmDelete();">Delete</button>
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

            <!-- Danh sách sinh viên -->
            <table class="table table-striped">
                <!-- Hiển thị thông tin sinh viên -->

                <!-- Hiển thị nút phân trang -->
                <div class="d-flex justify-content-center">
                    {{ $students->links() }}
                </div>
@endsection

{{--@extends('layout.master')--}}
{{--@section('content')--}}
{{--    <a href="{{ route('student.create') }}" class="btn btn-success btn-fw">Add a student</a>--}}
{{--    <table border="1px" cellspacing="0" cellpadding="0" width="100%" >--}}
{{--        <tr>--}}
{{--            <th>ID Student</th>--}}
{{--            <th>Student Name</th>--}}
{{--            <th>Email</th>--}}
{{--            <th>Phone</th>--}}
{{--            <th>Password</th>--}}
{{--            <th>Class</th>--}}
{{--            <th>Configure</th>--}}
{{--            <th>Destroy</th>--}}
{{--        </tr>--}}
{{--        @foreach($students as $student)--}}
{{--            <tr>--}}
{{--                <td>--}}
{{--                    {{$student->id}}--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    {{$student->student_name}}--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    {{$student->email}}--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    {{$student->phone}}--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    {{$student->password}}--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    {{$student->class->class_name}}--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    <a href="{{route('student.edit', $student)}}" class="btn btn-primary btn-fw">Edit</a>--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    <form method="post" action="{{route('student.destroy', $student)}}">--}}
{{--                        @csrf--}}
{{--                        @method('DELETE')--}}
{{--                        <button class="btn btn-danger btn-fw">Delete</button>--}}
{{--                    </form>--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}
{{--    </table>--}}
{{--@endsection--}}
