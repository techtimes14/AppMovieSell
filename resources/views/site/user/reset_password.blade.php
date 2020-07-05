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
            START LOGIN AREA
    =================================-->
    <section class="login_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">                    					
					{{ Form::open(array(
									'method'=> 'POST',
									'class' => '',
									'route' => ['site.users.reset-password', $token],
									'name'  => 'resetPasswordForm',
									'id'    => 'resetPasswordForm',
									'files' => true,
									'autocomplete' => false,
									'novalidate' => true)) }}
                        <div class="cardify login">
                            <div class="login--header">
                                @include('admin.elements.notification')

                                <h3>Reset Password</h3>
                                <p>Please enter your desired password</p>
                            </div>
                            <!-- end .login_header -->

                            <div class="login--form">
                                <div class="form-group">
                                    <label for="user_name">New Password</label>
                                    {{ Form::password('password', array(
                                                                'id' => 'password',
                                                                'placeholder' => 'Enter your new password',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>

                                <div class="form-group">
                                    <label for="user_name">Confirm Password</label>
                                    {{ Form::password('confirm_password', array(
                                                                'id' => 'confirm_password',
                                                                'placeholder' => 'Enter your confirm password',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>

                                <button class="btn btn--md btn--round" type="submit">Submit</button>

                                <div class="login_assist">
                                    <p>Already have an account?
                                        <a href="{{route('site.users.login')}}">Login</a></p>
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
            END LOGIN AREA
    =================================-->
    
  @endsection