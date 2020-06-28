@php
$pageBanners = Helper::pageBanners();
$siteSetting = Helper::getSiteSettings();
$menuCategory= Helper::getMenuCategory();
@endphp

@if (!Auth::user())
<!-- Login start -->
<div class="modal fade login_section" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="container">
                    <div class="login_popup">
                        <div class="login">
                            <h2>Login</h2>
                            <form method="POST" id="loginForm" name="loginForm" autocomplete="off">
                                {{ csrf_field() }}
                                <div class="input_wrap">
                                    <label>Email</label>
                                    <input type="email" name="email" id="email" value="" placeholder="">
                                </div>
                                <div class="input_wrap">
                                    <label>Password</label>
                                    <input type="password" name="password" id="password" value="" placeholder="">
                                </div>
                                <div class="input_wrap">
                                    <input type="submit" value="login">
                                </div>
                            </form>
                            <div class="signup_fgtpw">
                                <a data-toggle="modal" data-target="#signup">Sign Up</a> | <a data-toggle="modal" data-target="#forgot_pw">Forgot Your Password?</a>
                            </div>

                            <div class="signup_fgtpw" id="login_message"></div>
                        </div>
                        <div class="login_g">
                            <div class="login_g_inner">
                                <div class="loging_top">
                                    <figure>
                                        <img src="{{asset('images/site/login_logo.png')}}" alt="">
                                    </figure>
                                    <span>STREAM<em>FIT</em></span>
                                </div>
                                <div class="login_txt">
                                    <span>Changing</span> the world of online <span>fitness</span>
                                </div>
                                <div class="login_with_google">
                                    <i class="fab fa-google"></i><input type="submit" value="log in with google">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Login end -->

<!-- Signup start -->
<div class="modal fade login_section" id="signup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="container">
                    <div class="login_popup">
                        <div class="login signup">
                            <h2>Sign up</h2>
                            <form method="POST" id="registrationForm" name="registrationForm" autocomplete="off">
                                {{ csrf_field() }}
                                <div class="input_wrap input_wrap_half">
                                    <label>First Name</label>
                                    <input class="inpField" type="text" name="first_name" id="first_name" value="" placeholder="">
                                </div>
                                <div class="input_wrap input_wrap_half">
                                    <label>Last Name</label>
                                    <input class="inpField" type="text" name="last_name" id="last_name" value="" placeholder="">
                                </div>
                                <div class="input_wrap">
                                    <label>Email</label>
                                    <input type="email" name="email_register" id="email_register" value="" placeholder="">
                                </div>
                                <div class="input_wrap">
                                    <label>Password</label>
                                    <input type="password" name="password_register" id="password_register" value="" placeholder="">
                                </div>
                                <label class="checkmark_sec">I agree the terms and conditions
                                    <input type="checkbox" value="1" name="agree" id="agree">
                                    <span class="checkmark"></span>
                                </label>
                                <div class="input_wrap">
                                    <input type="submit" value="Sign up">
                                </div>
                            </form>
                            <div class="signup_fgtpw">
                                <a data-toggle="modal" data-target="#login">Log in</a> | <a data-toggle="modal" data-target="#forgot_pw">Forgot Your Password?</a>
                            </div>

                            <div class="signup_fgtpw" id="registration_message"></div>
                        </div>
                        <div class="login_g signup_g">
                            <div class="login_g_inner">
                                <div class="loging_top">
                                    <figure>
                                        <img src="{{asset('images/site/login_logo.png')}}" alt="">
                                    </figure>
                                    <span>STREAM<em>FIT</em></span>
                                </div>
                                <div class="login_txt">
                                    <span>Changing</span> the world of online <span>fitness</span>
                                </div>
                                <div class="login_with_google">
                                    <i class="fab fa-google"></i><input type="submit" value="log in with google">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Signup end -->

<!-- Forgot password start -->
<div class="modal fade create_board_section" id="forgot_pw" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="container">
                <div class="modal-body">
                    <div class="create_board_popup">
                        <div class="create_board_popup_inner">
                            <h2>Forgot Passward</h2>
                            <form method="POST" id="forgotPasswordForm" name="forgotPasswordForm" autocomplete="off">
                                {{ csrf_field() }}
                                <input type="text" name="forgot_email" id="forgot_email" placeholder="example@gmail.com">
                                <input type="submit" value="Send Email">
                            </form>
                            <div class="signup_fgtpw" id="forgot_password_message"></div>

                            <figure class="create_board_logo">
                                <img src="{{asset('images/site/create_board_logo.png')}}" alt="">
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Forgot password end -->
@endif

<header class="site_header">
  	<div class="header_top">
      	<div class="container">
          	<div class="hright">				  
			@if (!Auth::user())
				<div class="login">
                  	<ul>					
                      	<li>
                            <span class="s-icon login"></span>
                            <span><a id="loginlink" data-toggle="modal" data-target="#login">login</a></span>
                        </li>
                        <li>
                            <span class="s-icon signup"></span>
                            <span><a id="signuplink" data-toggle="modal" data-target="#signup">Signup</a></span>
                        </li>

                  	</ul>
				</div>
			@else
				@php
				$brandIds = Helper::userBrandVideos();
				@endphp
				<div class="loggedin">
					<div class="logedin_icon">
						<i class="icon-user"></i>
					</div>
					<span>Hi {{ucwords(Auth::user()->first_name)}}</span>
					<span class="s-icon logedin_arrow"></span>
				</div>
				<div class="loggedin_options">
					<ul>
						<li class="profile @if (Route::current()->getName() == 'site.users.edit-profile')active @endif">
							<a href="{{route('site.users.edit-profile')}}">
								<span class="s-icon profile"></span>
								<span>Edit Profile</span>
							</a>
						</li>
						@if (count($brandIds) > 0)
						<li class="favourite @if (Route::current()->getName() == 'site.users.brand')active @endif">
							<a href="{{route('site.users.brand')}}">
								<span class="s-icon favourite"></span>
								<span>Brand</span>
							</a>
						</li>
						@endif
						<li class="profile @if (Route::current()->getName() == 'site.users.profile')active @endif">
							<a href="{{route('site.users.profile')}}">
								<span class="s-icon profile"></span>
								<span>Profile</span>
							</a>
						</li>
						<li class="favourite @if (Route::current()->getName() == 'site.users.my-favourite' || Route::current()->getName() == 'site.users.favourite-board')active @endif">
							<a href="{{route('site.users.my-favourite')}}">
								<span class="s-icon favourite"></span>
								<span>My Favourites</span>
							</a>
						</li>
						<li class="logout">
							<a href="{{route('site.users.logout')}}">
								<span class="s-icon logout"></span>
								<span>Log out</span>
							</a>
						</li>
					</ul>
				</div>
			@endif
          	</div>
          	<div class="h_cntct">
              	<ul>
                    @php
                    if ($siteSetting->address != '') {
                    @endphp
                  	<li>
                      	<span class="s-icon map"></span>
                      	<span class="c_detail">{!!$siteSetting->address!!}</span>
                  	</li>
                    @php
                    }
                    
                    $phoneNo = '';
                    if ($siteSetting->phone_no != '') {
                        $phoneNo = preg_replace('/[^a-zA-Z0-9-_\-.]/','', $siteSetting->phone_no);
                    }
                    if ($phoneNo != '') {
                    @endphp
                  	<li>
                      	<span class="s-icon phone"></span>
                      	<span class="c_detail"><a href="tel:{!!$phoneNo!!}">{!!$siteSetting->phone_no!!}</a></span>
                  	</li>
                    @php
                    }
                    @endphp
              	</ul>
          	</div>
      	</div>
	</div>
	
  	<div class="common_banner">
@if (Route::current()->getName() == 'site.home')
	@if (count($pageBanners) > 0)
      	<div class="owl-carousel owl-theme homeslider d_homeslider">
		@foreach ($pageBanners as $banner)
			@php
			$imgPath = URL:: asset('images').'/site/'.Helper::NO_IMAGE;
			if(file_exists(public_path('/uploads/banner/thumbs/'.$banner->image))) {
		  		$imgPath = URL::to('/').'/uploads/banner/thumbs/'.$banner->image;
			}
			@endphp
          	<div class="item">
              	<div class="banner_box">
                  	<figure class="banner_img">
                      	<img src="{{$imgPath}}" alt="Banner Image">
                  	</figure>
                  	<div class="container">
                      	<div class="banner_text">
                          	<div class="banner_head">{{$banner->title}}</div>
                          	{!! $banner->short_description !!}
                          	<a href="#" class="btn_link"><i class="icon-mobile"></i> CALL US TODAY</a>
                      	</div>
                  	</div>
              	</div>
			</div>
		@endforeach
      	</div>
      	<div class="owl-carousel owl-theme homeslider m_homeslider">
		@foreach ($pageBanners as $banner)
			@php
			$imgPathMobile = URL:: asset('images').'/site/'.Helper::NO_IMAGE;
			if(file_exists(public_path('/uploads/banner/thumbs/'.$banner->mobile_image))) {
		  		$imgPathMobile = URL::to('/').'/uploads/banner/thumbs/'.$banner->mobile_image;
			}
			@endphp
          	<div class="item">
              	<div class="banner_box">
                  	<figure class="banner_img">
                      	<img src="{{$imgPathMobile}}" alt="Banner Image">
                  	</figure>
                  	<div class="container">
                      	<div class="banner_text">
                          	<div class="banner_head">{{$banner->title}}</div>
                          	{!! $banner->short_description !!}
                          	<a href="#" class="btn_link"><i class="icon-mobile"></i> CALL US TODAY</a>
                      	</div>
                  	</div>
              	</div>
			</div>
		@endforeach
		</div>
	@endif
@else
		<div class="inner_banner">
			<figure class="inner_banner_img inner_banner_d">
				<img src="{{asset('images/site/browse_bg.jpg')}}" alt="">
			</figure>
			<figure class="inner_banner_img inner_banner_tb">
				<img src="{{asset('images/site/banner_tab.jpg')}}" alt="">
			</figure>
			<figure class="inner_banner_img inner_banner_m">
				<img src="{{asset('images/site/banner_mobile.jpg')}}" alt="">
			</figure>
			<div class="container">
			@if (Route::current()->getName() == 'site.trending')
				<div class="inner_banner_txt">
					<h1 class="banner_heading">TRENDING</h1>
					<div class="browse_sorting">
						@include('site.elements.video_sortby')
					</div>
				</div>
			@elseif (Route::current()->getName() == 'site.category')
				<div class="inner_banner_txt">
					<h1 class="banner_heading">{{$typeName}}</h1>
					<div class="browse_type">
						<ul>
							<li>Browse By</li>
							<li>Type</li>
						</ul>
					</div>
					<div class="browse_sorting">
						@include('site.elements.video_sortby')						
					</div>
				</div>
			@else
				<div class="inner_banner_txt edit_profile_bnrtxt">
					<h1 class="banner_heading">{{$cmsData->title}}</h1>
				@if (Route::current()->getName() == 'site.users.my-favourite')
					<div class="create_board">
						<a data-toggle="modal" data-target="#create_board">Create Board</a>
					</div>
				@elseif (Route::current()->getName() == 'site.users.favourite-board')
					<div class="create_board">
						<a href="javascript:void(0);">{{$boardData->title}}</a>
					</div>
				@endif
				</div>
			@endif
			</div>
		</div>
@endif
      	<div class="banner_top">
          	<div class="container">
              	<div class="logo">
                  	<a href="{{route('site.home')}}"><img src="{{asset('images/site/logo.png')}}" alt=""></a>
              	</div>
              	<div class="nav_wrapper">
                  	<span class="responsive_btn"><span></span></span>
                  	<nav class="nav_menu">
                      	<ul>
                          	<li @if (Route::current()->getName() == 'site.home')class="current-menu-item" @endif><a href="{{route('site.home')}}">Home</a></li>
                          	<li @if (Route::current()->getName() == 'site.trending')class="current-menu-item" @endif><a href="{{route('site.trending')}}">Trending</a></li>
							<li><a href="#">Browse By</a>
							@if ($menuCategory->count() > 0)	
								<span class="subarrow"></span>
								<ul class="sub-menu">
								@foreach ($menuCategory as $category)
									<li><a href="{{route('site.category', $category->slug)}}">{{$category->title}}</a></li>
								@endforeach									
								</ul>
							@endif
							</li>                          	
                            @if (!Auth::user())
                            <li>
                                <a data-toggle="modal" data-target="#login">Favourites</a>
                            </li>
                            @else
                            <li @if (Route::current()->getName() == 'site.users.my-favourite')class="current-menu-item" @endif>
                                <a href="{{route('site.users.my-favourite')}}">Favourites</a>
                            </li>
                            @endif
                      	</ul>
                  	</nav>
              	</div>
          	</div>
      	</div>
	</div>
</header>