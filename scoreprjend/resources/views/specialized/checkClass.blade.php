@extends('layout.master')

@section('content')
    <div class="card">
        <h1 class="dropdown-item-title d-flex justify-content-center">Specialized Management - Check Class</h1>
        <div class="card-body">
            <h2>{{ $specialized->specialized_name }}</h2>
            @if($classes->isEmpty())
                <p>No classes found for this specialized.</p>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>Class ID</th>
                        <th>Class Name</th>
                        <th>Number of Students</th>
                        <th>Action</th> <!-- New column for the Check Students button -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td>{{ $class->id }}</td>
                            <td>{{ $class->class_name }}</td>
                            <td>{{ $students->where('class_id', $class->id)->count() }}</td>
                            <td>
                                <a href="{{ route('specialized.checkStudent', ['class_id' => $class->id]) }}"
                                   class="btn btn-info">Check Students</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
