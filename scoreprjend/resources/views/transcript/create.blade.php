@extends('layout.masterLecturer')

@section('content')
    <form method="post" action="{{ route('transcript.store') }}">
        <div class="card">
            <div class="card-body">
                @csrf
                <input type="hidden" name="division_id" value="{{ $division->id }}">

                <div class="form-group row mb-4">
                    <div class="col-md-3 offset-md-9">
                        <label class="mdi mdi-account-badge-alert">Class:</label>
                        <span>
                            {{ $division->class->class_name }}_{{ $division->class->school_year->sy_name }}
                        </span>
                    </div>
                </div>

                <table class="table table-dark">
                    <tr>
                        <th>Subject</th>
                        <th>Semester</th>
                    </tr>
                    <tr>
                        <td>
                            @isset($division->subject)
                                {{ $division->subject->subject_name }}
                            @else
                                Subject not available
                            @endisset
                        </td>
                        <td>
                            @if(isset($division->semester))
                                @if($division->semester == 0)
                                    <div class="badge badge-primary">Semester 1</div>
                                @elseif($division->semester == 1)
                                    <div class="badge badge-info">Semester 2</div>
                                @elseif($division->semester == 2)
                                    <div class="badge badge-warning">Extra Semester</div>
                                @else
                                    Invalid Semester
                                @endif
                            @else
                                Semester not available
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="form-group">
                    <label class="label">Transcript Name</label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Transcript Name" name="transcript_name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="label">Test times</label>
                    <div>
                        <select name="exam_times" class="input-group">
                            <option value="0">1 time</option>
                            <option value="1">2 times</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-outline-primary">Add</button>
            </div>
        </div>
    </form>
@endsection
