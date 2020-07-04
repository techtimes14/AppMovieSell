@extends('site.layouts.app', [])
  @section('content')
  
    <!--================================
        START BREADCRUMB AREA
    =================================-->
    @include('site.elements.breadcrumb')
    <!--================================
        END BREADCRUMB AREA
    =================================-->

    <!--================================
            START SIGNUP AREA
    =================================-->
    <section class="signup_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="affilated_signup_content">
                        <figure>
                            <img src="{{asset('images/site/registration_img.jpg')}}" alt="">
                        </figure>
                        <div class="tab_menu">
							<ul class="tf_head clearfix">
								<li class="active"><a href="javascript:void(0)">Join Us</a></li>
								<li><a href="javascript:void(0)">Why to Join</a></li>
								<li><a href="javascript:void(0)">Enjoyment</a></li>
							</ul>
                        </div>
                        <div class="tab_details">
                            <div class="tab_info" style="display: block;">
                                <div class="affilated_signup_text">
                                    <h3>Don't have an account?</h3>
                                    <p><strong>Register now!</strong> It only takes 2 minutes to register for over a million titles.</p>
                                    <p>Joined hundreds of thousands of satisfied members, who previously spent countless hours for searching apps, upcoming moves and many more. But, now they are enjoying all those aspects joining our website</p>
                                </div>
                            </div>
                            <div class="tab_info" style="display: none;">
                                <div class="affilated_signup_text">
                                    <h3>Why would you join us?</h3>
                                    <p><strong>It's HERE and it's FREE!</strong> Here's why you should join:</p>
                                    <ul>
                                        <li>Wherever you are: directly to the browser on your PC or tablet for searching Upcoming Movies, Online Apps and many more.</li>
                                        <li>More than 10 million titles that cover every genre imaginable, at your fingertips.</li>
                                        <li>All platforms. Fully optimized.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab_info" style="display: none;">
                                <div class="affilated_signup_text">
                                    <h3>What are you waiting for?</h3>
                                    <p>Find out why thousands of people join each day.</p>
                                    <p>Just <a href="signup.html">Sign up</a> now and experience unlimited entertainment!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    {{ Form::open(array(
									'method'=> 'POST',
									'class' => '',
									'route' => ['site.users.affiliated-sign-up'],
									'name'  => 'affiliatedSignUpForm',
									'id'    => 'affiliatedSignUpForm',
									'files' => true,
									'autocomplete' => false,
									'novalidate' => true)) }}                    
                        <div class="cardify signup_form">
                            <div class="login--header">
                                @include('admin.elements.notification')

                                <h3>Register Now</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                </p>
                            </div>
                            <!-- end .login_header -->
                            <div class="login--form">
                                <div class="form-group">
                                    <label for="email_ad">Email Address</label>
                                    {{ Form::text('email', null, array(
                                                                'id' => 'email',
                                                                'placeholder' => 'Enter your email address',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    {{ Form::password('password', array(
                                                                'id' => 'password',
                                                                'placeholder' => 'Enter your password',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                </div>
                                <div class="form-group">
                                    <label for="con_pass">Confirm Password</label>
                                    {{ Form::password('confirm_password', array(
                                                                'id' => 'confirm_password',
                                                                'placeholder' => 'Enter your confirm password',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                </div>
                                <button class="btn btn--md btn--round register_btn" type="submit">Continue</button>
                            </div>
                            <!-- end .login--form -->
                        </div>
                        <!-- end .cardify -->
                    {{Form::close()}}
                </div>
                <!-- end .col-md-6 -->
            </div>
            <!-- end .row -->
        </div>
        <!-- end .container -->
    </section>
    <!--================================
            END SIGNUP AREA
    =================================-->
    
  @endsection