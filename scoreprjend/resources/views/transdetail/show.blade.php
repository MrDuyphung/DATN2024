@extends('layout.masterStudentUser')
@section('content')

    <div class="col-lg-15 grid-margin stretch-card">

        <div class="card">
            <div class="card-body">
                <h1 class="card-title"></h1>
{{--                <form method="get" action="{{ route('transdetail.show') }}">--}}

{{--                        <label for="subject_id">Chọn Môn Học:</label>--}}
{{--                        <select class="input-group" id="subject_id" name="subject_id">--}}
{{--                            <option value="">Tất cả môn học</option>--}}
{{--                            @foreach($transcriptDetails as $transcript_detail)--}}
{{--                                <option value="{{ $transcript_detail->id }}">{{ $transcript_detail->transcript->division->subject->subject_name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    <button type="submit" class="btn btn-primary">Lọc</button>--}}
{{--                </form>--}}


            @if(count($transcriptDetails) === 0)
                    <div class="alert alert-info" >
                        No Results Found
                    </div>
                @else
                @endif
{{--                <td>--}}
{{--                    <a href="--}}
{{--                    {{ route('report.create')}}--}}
{{--                    " class="btn btn-primary">Report to admin</a>--}}
{{--                </td>--}}
                <table class="table table-striped " >

                    <tr>
                        <th>Student_Name</th>
                        <th>Class</th>
                        <th>Specialized</th>
                        <th>Subject</th>
                        <th>Test time</th>
                        <th>Semester</th>
                        <th>Note</th>
                        <th>Score</th>
                        <th>Rates</th>
                    </tr>
                    @foreach($transcriptDetails as $transcript_detail)
                        <tr>
                            <td>
                                {{$transcript_detail->student->student_name}}
                            </td>
                            <td>
                                {{$transcript_detail->class_name}}_{{$transcript_detail->sy_name}}
                            </td>
                            </td>
                            <td>
                                {{$transcript_detail->specialized_name}}
                            </td>

                            <td>
                                {{$transcript_detail->subject_name}}
                            </td>
                            <td>
                                @if($transcript_detail -> exam_times == 0)
                                    <div class="badge badge-success">1 times</div>
                                @elseif($transcript_detail -> exam_times == 1)
                                    <div class="badge badge-primary">2 times</div>
                                @elseif($transcript_detail -> exam_times == 2)
                                    <div class="badge badge-danger">Banned</div>
                                @elseif($transcript_detail -> exam_times == 3)
                                    <div class="badge badge-warning">Exam Skipped</div>
                                @endif
                            </td>
                            <td>
                                @if($transcript_detail -> semester == 0)
                                    <div class="badge badge-primary">Semester 1</div>
                                @elseif($transcript_detail -> semester == 1)
                                    <div class="badge badge-info">Semester 2</div>
                                @elseif($transcript_detail -> semester == 2)
                                    <div class="badge badge-warning">Extra Semester</div>
                                @endif
                            </td>
                            <td>
                                @if($transcript_detail -> note == 1)
                                    <div class="badge badge-info">Tested</div>
                                @elseif($transcript_detail -> note == 2)
                                    <div class="badge badge-danger">Banned</div>
                                @elseif($transcript_detail -> note == 3)
                                    <div class="badge badge-warning">Exam Skipped</div>
                                @endif
                            </td>
                            <td @if($transcript_detail -> score < 5) class="text-danger"
                                @elseif($transcript_detail -> score >= 5.01) class="text-success"
                                @endif>
                                {{$transcript_detail->score ?? 'None'}}


                            </td>
                            <td @if($transcript_detail -> score < 5)
                                {{$transcript_detail->score ?? 'None'}}
                                <div class="text-danger "> Fail <i class="mdi mdi-arrow-down"></i> </div>
                            @elseif($transcript_detail -> score >= 5.01)
                                <div class="text-success"> Pass <i class="mdi mdi-arrow-up"></i> </div>
                                @endif
                                </td>

                        </tr>
                    @endforeach
                </table>


@endsection
