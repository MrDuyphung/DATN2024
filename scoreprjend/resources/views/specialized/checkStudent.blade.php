@extends('layout.master')

@section('content')
    <div class="card">
        <h1 class="dropdown-item-title d-flex justify-content-center">Specialized Management - Check Students</h1>
        <div class="card-body">
            <h2>Class: {{ $class->class_name }}</h2>
            @if($students->isEmpty())
                <p>No students found for this class.</p>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <!-- Add more columns if needed -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->student_name }}</td>
                            <!-- Add more columns if needed -->
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
