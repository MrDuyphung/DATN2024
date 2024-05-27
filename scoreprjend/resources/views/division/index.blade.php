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
{{--    <div class="mb-3">--}}
{{--        <form method="GET" action="{{ route('division.index') }}">--}}
{{--            <div class="input-group">--}}
{{--               --}}
{{--                <button type="submit" class="btn btn-primary">Search</button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </div>--}}
    <div class="mb-3">
        <form method="GET" action="{{ route('division.index') }}">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search by ..." name="search" value="{{ session('search') }}">
                <select name="status" class="input-group">
                    <option value="">Check Status</option>
                    <option value="Working" {{ session('status') == 'Working' ? 'selected' : '' }}>Working</option>
                    <option value="Not Working" {{ session('status') == 'Not Working' ? 'selected' : '' }}>Not Working</option>
                    <option value="Job Done" {{ session('status') == 'Job Done' ? 'selected' : '' }}>Job Done</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    Filter</button>
            </div>
        </form>
    </div>

    <a class="btn btn-success btn-fw; menu-icon mdi mdi-adjust" href="{{ route('division.create') }}" class="btn btn-light">Add Division</a>
    <table class="table table-striped">
        <thead>
        <tr>
{{--            <th>ID Division</th>--}}
{{--            <th>Division Name</th>--}}
            <th>Semester</th>
            <th>Class</th>
            <th>Lecturer</th>
            <th>Specialized</th>
            <th>Subject</th>
            <th>Admin Assigned</th>
            <th>Status</th>
            <th>Config</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($divisions as $division)
            @if($division->getStatus() == $selectedStatus)
            <tr>
{{--                <td>{{ $division->id }}</td>--}}
{{--                <td>{{ $division->division_name }}</td>--}}
                <td>
                    @if($division->semester == 0)
                        <div class="badge badge-primary">Semester 1</div>
                    @elseif($division->semester == 1)
                        <div class="badge badge-info">Semester 2</div>
                    @elseif($division->semester == 2)
                        <div class="badge badge-warning">Extra Semester</div>
                    @endif
                </td>
{{--                <td>--}}
{{--                    @if($division->class_count == 1)--}}
{{--                        <div class="badge badge-primary">1 Class</div>--}}
{{--                    @elseif($division->class_count == 2)--}}
{{--                        <div class="badge badge-warning">2 Class</div>--}}
{{--                    @elseif($division->class_count == 3)--}}
{{--                        <div class="badge badge-danger">3 Class</div>--}}
{{--                    @endif--}}
{{--                </td>--}}
                <td>{{ $division->class->class_name }}_{{ $division->class->school_year->sy_name }}</td>

                <td>{{ $division->lecturer_name }}</td>
                <td>{{ $division->specialized_name }}</td>
                <td>{{ $division->subject_name }}</td>
                <td>{{ $division->username }}</td>
                <td>
                    @if($division->getStatus() == 'Job Done')
                        <div class="badge badge-success">{{ $division->getStatus() }}</div>
                    @elseif($division->getStatus() == 'Working')
                        <div class="badge badge-warning">{{ $division->getStatus() }}</div>
                    @else
                        <div class="badge badge-danger">{{ $division->getStatus() }}</div>
                    @endif
                </td>
                <td>
                    <a href="{{ route('division.edit', $division->id) }}" class="mdi mdi-account-edit badge badge-primary">Edit</a>
                    <form method="post" action="{{ route('division.destroy', $division->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="mdi mdi-delete badge badge-danger" type="submit" onclick="return confirmDelete();">Delete</button>
                    </form>
                    <script>
                        function confirmDelete() {
                            // Sử dụng hộp thoại xác nhận
                            return confirm('Do you want to delete?');
                        }
                    </script>
                </td>
                <td>
                    @if($division->getStatus() == 'Job Done')
                        <a href="{{ route('division.checkTranscript', $division->id) }}" class="mdi-account">Check</a>
                    @else
                        <div class="badge badge-dark blinking">Unavailable</div>
                    @endif
                </td>

            </tr>
            @endif
        @endforeach
        </tbody>
    </table>
@endsection
<style>
    .blinking {
        animation: blinkingText 1.2s infinite;
    }

    @keyframes blinkingText {
        0% { color: red; }
        50% { color: transparent; }
        100% { color: red; }
    }
</style>

<script>
    function confirmDelete() {
        return confirm('Do you want to delete?');
    }
</script>
