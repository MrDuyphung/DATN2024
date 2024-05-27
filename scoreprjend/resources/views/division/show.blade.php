@extends('layout.masterLecturer')

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

    <h1>Division Information</h1>

    <div class="mb-3">
        <form method="GET" action="{{ route('division.show') }}">
            <div class="input-group">
                <select name="status" class="input-group">
                    <option value="">Check Status</option>
                    <option value="Working" {{ session('status') == 'Working' ? 'selected' : '' }}>Working</option>
                    <option value="Not Working" {{ session('status') == 'Not Working' ? 'selected' : '' }}>Not Working</option>
                    <option value="Job Done" {{ session('status') == 'Job Done' ? 'selected' : '' }}>Job Done</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Semester</th>
            <th>Class</th>
            <th>Lecturer</th>
            <th>Specialized</th>
            <th>Subject</th>
            <th>Admin Assigned</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @php $count = 0 @endphp
        @foreach($divisions as $division)
            @if($division->getStatus() == $selectedStatus)
                @php $count++ @endphp
                <tr>
                    <td>
                        @if($division->semester == 0)
                            <div class="badge badge-primary">Semester 1</div>
                        @elseif($division->semester == 1)
                            <div class="badge badge-info">Semester 2</div>
                        @elseif($division->semester == 2)
                            <div class="badge badge-warning">Extra Semester</div>
                        @endif
                    </td>
                    <td>{{ $division->class->class_name }}_{{ $division->class->school_year->sy_name }}</td>
                    {{--                <td>--}}
                    {{--                    @if($division->class_count == 1)--}}
                    {{--                        <div class="badge badge-primary">1 Class</div>--}}
                    {{--                    @elseif($division->class_count == 2)--}}
                    {{--                        <div class="badge badge-warning">2 Class</div>--}}
                    {{--                    @elseif($division->class_count == 3)--}}
                    {{--                        <div class="badge badge-danger">3 Class</div>--}}
                    {{--                    @endif--}}
                    {{--                </td>--}}
                    <td>
                        {{ $division->lecturer_name }}
                    </td>
                    <td>
                        {{ $division->specialized_name }}
                    </td>
                    <td>
                        {{ $division->subject_name }}
                    </td>
                    <td>
                        {{ $division->username }}
                    </td>
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
                        @if($division->getStatus() == 'Job Done')
                            {{--                        <div class="badge badge-success">Finished</div>--}}
                            {{--                        --}}{{--                                    <a class="btn btn-outline-success" href="{{route('transdetail.index', ['transcript_id' => $transcript->id])}}">Check</a>--}}
                        @elseif($division->getStatus() == 'Working')
                            {{--                        @if($transcript->exam_times == 0)--}}
                            <a class="btn btn-outline-success" href="{{route('transcript.create', ['division_id' => $division->id])}}">Make Transcript</a>
                            {{--                        @elseif($transcript->exam_times == 1)--}}
                            {{--                            @if($transcript->transcriptDetails->where('transcript_id', $transcript->id)->count() > 0)--}}
                            {{--                                <div class="badge badge-success">Finished</div>--}}
                            {{--                                --}}{{--                                            <a class="btn btn-outline-success" href="{{route('transdetail.index', ['transcript_id' => $transcript->id])}}">Check</a>--}}

                        @else
                            <a class="btn btn-outline-success" href="{{route('transcript.create', ['division_id' => $division->id])}}">Make Transcript</a>
                        @endif


                    </td>
                </tr>
            @endif
        @endforeach
        @if($count == 0)
            <tr>
                <td colspan="8" style="text-align: center" class="blink-red">Remember to check your work timeline</td>
            </tr>


        @endif
        </tbody>
    </table>
@endsection
<style>
    @keyframes blink {
        0% {
            background-color: rgba(255, 0, 0, 0.1);
        }
        50% {
            background-color: rgba(255, 0, 0, 0.5);
        }
        100% {
            background-color: rgba(255, 0, 0, 0.1);
        }
    }

    .blink-red {
        animation: blink 1.5s infinite;
    }
</style>
