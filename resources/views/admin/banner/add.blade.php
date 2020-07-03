@extends('admin.layouts.app', ['title' => $panel_title])
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.banner.list')}}"><i class="fa fa-picture-o"></i> Banner List</a></li>
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
                                    'route' => ['admin.banner.addsubmit'],
                                    'name'  => 'addBannerForm',
                                    'id'    => 'addBannerForm',
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
                                <div class="form-group">
                                    <label for="UploadBannerWeb">Image<span class="red_star">*</span></label><br>                                        
                                    {{ Form::file('image', array(
                                                                'id' => 'image',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Image',
                                                                'required' => 'required' )) }}
                                </div>
                                <span>Select file dimensions {{AdminHelper::ADMIN_BANNER_THUMB_IMAGE_WIDTH}}px X {{AdminHelper::ADMIN_BANNER_THUMB_IMAGE_HEIGHT}}px</span>
                                
                                
                            </div>
                            
                        </div>                    
                        
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                               <a class="btn btn-block btn-default btn_width_reset" href="{{route('admin.banner.list')}}">Cancel</a>
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