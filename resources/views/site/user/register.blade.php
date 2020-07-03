@extends('site.layouts.app', [])
  @section('content')
  
    <!--================================
        START BREADCRUMB AREA
    =================================-->
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb">
                        <ul>
                            <li>
                                <a href="index.html">Home</a>
                            </li>
                            <li class="active">
                                <a href="#">Signup</a>
                            </li>
                        </ul>
                    </div>
                    <h1 class="page-title">Sign up</h1>
                </div>
                <!-- end /.col-md-12 -->
            </div>
            <!-- end /.row -->
        </div>
        <!-- end /.container -->
    </section>
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
                    <form action="#">
                        <div class="cardify signup_form">
                            <div class="login--header">
                                <h3>Create Your Account</h3>
                                <p>Please fill the following fields with appropriate information to register a new MartPlace
                                    account.
                                </p>
                            </div>
                            <!-- end .login_header -->

                            <div class="login--form">

                                <div class="form-group">
                                    <label for="urname">Your Name</label>
                                    <input id="urname" type="text" class="text_field" placeholder="Enter your Name">
                                </div>

                                <div class="form-group">
                                    <label for="email_ad">Email Address</label>
                                    <input id="email_ad" type="text" class="text_field" placeholder="Enter your email address">
                                </div>

                                <div class="form-group">
                                    <label for="user_name">Username</label>
                                    <input id="user_name" type="text" class="text_field" placeholder="Enter your username...">
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" type="text" class="text_field" placeholder="Enter your password...">
                                </div>

                                <div class="form-group">
                                    <label for="con_pass">Confirm Password</label>
                                    <input id="con_pass" type="text" class="text_field" placeholder="Confirm password">
                                </div>

                                <button class="btn btn--md btn--round register_btn" type="submit">Register Now</button>

                                <div class="login_assist">
                                    <p>Already have an account?
                                        <a href="signup.html">Login</a>
                                    </p>
                                </div>
                            </div>
                            <!-- end .login--form -->
                        </div>
                        <!-- end .cardify -->
                    </form>
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