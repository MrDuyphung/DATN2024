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
                    <h1 class="card-title">Lecturer</h1>
    <div class="card-body">
        <div class="mb-3">
            <form method="GET" action="{{ route('lecturer.index') }}">
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

            <!-- Hiển thị thông tin sinh viên -->

            <!-- Hiển thị nút phân trang -->
            <div class="d-flex justify-content-lg-end">
                {{ $lecturers->links() }}
            </div>
        </table>
    <a class="btn btn-success btn-fw; menu-icon mdi mdi-creation" href="{{ route('lecturer.create') }}" >Add a lecturer</a>
        <table class="table table-striped">
        <tr>
            <th>ID Lecturer</th>
            <th>Lecturer Name</th>
            <th>Email</th>
            <th>Phone</th>
{{--            <th>Password</th>--}}
            <th>Specialized</th>
            <th>Configure</th>
            <th>Destroy</th>
        </tr>
        @foreach($lecturers as $lecturer)
            <tr>
                <td>
                    {{$lecturer->id}}
                </td>
                <td>
                    {{$lecturer->lecturer_name}}
                </td>
                <td>
                    {{$lecturer->email}}
                </td>
                <td>
                    {{$lecturer->phone}}
                </td>
{{--                <td>--}}
{{--                    {{$lecturer->password}}--}}
{{--                </td>--}}
                <td>
                    {{$lecturer->specialized_name}}
                </td>
                <td>
                    <a href="{{route('lecturer.edit', $lecturer -> id )}}" class="btn btn-primary btn-fw">Edit</a>
                </td>
                <td>
                    <form method="post" action="{{route('lecturer.destroy', $lecturer -> id )}}">
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
@endsection
