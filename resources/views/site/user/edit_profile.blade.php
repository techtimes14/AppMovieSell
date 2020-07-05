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
        START DASHBOARD AREA
    =================================-->
    <section class="dashboard-area">
        <div class="dashboard_contents">
            <div class="container">

                {{ Form::open(array(
                                'method'=> 'POST',
                                'class' => '',
                                'route' => ['site.users.edit-profile'],
                                'name'  => 'editProfile',
                                'id'    => 'editProfile',
                                'files' => true,
                                'autocomplete' => false,
                                'novalidate' => true)) }}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="information_module">
                                @include('site.elements.notification')

                                <a class="toggle_title" href="#collapse2" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapse1">
                                    <h4>Personal Information
                                        <span class="lnr lnr-chevron-down"></span>
                                    </h4>
                                </a>

                                <div class="information__set toggle_module collapse show" id="collapse2">
                                    <div class="information_wrapper form--fields">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="firstname">First Name
                                                        <sup>*</sup>
                                                    </label>
                                                    {{ Form::text('first_name', $userDetail->first_name, array(
                                                                'id' => 'first_name',
                                                                'placeholder' => 'First Name',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="lastname">Last Name
                                                        <sup>*</sup>
                                                    </label>
                                                    {{ Form::text('last_name', $userDetail->last_name, array(
                                                                'id' => 'last_name',
                                                                'placeholder' => 'Last Name',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="usrname">Username
                                                <sup>*</sup>
                                            </label>
                                            {{ Form::text('user_name', $userDetail->user_name, array(
                                                                'id' => 'user_name',
                                                                'placeholder' => 'aazztech',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                        </div>

                                        <div class="form-group">
                                            <label for="emailad">Email Address
                                                <sup>*</sup>
                                            </label>
                                            {{ Form::text('email', $userDetail->email, array(
                                                                'id' => 'email',
                                                                'placeholder' => 'Email address',
                                                                'class' => 'text_field',
                                                                'readonly'  => true,
                                                                'disabled'  => true,
                                                                'required' => 'required'
                                                                )) }}
                                        </div>
                                        <div class="form-group">
                                            <label for="authbio">About You</label>
                                            {{ Form::textarea('author_bio', isset($userDetail->userDetail->author_bio)?$userDetail->userDetail->author_bio:'', array(
                                                                'id' => 'author_bio',
                                                                'placeholder' => 'Short brief about yourself',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                        </div>
                                    </div>
                                    <!-- end /.information_wrapper -->
                                </div>
                                <!-- end /.information__set -->
                            </div>
                            <!-- end /.information_module -->
                        </div>
                        <!-- end /.col-md-6 -->

                        <div class="col-lg-6">
                            <div class="information_module">
                                <a class="toggle_title" href="#collapse1" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapse1">
                                    <h4>Biling Information
                                        <span class="lnr lnr-chevron-down"></span>
                                    </h4>
                                </a>
                                <div class="information__set toggle_module collapse" id="collapse1">
                                    <div class="information_wrapper form--fields">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="first_name">First Name
                                                        <sup>*</sup>
                                                    </label>
                                                    {{ Form::text('billing_first_name', isset($userDetail->userDetail->billing_first_name)?$userDetail->userDetail->billing_first_name:'', array(
                                                                'id' => 'billing_first_name',
                                                                'placeholder' => 'First Name',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="last_name">last Name
                                                        <sup>*</sup>
                                                    </label>
                                                    {{ Form::text('billing_last_name', isset($userDetail->userDetail->billing_last_name)?$userDetail->userDetail->billing_last_name:'', array(
                                                                'id' => 'billing_last_name',
                                                                'placeholder' => 'Last Name',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end /.row -->
                                        
                                        <div class="form-group">
                                            <label for="email1">Email Address
                                                <sup>*</sup>
                                            </label>
                                            {{ Form::text('billing_email', isset($userDetail->userDetail->billing_email)?$userDetail->userDetail->billing_email:'', array(
                                                                'id' => 'billing_email',
                                                                'placeholder' => 'Email address',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                        </div>
                                        @php
                                        $countryId = '';
                                        if (isset($userDetail->userDetail->billing_country)) {
                                            $countryId = $userDetail->userDetail->billing_country;
                                        }
                                        @endphp
                                        <div class="form-group">
                                            <label for="country1">Country
                                                <sup>*</sup>
                                            </label>
                                            <div class="select-wrap select-wrap2">
                                                <select name="billing_country" id="billing_country" class="text_field">
                                                    <option value="">Select one</option>
                                                    <option value="1" @if ($countryId == 1)selected @endif>Bangladesh</option>
                                                    <option value="2" @if ($countryId == 2)selected @endif>India</option>
                                                    <option value="3" @if ($countryId == 3)selected @endif>Uruguye</option>
                                                    <option value="4" @if ($countryId == 4)selected @endif>Australia</option>
                                                    <option value="5" @if ($countryId == 5)selected @endif>Neverland</option>
                                                    <option value="6" @if ($countryId == 6)selected @endif>Atlantis</option>
                                                </select>
                                                <span class="lnr lnr-chevron-down"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="address1">Address Line 1</label>
                                            {{ Form::text('billing_address_line_1', isset($userDetail->userDetail->billing_address_line_1)?$userDetail->userDetail->billing_address_line_1:'', array(
                                                                'id' => 'billing_address_line_1',
                                                                'placeholder' => 'Address line one',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                        </div>

                                        <div class="form-group">
                                            <label for="address2">Address Line 2</label>
                                            {{ Form::text('billing_address_line_2', isset($userDetail->userDetail->billing_address_line_2)?$userDetail->userDetail->billing_address_line_2:'', array(
                                                                'id' => 'billing_address_line_2',
                                                                'placeholder' => 'Address line two',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="city">City / State
                                                        <sup>*</sup>
                                                    </label>
                                                    {{ Form::text('billing_city', isset($userDetail->userDetail->billing_city)?$userDetail->userDetail->billing_city:'', array(
                                                                'id' => 'billing_city',
                                                                'placeholder' => 'City',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                                    {{-- <div class="select-wrap select-wrap2">
                                                        <select name="billing_city" id="billing_city" class="text_field">
                                                            <option value="">Select one</option>
                                                            <option value="dhaka">Dhaka</option>
                                                            <option value="sydney">Sydney</option>
                                                            <option value="newyork">New York</option>
                                                            <option value="london">London</option>
                                                            <option value="mexico">New Mexico</option>
                                                        </select>
                                                        <span class="lnr lnr-chevron-down"></span>
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="zipcode">Zip / Postal Code
                                                        <sup>*</sup>
                                                    </label>
                                                    {{ Form::text('billing_postal_code', isset($userDetail->userDetail->billing_postal_code)?$userDetail->userDetail->billing_postal_code:'', array(
                                                                'id' => 'billing_postal_code',
                                                                'placeholder' => 'zip/postal code',
                                                                'class' => 'text_field',
                                                                'required' => 'required'
                                                                )) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end /.information__set -->
                            </div>
                            <!-- end /.information_module -->
                            <div class="information_module">
                                <a class="toggle_title" href="#collapse3" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapse1">
                                    <h4>Profile Image
                                        <span class="lnr lnr-chevron-down"></span>
                                    </h4>
                                </a>
                                @if ($userDetail->profile_pic != null)
                                    @php
                                    $profileImg = URL:: asset('images').'/site/authplc.png';
                                    if (file_exists(public_path('/uploads/users/thumbs/'.$userDetail->profile_pic))) {
                                        $profileImg = URL::to('/').'/uploads/users/thumbs/'.$userDetail->profile_pic;
                                    }
                                    @endphp
                                @endif
                                <div class="information__set profile_images toggle_module collapse" id="collapse3">
                                    <div class="information_wrapper">
                                        <div class="profile_image_area flx-area">
                                            <div class="crclimg"><img src="{{$profileImg}}" id="blah" alt="Author profile area"></div>
                                            <div class="img_info">
                                                <p class="bold">Profile Image</p>
                                                <p class="subtitle">JPG, GIF or PNG 100x100 px</p>
                                            </div>

                                            <label for="cover_photo" class="upload_btn">
                                            <input type="file" name="cover_photo" id="cover_photo" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                <span class="btn btn--sm btn--round" aria-hidden="true">Upload Image</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end /.information_module -->

                           
                        </div>
                        <!-- end /.col-md-6 -->

                        <div class="col-md-12">
                            <div class="dashboard_setting_btn">
                                <button type="submit" class="btn btn--round btn--md">Save Change</button>
                            </div>
                        </div>
                        <!-- end /.col-md-12 -->
                    </div>
                    <!-- end /.row -->
                </form>
                <!-- end /form -->
            </div>
            <!-- end /.container -->
        </div>
        <!-- end /.dashboard_menu_area -->
    </section>
    <!--================================
            END DASHBOARD AREA
    =================================-->
    
  @endsection