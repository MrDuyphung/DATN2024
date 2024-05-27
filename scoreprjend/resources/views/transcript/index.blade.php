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


    <div class="col-lg-15 grid-margin stretch-card" >
        <div class="card">
            <div class="card-body">
                <h1>Transcripts</h1>
{{--                <a class="btn btn-success btn-fw; menu-icon mdi mdi-creation" href="{{route('transcript.create')}}">Add Transcript</a>--}}
                <table class="table table-striped" >
                    <tr>
{{--                        <th>ID Transcript</th>--}}
                        <th>Transcript Name</th>
                        <th>Test times</th>
                        <th>Semester</th>

                        <th>Class</th>
                        <th>Specialized</th>
                        <th>Subject</th>
                        <th>Lecturer</th>
                        <th>Action & Status</th>
                        <th>Point Maker</th>
                        <th></th>
                    </tr>
                    @foreach($transcripts as $transcript)
                        <tr>
{{--                            <td>--}}
{{--                                {{$transcript->id}}--}}
{{--                            </td>--}}
                            <td>
                                {{$transcript->transcript_name}}
                            </td>
                            <td>
                                @if($transcript -> exam_times == 0)
                                    <div class="badge badge-success">1 Times</div>
                                @elseif($transcript -> exam_times == 1)
                                    <div class="badge badge-info">2 Times</div>
                                @elseif($transcript -> exam_times == 2)
                                    <div class="badge badge-warning">Relearn</div>
                                @endif
                            </td>
                            <td>
                                @if($transcript -> semester == 0)
                                    <div class="badge badge-primary">Semester 1</div>
                                @elseif($transcript -> semester == 1)
                                    <div class="badge badge-info">Semester 2</div>
                                @elseif($transcript -> semester == 2)
                                    <div class="badge badge-warning">Extra Semester</div>
                                @endif
                            </td>
                            <td>
                                {{$transcript->class_name}}_{{$transcript->sy_name}}
                            </td>
                            <td>
                                {{$transcript->specialized_name}}
                            </td>
                            <td>
                                {{$transcript->subject_name}}
                            </td>
                            <td>
                                {{$transcript->lecturer_name}}
                            </td>
{{--                            <td>--}}

{{--                                @if($transcript->isFinish())--}}
{{--                                    <div class="badge badge-success">Finished</div>--}}
{{--                                @else--}}
{{--                                    <div class="badge badge-warning">In Progress</div>--}}
{{--                                @endif--}}
{{--                            </td>--}}
                            <td>
                                @if($transcript->isFinish())
                                    <div class="badge badge-success">Finished</div>
{{--                                    <a class="btn btn-outline-success" href="{{route('transdetail.index', ['transcript_id' => $transcript->id])}}">Check</a>--}}
                                @else
                                    @if($transcript->exam_times == 0)
                                        <a class="btn btn-outline-success" href="{{route('transdetail.create', ['transcript_id' => $transcript->id])}}">Add Point</a>
                                    @elseif($transcript->exam_times == 1)
                                        @if($transcript->transcriptDetails->where('transcript_id', $transcript->id)->count() > 0)
                                            <div class="badge badge-success">Finished</div>
{{--                                            <a class="btn btn-outline-success" href="{{route('transdetail.index', ['transcript_id' => $transcript->id])}}">Check</a>--}}

                                        @else
                                            <a class="btn btn-outline-dark" href="{{route('transdetail.created', ['transcript_id' => $transcript->id])}}">Add Point</a>
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>
                                <form method="post" action="{{ route('transcript.destroy', $transcript -> id ) }}" id="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button class="menu-icon; mdi mdi-delete badge badge-danger" type="submit" onclick="return confirmDelete();">Delete</button>
                                </form>

                                <script>
                                    function confirmDelete() {
                                        // Sử dụng hộp thoại xác nhận
                                        return confirm('Do you want to delete?');
                                    }
                                </script>
                            </td>
                            <td>
                                <a class="btn btn-outline-success" href="{{route('transdetail.index', ['transcript_id' => $transcript->id])}}">Check</a>

                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
