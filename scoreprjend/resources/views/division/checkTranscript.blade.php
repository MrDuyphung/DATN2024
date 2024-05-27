@extends('layout.master')

@section('content')
    <div class="card">
        <h1 class="dropdown-item-title d-flex justify-content-center">Division Management - Check Transcript</h1>
        <div class="card-body">
{{--            <h2>{{ $specialized->specialized_name }}</h2>--}}
            @if($transcripts->isEmpty())
                <p>No Transcript found for this division.</p>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>Transcript ID</th>
                        <th>Transcript Name</th>
                        <th>Exam Times</th>
{{--                        <th>Number of Students</th>--}}
                        <th>Action</th> <!-- New column for the Check Students button -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transcripts as $transcript)
                        <tr>
                            <td>{{ $transcript->id }}</td>
                            <td>{{ $transcript->transcript_name }}</td>
                            <td>@if($transcript -> exam_times == 0)
                                    <div class="badge badge-success">1 Times</div>
                                @elseif($transcript -> exam_times == 1)
                                    <div class="badge badge-info">2 Times</div>
                                @elseif($transcript -> exam_times == 2)
                                    <div class="badge badge-warning">Relearn</div>
                                @endif</td>
{{--                            <td>{{ $students->where('class_id', $class->id)->count() }}</td>--}}
                            <td>
                                <a href="{{ route('division.checkTransDetail', ['transcript_id' => $transcript->id]) }}"
                                   class="mdi-eye">Check Info</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
