@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.package.list')}}"><i class="fa fa-dropbox"></i> Pakage List</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                
                @include('admin.elements.notification')

                {{ Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.package.addsubmit'],
                                    'name'  => 'addPackageForm',
                                    'id'    => 'addPackageForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title<span class="red_star">*</span></label>
                                    {{ Form::text('title', null, array(
                                                                'id' => 'title',
                                                                'placeholder' => 'Title',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="TitleArabic">Title (Arabic)<span class="red_star">*</span></label>
                                    {{ Form::text('title_ar', null, array(
                                                                'id' => 'title_ar',
                                                                'class' => 'form-control',
                                                                'dir' => 'rtl',
                                                                'placeholder' => 'Title',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Name">Description (English)<span class="red_star">*</span></label>
                                    {{ Form::textarea('description_en', null, array(
                                                                'id'=>'description_en',
                                                                'class' => 'form-control',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="NameArabic">Description (Arabic)<span class="red_star">*</span></label>
                                    {{ Form::textarea('description_ar', null, array(
                                                                'id'=>'description_ar',
                                                                'class' => 'form-control',
                                                                'required' => 'required')) }}
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Period<span class="red_star">*</span></label>
                                    <select name="package_period_id" id="package_period_id" class="form-control">
                                        <option value="">-Select-</option>
                                @if (count($packagePeriodList))
                                    @foreach ($packagePeriodList as $packagePeriod)
                                        <option value="{{$packagePeriod->id}}">{{$packagePeriod->title}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">                                    
                                    <label for="Amount">Amount<span class="red_star">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            SAR
                                        </div>
                                        {{ Form::text('amount', null, array(
                                                                        'id' => 'amount',
                                                                        'min' => 0,
                                                                        'placeholder' => 'Amount',
                                                                        'class' => 'form-control',
                                                                        'required' => 'required'
                                                                        )) }}
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gracePeriod">Grace Period<span class="red_star">*</span></label>
                                    <select name="grace_period" id="grace_period" class="form-control">
                                        <option value="">-Select-</option>
                                    @for($i=0; $i<=20; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                    </select>
                                    <small>Number of days</small>
                                    <div id="period_error_message"></div>
                                </div>                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="allowUser">Allow User<span class="red_star">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        {{ Form::text('allow_user', null, array(
                                                                'id' => 'allow_user',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Number of users',
                                                                'required' => 'required' )) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.package.list') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<script>
$(function () {
    CKEDITOR.replace('description_en');
    CKEDITOR.replace('description_ar', {
        language : 'ar'
    });
});
</script>

@endsection