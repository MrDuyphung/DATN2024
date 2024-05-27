@extends('layout.masterLecturer')
@section('content')
    <h1>Lecturer Profile</h1>
    <p>Name: {{ $lecturer->lecturer_name }}</p>
    <p>Email: {{ $lecturer->email }}</p>
    <p>Phone: {{ $lecturer->phone }}</p>
{{--    <form method="POST" action="{{ route('lecturer.profile.update') }}">--}}
{{--        @csrf--}}
{{--        @method('PUT')--}}
{{--        <div class="form-group">--}}
{{--            <label for="lecturer_name">Full Name</label>--}}
{{--            <input type="text" name="lecturer_name" id="lecturer_name" value="{{ old('lecturer_name', $lecturer->lecturer_name) }}" class="form-control" required>--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="email">Email</label>--}}
{{--            <input type="email" name="email" id="email" value="{{ old('email', $lecturer->email) }}" class="form-control" required>--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="phone">Phone</label>--}}
{{--            <input type="text" name="phone" id="phone" value="{{ old('phone', $lecturer->phone) }}" class="form-control" required>--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="password">Password (Leave empty to keep the same)</label>--}}
{{--            <input type="password" name="password" id="password" class="form-control">--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="password_confirmation">Confirm Password</label>--}}
{{--            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">--}}
{{--        </div>--}}
{{--        <button type="submit" class="btn btn-primary">Update Profile</button>--}}
{{--    </form>--}}
@endsection
