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
                        <div class="order_details">
                            <div class="order_top">
                                <div class="order_sec">
                                    <h4>Summary</h4>
                                    <em>Regular membership</em>
                                </div>
                                <div class="price_sec">
                                    <span>$0</span>
                                </div>
                            </div>
                            <div class="order_total">
                                <div class="total_left">
                                    <h3>Total</h3>
                                </div>
                                <div class="total_right">
                                    <span>$0</span>
                                </div>
                            </div>
                        </div>
                        <div class="affilated_signup_text">
                            <h3>Why do we ask for your billing information?</h3>
                            <p>Because we are only licensed to distribute our content to certain countries, we ask that you verify your mailing address by providing us with a valid credit card number. WE GUARANTEE NO CHARGE WILL BE APPLIED for validating your account. No charges will appear on your credit card statement unless you upgrade to a Premium Membership or make a purchase.</p>
                        </div>
                        <div class="affilated_signup_text">
                            <h3>We offer you a secure online environment</h3>
                            <p>Unlike many companies on the Internet, we use encryption technology for your security. Our site uses Secure Sockets Layering (SSL) to encrypt your personal information, such as your credit card number, name and address, before sending it over the Internet. Your data is encrypted and password protected so that no one can see your information!</p>
                        </div>
                        <div class="affilated_signup_text">
                            <h3>There are never any hidden charges</h3>
                            <p>We make sure to provide our members with a detailed transaction history so they know what they are paying for. Credit card information is necessary to facilitate future purchases only. No charge will appear on your credit card summary, unless you upgrade to Premium Membership or make a purchase.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    {{ Form::open(array(
									'method'=> 'POST',
									'class' => '',
									'route' => ['site.users.affiliated-payment'],
									'name'  => 'affiliatedPaymentForm',
									'id'    => 'affiliatedPaymentForm',
									'files' => true,
									'autocomplete' => false,
									'novalidate' => true)) }}
                        <div class="cardify signup_form affilate_payment_form">
                            <div class="login--header">
                                @include('admin.elements.notification')

                                <h3>Payment Information</h3>
                            </div>
                            <!-- end .login_header -->
                            <div class="login--form">
                                <div class="form-group half">
                                    <label for="first_name">First Name</label>
                                    {{ Form::text('first_name', null, array(
                                                                'id' => 'first_name',
                                                                'placeholder' => 'Enter your first name',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                </div>
                                <div class="form-group half last">
                                    <label for="last_name">Last Name</label>
                                    {{ Form::text('last_name', null, array(
                                                                'id' => 'last_name',
                                                                'placeholder' => 'Enter your last name',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                </div>
                                <div class="clear"></div>
                                <div class="form-group">
                                    <label for="postal_code">Postal Code</label>
                                    {{ Form::text('postal_code', null, array(
                                                                'id' => 'postal_code',
                                                                'placeholder' => 'Enter your postal code',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                </div>
                            </div>
                            <div class="card_details_section">
                                <div class="login--header">
                                    <h3>Card Details</h3>
                                </div>
                                <div class="login--form">
                                    <div class="form-group">
                                        <label for="card_number">Card Number</label>
                                        {{ Form::text('name_on_card', null, array(
                                                                'id' => 'name_on_card',
                                                                'placeholder' => 'John Doe',
                                                                'class' => 'text_field
                                                                ',
                                                                'required' => 'required'
                                                                )) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="card_number">Card Number</label>
                                        {{ Form::text('card_number', null, array(
                                                                'id' => 'card_number',
                                                                'placeholder' => '1234 5678 9012 3456',
                                                                'class' => 'text_field card_validation',
                                                                'required' => 'required'
                                                                )) }}
                                    </div>
                                    <!-- lebel for date selection -->
                                    <label for="name">Expire Date</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="select-wrap select-wrap2">
                                                    {{ Form::text('expiry_month', null, array(
                                                                        'id' => 'expiry_month',
                                                                        'placeholder' => 'MM',
                                                                        'class' => 'text_field card_validation',
                                                                        'maxlength' => 2,
                                                                        'required' => 'required'
                                                                        )) }}
                                                </div>
                                                <!-- end /.select-wrap -->
                                            </div>
                                            <!-- end /.form-group -->
                                        </div>
                                        <!-- end /.col-md-6-->

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="select-wrap select-wrap2">
                                                    {{ Form::text('expiry_year', null, array(
                                                                        'id' => 'expiry_year',
                                                                        'placeholder' => 'YYYY',
                                                                        'class' => 'text_field card_validation',
                                                                        'maxlength' => 4,
                                                                        'required' => 'required'
                                                                        )) }}
                                                </div>
                                                <!-- end /.select-wrap -->
                                            </div>
                                            <!-- end /.form-group -->
                                        </div>
                                        <!-- end /.col-md-6-->
                                    </div>
                                    <!-- end /.row -->
                                    <div class="form-group cvv">
                                        <label for="cv_code">CVV Code</label>
                                        {{ Form::password('cvv', array(
                                                                    'id' => 'cvv',
                                                                    'placeholder' => '123',
                                                                    'class' => 'text_field card_validation',
                                                                    'maxlength' => 3,
                                                                    'required' => 'required'
                                                                    )) }}
                                        <figure>
                                            <img src="{{asset('images/site/CVV.png')}}" alt="">
                                        </figure>
                                    </div>
                                    <div class="clear"></div>
                                    <button class="btn btn--md btn--round register_btn" type="submit">Continue</button>
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