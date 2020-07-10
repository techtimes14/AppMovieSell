@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.package.list')}}"><i class="fa fa-dropbox"></i> Pakage List</a></li>
        <li class="active">{{ $data['page_title'] }}</li>
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
                                    'route' => ['admin.package.editsubmit', $details["id"]],
                                    'name'  => 'updatePackageForm',
                                    'id'    => 'updatePackageForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Title">Title (English)<span class="red_star">*</span></label>
                                    {{ Form::text('title',$details['title'], array(
                                                                'id' => 'title',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Title',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="TitleArabic">Title (Arabic)<span class="red_star">*</span></label>
                                    {{ Form::text('title_ar', $details->local[1]['local_title'],array(
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
                                    {{ Form::textarea('description_en', $details->local[0]['local_description'], array(
                                                                'id'=>'description_en',
                                                                'class' => 'form-control',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="NameArabic">Description (Arabic)<span class="red_star">*</span></label>
                                    {{ Form::textarea('description_ar', $details->local[1]['local_description'], array(
                                                                'id'=>'description_ar',
                                                                'class' => 'form-control',
                                                                'required' => 'required')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gracePeriod">Grace Period<span class="red_star">*</span></label>
                                    <select name="grace_period" id="grace_period" class="form-control">
                                        <option value="">-Select-</option>
                                    @for($i=0; $i<=20; $i++)
                                        <option value="{{$i}}" @if ($i == old('grace_period', $details->grace_period))
                                            selected="selected"
                                        @endif >{{$i}}</option>
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
                                        {{ Form::text('allow_user', $details['allow_user'], array(
                                                                'id' => 'allow_user',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Number of users',
                                                                'required' => 'required' )) }}
                                    </div>
                                </div>
                            </div>
                        </div>                 
                    </div> 
                        {{-- Add field for product description --}}
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.package.list').'?page='.$data['pageNo'] }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
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