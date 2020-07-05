<div class="menu-area">
	<!-- start .top-menu-area -->
	<div class="top-menu-area">
		<!-- start .container -->
		<div class="container">
			<!-- start .row -->
			<div class="row align-content-center align-items-center">
				<!-- start .col-md-3 -->
				<div class="col-lg-3 col-md-3 col-6 v_middle">
					<div class="logo">
						<a href="index.html">
							<img src="{{asset('images/site/logo.png')}}" alt="logo image" class="img-fluid">
						</a>
					</div>
				</div>
				<!-- end /.col-md-3 -->

				<!-- start .col-md-5 -->
				<div class="col-lg-8 offset-lg-1 col-md-9 col-6 v_middle">
					<!-- start .author-area -->
				@if (!Auth::user())
					<div class="author-area">
						<a href="{{route('site.users.login')}}" class="author-area__seller-btn inline-block">Log In</a>
						<a href="{{route('site.users.sign-up')}}" class="author-area__seller-btn inline-block mr-0">Sign Up</a>
					</div>
				@else
					<div class="author-author__info inline has_dropdown p-0 d-flex align-items-center align-content-center">
						<div class="author__avatar">
							<img src="{{asset('images/site/usr_avatar.png')}}" alt="user avatar">
						</div>
						<div class="autor__info">
							<p class="name">
								{{Auth::user()->full_name}}
							</p>							
						</div>

						<div class="dropdowns dropdown--author">
							<ul>
								<li @if(Route::current()->getName() == 'site.users.edit-profile') class="active" @endif>
									<a href="{{route('site.users.edit-profile')}}"><span class="lnr lnr-user"></span>Edit Profile</a>
								</li>
								<li @if(Route::current()->getName() == 'site.users.my-purchases') class="active" @endif>
									<a href="{{route('site.users.my-purchases')}}"><span class="lnr lnr-cart"></span>My Purchases</a>
								</li>
								<li @if(Route::current()->getName() == 'site.users.my-favourites') class="active" @endif>
									<a href="{{route('site.users.my-favourites')}}"><span class="lnr lnr-heart"></span>My Favourite</a>
								</li>
								<li @if(Route::current()->getName() == 'site.users.membership') class="active" @endif>
									<a href="{{route('site.users.membership')}}"><span class="lnr lnr-briefcase"></span>Membership</a>
								</li>								
								<li>
									<a href="{{route('site.users.logout')}}"><span class="lnr lnr-exit"></span>Logout</a>
								</li>
							</ul>
						</div>
					</div>
				@endif
					<!-- end .author-area -->

					<!-- author area restructured for mobile -->
					<div class="mobile_content ">
						<span class="lnr lnr-user menu_icon"></span>

						<!-- offcanvas menu -->
						<div class="offcanvas-menu closed">
							<span class="lnr lnr-cross close_menu"></span>							
						@if (!Auth::user())
							<div class="text-center mbl-signbtn">
								<a href="{{route('site.users.login')}}" class="author-area__seller-btn">Log In</a>
								<a href="{{route('site.users.sign-up')}}" class="author-area__seller-btn">Sign Up</a>
							</div>
						@else
							<div class="offcanvas-menu closed pt-0">
                                <span class="lnr lnr-cross close_menu"></span>
                                <div class="author-author__info d-flex justify-content-center align-items-center align-content-center">
                                    <div class="author__avatar v_middle">
                                        <img src="{{asset('images/site/usr_avatar.png')}}" alt="user avatar">
                                    </div>
                                    <div class="autor__info v_middle">
                                        <p class="name">
                                            {{Auth::user()->full_name}}
                                        </p>                                        
                                    </div>
                                </div>
                                <!--end /.author-author__info-->
                                <div class="dropdowns dropdown--author">
									<ul>
										<li @if(Route::current()->getName() == 'site.users.edit-profile') class="active" @endif>
											<a href="{{route('site.users.edit-profile')}}"><span class="lnr lnr-user"></span>Edit Profile</a>
										</li>
										<li @if(Route::current()->getName() == 'site.users.my-purchases') class="active" @endif>
											<a href="{{route('site.users.my-purchases')}}"><span class="lnr lnr-cart"></span>My Purchases</a>
										</li>
										<li @if(Route::current()->getName() == 'site.users.my-favourites') class="active" @endif>
											<a href="{{route('site.users.my-favourites')}}"><span class="lnr lnr-heart"></span>My Favourite</a>
										</li>
										<li @if(Route::current()->getName() == 'site.users.membership') class="active" @endif>
											<a href="{{route('site.users.membership')}}"><span class="lnr lnr-briefcase"></span>Membership</a>
										</li>								
										<li>
											<a href="{{route('site.users.logout')}}"><span class="lnr lnr-exit"></span>Logout</a>
										</li>
									</ul>
                                </div>                               
                            </div>
						@endif
						</div>
					</div>
					<!-- end /.mobile_content -->
				</div>
				<!-- end /.col-md-5 -->
			</div>
			<!-- end /.row -->
		</div>
		<!-- end /.container -->
	</div>
	<!-- end  -->

	<!-- start .mainmenu_area -->
	<div class="mainmenu">
		<!-- start .container -->
		<div class="container">
			<!-- start .row-->
			<div class="row">
				<!-- start .col-md-12 -->
				<div class="col-md-12">
					<div class="navbar-header navbar-header-mbl">							
					</div>
					<nav class="navbar navbar-expand-md navbar-light mainmenu__menu">
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
							aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="navbarNav">
							<ul class="navbar-nav">
								<li class="active"><a href="{{url('/')}}">HOME</a></li>
								<li><a href="about.html">About us</a></li>
								<li><a href="javascript:void(0);">Services</a></li>
								<li><a href="javascript:void(0);">Contact</a></li>
								<li><a href="javascript:void(0);">Legal</a></li>									
								<li><a href="javascript:void(0);">Market Place</a></li>
								<li><a href="{{route('site.users.affiliated-sign-up')}}">Affiliate Signup</a></li>
							</ul>
						</div>
						<!-- /.navbar-collapse -->
					</nav>
				</div>
				<!-- end /.col-md-12 -->
			</div>
			<!-- end /.row-->
		</div>
		<!-- start .container -->
	</div>
	<!-- end /.mainmenu-->
</div>