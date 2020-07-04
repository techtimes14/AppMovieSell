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
                <div class="col-lg-6 offset-lg-3">
                    {{ Form::open(array(
									'method'=> 'POST',
									'class' => '',
									'route' => ['site.users.sign-up'],
									'name'  => 'signUpForm',
									'id'    => 'signUpForm',
									'files' => true,
									'autocomplete' => false,
									'novalidate' => true)) }}
                        <div class="cardify signup_form">
                            <div class="login--header">
                                @include('admin.elements.notification')
                                
                                <h3>Create Your Account</h3>
                                <p>Please fill the following fields with appropriate information to register a new MartPlace account.</p>
                            </div>
                            <!-- end .login_header -->

                            <div class="login--form">
                                <div class="form-group">
                                    <label for="urname">Your Name</label>
                                    {{ Form::text('full_name', null, array(
                                                                'id' => 'full_name',
                                                                'placeholder' => 'Enter your name',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>

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
                                    <label for="user_name">Username</label>
                                    {{ Form::text('user_name', null, array(
                                                                'id' => 'user_name',
                                                                'placeholder' => 'Enter your username',
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

                                <button class="btn btn--md btn--round register_btn" type="submit">Register Now</button>

                                <div class="login_assist">
                                    <p>Already have an account?
                                        <a href="{{route('site.users.login')}}">Login</a>
                                    </p>
                                </div>
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