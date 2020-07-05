<a href="{{route('admin.dashboard')}}" class="logo">
    {{-- <span class="logo-mini"><b>{{ Helper::getAppNameFirstLetters() }}</b></span>
    <span class="logo-lg"><b>{{ Helper::getAppName() }}</b></span>
    <img src="{{ asset('images/site/logo.jpg') }}"/>     --}}
    <span class="logo-mini"><b>{{ Helper::getAppNameFirstLetters() }}</b></span>
    <img class="logo-lg" src="{{ asset('images/admin/logo.png') }}" width="100px" height="20px" />
</a>

<nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle admin_user_img_area" data-toggle="dropdown">
                    <img src="{{ asset('js/admin/dist/img/avatar5.png') }}" class="user-image" alt="{{ Auth::guard('admin')->user()->first_name.' '.Auth::guard('admin')->user()->last_name }}">
                    <span class="hidden-xs">{{ Auth::guard('admin')->user()->first_name.' '.Auth::guard('admin')->user()->last_name }}</span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                        <img src="{{ asset('js/admin/dist/img/avatar5.png') }}" class="img-circle" alt="{{ Auth::guard('admin')->user()->first_name.' '.Auth::guard('admin')->user()->last_name }}">
                        <p>
                            {{Auth::guard('admin')->user()->first_name.' '.Auth::guard('admin')->user()->last_name}}
                        </p>
                    </li>
                    <!-- Menu Body -->
                    <li class="user-body">
                        <div class="row">
                            
                            <div class="col-xs-12 text-center">
                                <a class="" href="{{ route('admin.change-password') }}">Change Password</a>
                            </div>
                            
                        </div>
                    </li>
                    <!-- Menu Footer -->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="{{ route('admin.edit-profile') }}" class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('admin.logout') }}" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>