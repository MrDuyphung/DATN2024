

<script src="jquery.min.js"></script>
<script src="bootstrap.min.js"></script>
@if(session('student'))

@endif
<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile not-navigation-link">
            <div class="nav-link">
                <div class="user-wrapper">
                    <div class="profile-image">
                        <img src="{{ url('assets/images/faces/avt.jpg') }}" alt="profile image">
                    </div>
                    <div class="text-wrapper">
                        @if(Auth::guard('student')->check())
                            <p class="student_name">{{ Auth::guard('student')->user()->student_name }}</p>
                        @endif
                        <div class="dropdown" data-display="static">
                            <a href="#" class="nav-link d-flex user-switch-dropdown-toggler" id="" href="#" data-toggle="dropdown" aria-expanded="">
                                <small class="designation text-muted">Student</small>
                                <span class="status-indicator online"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="UsersettingsDropdown">
                                <a class="dropdown-item p-0">
                                    <div class="d-flex border-bottom">
                                        <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                            <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>
                                        </div>
                                        <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">
                                            <i class="mdi mdi-account-outline mr-0 text-gray"></i>
                                        </div>
                                        <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                            <i class="mdi mdi-alarm-check mr-0 text-gray"></i>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item mt-2"> Manage Accounts </a>
                                <a class="dropdown-item"> Change Password </a>
                                <a class="dropdown-item"> Check Inbox </a>
                                <a class="dropdown-item"> Sign Out </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="clock">
                    <center>
                        <span id="date"></span><br>
                        <span id="time"></span>
                    </center>
                </div>
                {{--                <button class="btn btn-success btn-block">New Project <i class="mdi mdi-plus"></i>--}}
                {{--                </button>--}}
            </div>

        </li>
        {{--        <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{url('/')}}">--}}
        {{--                <i class="menu-icon mdi mdi-television"></i>--}}
        {{--                <span class="menu-title">Dashboard</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
        {{--        <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('sy.index')}}">--}}
        {{--                <i class="menu-icon mdi mdi-dna"></i>--}}
        {{--                <span class="menu-title">School Year Management</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
        {{--        <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('specialized.index')}}">--}}
        {{--                <i class="menu-icon mdi mdi-chart-line"></i>--}}
        {{--                <span class="menu-title">Specialized Management</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
        {{--        <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('lecturer.index')}}">--}}
        {{--                <i class="menu-icon mdi mdi-emoticon"></i>--}}
        {{--                <span class="menu-title">Lecturer List</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
        {{--        <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('subject.index')}}">--}}
        {{--                <i class="menu-icon mdi mdi-file-outline"></i>--}}
        {{--                <span class="menu-title">Subject Management</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
        {{--        <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('student.index')}}">--}}
        {{--                <i class="menu-icon mdi mdi-table-large"></i>--}}
        {{--                <span class="menu-title">Student Management</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
        {{--        <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('class.index')}}">--}}
        {{--                <i class="menu-icon mdi mdi-lock-outline"></i>--}}
        {{--                <span class="menu-title">Class Management</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
        {{--        <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('division.show')}}">--}}
        {{--                <i class="menu-icon mdi mdi-access-point"></i>--}}
        {{--                <span class="menu-title">Division</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}
        {{--        <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('lecturer.profile')}}">--}}
        {{--                <i class="menu-icon mdi mdi-account"></i>--}}
        {{--                <span class="menu-title">My Profile</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}

        <li class="nav-item active ">
            <a class="nav-link" href="{{route('transdetail.show')}}">
                <i class="menu-icon mdi mdi-account-badge-horizontal"></i>
                <span class="menu-title">My Academic Score</span>
            </a>
        </li>
        <li class="nav-item active ">
            <a class="nav-link" >
                <i class="menu-icon mdi mdi-book-information-variant"></i>
                <span class="menu-title">My Schedule</span>
            </a>
        </li>
        <li class="nav-item active ">
            <a class="nav-link" >
                <i class="menu-icon mdi mdi-feather"></i>
                <span class="menu-title">Fee Collection</span>
            </a>
        </li>
        <li class="nav-item active ">
            <a class="nav-link" >
                <i class="menu-icon mdi mdi-spellcheck"></i>
                <span class="menu-title">Specialized Transfer</span>
            </a>
        </li>
        <li class="nav-item active ">
            <a class="nav-link" >
                <i class="menu-icon mdi mdi-tablet-dashboard"></i>
                <span class="menu-title">My Attendance Sheet</span>
            </a>
        </li>



{{--        <li class="nav-item active ">--}}
{{--            <a class="nav-link" href="{{route('student.profile')}}">--}}
{{--                <i class="menu-icon mdi mdi-account-badge"></i>--}}
{{--                <span class="menu-title">My Profile</span>--}}
{{--            </a>--}}
{{--        </li>--}}

{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('report.index')}}">--}}
{{--                <i class="menu-icon mdi mdi-professional-hexagon"></i>--}}
{{--                <span class="menu-title">Make a Report</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('reexamine.show')}}">--}}
{{--                <i class="menu-icon mdi mdi-hackernews"></i>--}}
{{--                <span class="menu-title">Reexamine Results</span>--}}
{{--            </a>--}}
{{--        </li>--}}

        {{--    <li class="nav-item">--}}
        {{--      <a class="nav-link" data-toggle="collapse" href="" aria-expanded="" aria-controls="basic-ui">--}}
        {{--        <i class="menu-icon mdi mdi-dna"></i>--}}
        {{--        <span class="menu-title">Basic UI Elements</span>--}}
        {{--        <i class="menu-arrow"></i>--}}
        {{--      </a>--}}
        {{--      <div class="collapse " id="">--}}
        {{--        <ul class="nav flex-column sub-menu">--}}
        {{--          <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('sy.index')}}">School Year Management</a>--}}
        {{--          </li>--}}
        {{--          <li class="nav-item }">--}}
        {{--            <a class="nav-link" href="{{route('specialized.index')}}">Specialized Management</a>--}}
        {{--          </li>--}}
        {{--          <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('lecturer.index')}}">Lecturer List</a>--}}
        {{--          </li>--}}
        {{--        </ul>--}}
        {{--      </div>--}}
        {{--    </li>--}}

        {{--    <li class="nav-item {{ active_class(['charts/chartjs']) }}">--}}
        {{--      <a class="nav-link" href="{{ url('/charts/chartjs') }}">--}}
        {{--        <i class="menu-icon mdi mdi-chart-line"></i>--}}
        {{--        <span class="menu-title">Charts</span>--}}
        {{--      </a>--}}
        {{--    </li>--}}
        {{--    <li class="nav-item {{ active_class(['tables/basic-table']) }}">--}}
        {{--      <a class="nav-link" href="{{ url('/tables/basic-table') }}">--}}
        {{--        <i class="menu-icon mdi mdi-table-large"></i>--}}
        {{--        <span class="menu-title">Tables</span>--}}
        {{--      </a>--}}
        {{--    </li>--}}
        {{--    <li class="nav-item {{ active_class(['icons/material']) }}">--}}
        {{--      <a class="nav-link" href="{{ url('/icons/material') }}">--}}
        {{--        <i class="menu-icon mdi mdi-emoticon"></i>--}}
        {{--        <span class="menu-title">Icons</span>--}}
        {{--      </a>--}}
        {{--    </li>--}}
        {{--    <li class="nav-item {{ active_class(['user-pages/*']) }}">--}}
        {{--      <a class="nav-link" data-toggle="collapse" href="#user-pages" aria-expanded="{{ is_active_route(['user-pages/*']) }}" aria-controls="user-pages">--}}
        {{--        <i class="menu-icon mdi mdi-lock-outline"></i>--}}
        {{--        <span class="menu-title">User Pages</span>--}}
        {{--        <i class="menu-arrow"></i>--}}
        {{--      </a>--}}
        {{--      <div class="collapse {{ show_class(['user-pages/*']) }}" id="user-pages">--}}
        {{--        <ul class="nav flex-column sub-menu">--}}
        {{--          <li class="nav-item {{ active_class(['user-pages/login']) }}">--}}
        {{--            <a class="nav-link" href="{{ url('/user-pages/login') }}">Login</a>--}}
        {{--          </li>--}}
        {{--          <li class="nav-item {{ active_class(['user-pages/register']) }}">--}}
        {{--            <a class="nav-link" href="{{ url('/user-pages/register') }}">Register</a>--}}
        {{--          </li>--}}
        {{--          <li class="nav-item {{ active_class(['user-pages/lock-screen']) }}">--}}
        {{--            <a class="nav-link" href="{{ url('/user-pages/lock-screen') }}">Lock Screen</a>--}}
        {{--          </li>--}}
        {{--        </ul>--}}
        {{--      </div>--}}
        {{--    </li>--}}
        {{--    <li class="nav-item">--}}
        {{--      <a class="nav-link" href="https://www.bootstrapdash.com/demo/star-laravel-free/documentation/documentation.html" target="_blank">--}}
        {{--        <i class="menu-icon mdi mdi-file-outline"></i>--}}
        {{--        <span class="menu-title">Documentation</span>--}}
        {{--      </a>--}}
        {{--    </li>--}}
    </ul>
</nav>
