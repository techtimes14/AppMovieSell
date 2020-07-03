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
                                <a href="#">Add Payment Method</a>
                            </li>
                        </ul>
                    </div>
                    <h1 class="page-title">Add Payment Method</h1>
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
            START DASHBOARD AREA
    =================================-->
    <section class="dashboard-area">
        <div class="dashboard_contents">
            <div class="container">
                @include('admin.elements.notification')
                
                {{ Form::open(array(
									'method'=> 'POST',
									'class' => '',
									'route' => ['site.users.add-payment-method'],
									'name'  => 'paymentMethodForm',
									'id'    => 'paymentMethodForm',
									'files' => true,
									'autocomplete' => false,
									'novalidate' => true)) }}
                    <div class="credit_modules">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="modules__title">
                                   <h3>Add Payment Method</h3>
                                </div>                              
                            </div>
                            <!-- end /.col-md-12 -->
                        </div>
                        <!-- end /.row -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="payment_info modules__content">
                                    <div class="form-group">
                                        <label for="card_number">Name on Card</label>
                                        {{ Form::text('name_on_card', null, array(
                                                                'id' => 'name_on_card',
                                                                'placeholder' => 'John Doe',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                 )) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="card_number">Card Number</label>
                                        {{ Form::text('card_number', null, array(
                                                                'id' => 'card_number',
                                                                'placeholder' => '1234 5678 9012 3456',
                                                                'class' => 'text_field',
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
                                                                        'class' => 'text_field',
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
                                                                        'class' => 'text_field',
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

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cv_code">CVV Code</label>
                                                {{ Form::text('cvv', null, array(
                                                                        'id' => 'cvv',
                                                                        'placeholder' => '123',
                                                                        'class' => 'text_field',
                                                                        'maxlength' => 3,
                                                                        'required' => 'required'
                                                                        )) }}
                                            </div>
                                            <button type="submit" class="btn btn--round btn--default new-btn-default">Complete Registration</button>
                                        </div>
                                    </div>                                    
                                </div>
                                <!-- end /.payment_info -->
                            </div>
                            <!-- end /.col-md-6 -->
                        </div>
                        <!-- end /.row -->
                    </div>
                    <!-- end /.credit_modules -->
                </form>
                <!-- end /.add_credit_form -->
            </div>
            <!-- end /.container -->
        </div>
        <!-- end /.dashboard_menu_area -->
    </section>
    <!--================================
        END DASHBOARD AREA
    =================================-->
    
  @endsection