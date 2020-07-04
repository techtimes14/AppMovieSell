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
									'route' => ['site.users.forgot-password'],
									'name'  => 'forgetPasswordForm',
									'id'    => 'forgetPasswordForm',
									'files' => true,
									'autocomplete' => false,
									'novalidate' => true)) }}
                        <div class="cardify login">
                            <div class="login--header">
                                @include('admin.elements.notification')

                                <h3>Forgot Password</h3>
                                <p>You can retrieve reset password link with your email address</p>
                            </div>
                            <!-- end .login_header -->

                            <div class="login--form">
                                <div class="form-group">
                                    <label for="user_name">Email Address</label>
                                    {{ Form::text('email', null, array(
                                                                'id' => 'email',
                                                                'placeholder' => 'Enter your email address',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>

                                <button class="btn btn--md btn--round" type="submit">Send Link</button>

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