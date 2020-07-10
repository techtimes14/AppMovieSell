@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ $data['page_title'] }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit</h3>
                </div>

                @include('admin.elements.notification')

                {{ Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.site-settings'],
                                    'name'  => 'updateSiteSettingsForm',
                                    'id'    => 'updateSiteSettingsForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="FirstName">From Email<span class="red_star">*</span></label>
                                    {{ Form::text('from_email', $data['from_email'], array(
                                                                'id' => 'from_email',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'From Email',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="LastName">To Email<span class="red_star">*</span></label>
                                    {{ Form::text('to_email', $data['to_email'], array(
                                                                'id' => 'to_email',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'To Email',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Website Title For Email<span class="red_star">*</span></label>
                                    {{ Form::text('website_title', $data['website_title'], array(
                                                                'id' => 'website_title',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Website Title',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Website Link<span class="red_star">*</span></label>
                                    {{ Form::text('website_link', $data['website_link'], array(
                                                                'id' => 'website_link',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Website Link',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Facebook Link</label>
                                    {{ Form::text('facebook_link', $data['facebook_link'], array(
                                                                'id' => 'facebook_link',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Facebook Link')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="LinkedInLink">LinkedIn Link</label>
                                    {{ Form::text('linkedin_link', $data['linkedin_link'], array(
                                                                'id' => 'linkedin_link',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'LinkedIn Link' )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="YouTubeLink">YouTube Link</label>
                                    {{ Form::text('youtube_link', $data['youtube_link'], array(
                                                                'id' => 'youtube_link',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'YouTube Link' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="GooglePlusLink">Google Plus Link</label>
                                    {{ Form::text('googleplus_link', $data['googleplus_link'], array(
                                                                'id' => 'googleplus_link',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Google Plus Link' )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="TwitterLink">Twitter Link</label>
                                    {{ Form::text('twitter_link', $data['twitter_link'], array(
                                                                'id' => 'twitter_link',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Twitter Link' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Instagram Link</label>
                                    {{ Form::text('instagram_link', $data['instagram_link'], array(
                                                                'id' => 'instagram_link',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Instagram Link' )) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Pinterest Link</label>
                                    {{ Form::text('pinterest_link', $data['pinterest_link'], array(
                                                                'id' => 'pinterest_link',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Pinterest Link' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Rss Link</label>
                                    {{ Form::text('rss_link', $data['rss_link'], array(
                                                                'id' => 'rss_link',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Rss Link' )) }}
                                </div>
                            </div>                            
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Default Meta Title</label>
                                    {{ Form::text('default_meta_title', $data['default_meta_title'], array(
                                                                'id' => 'default_meta_title',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Default Meta Title' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Default Meta Keywords</label>
                                    {{ Form::textarea('default_meta_keywords', $data['default_meta_keywords'], array(
                                                'id' => 'default_meta_keywords',
                                                'class' => 'form-control',
                                                'rows' => 4,
                                                'cols' => 4,
                                                'placeholder' => 'Default Meta Keywords' )) }}
                                </div>
                            </div>                            
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Default Meta Description</label>
                                    {{ Form::textarea('default_meta_description', $data['default_meta_description'], array(
                                                                'id' => 'default_meta_description',
                                                                'class' => 'form-control',
                                                                'rows' => 4,
                                                                'cols' => 4,
                                                                'placeholder' => 'Default Meta Description' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Address</label>
                                    {{ Form::textarea('address', $data['address'], array(
                                                                'id' => 'address',
                                                                'class' => 'form-control',
                                                                'rows' => 4,
                                                                'placeholder' => 'Address' )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Phone Number</label>
                                    {{ Form::text('phone_no', $data['phone_no'], array(
                                                                'id' => 'phone_no',
                                                                'class' => 'form-control',
                                                                'rows' => 4,
                                                                'placeholder' => 'Phone Number' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Footer Short Description</label>
                                    {{ Form::textarea('home_short_description', $data['home_short_description'], array(
                                                                'id' => 'home_short_description',
                                                                'class' => 'form-control',
                                                                'rows' => 4,
                                                                'placeholder' => 'Footer Short Description' )) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection