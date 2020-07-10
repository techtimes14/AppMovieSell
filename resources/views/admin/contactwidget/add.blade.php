@extends('admin.layouts.app', ['title' => $panel_title])
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.contactwidget.list')}}"><i class="fa fa-picture-o"></i> Contact Widget List</a></li>
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
                                    'route' => ['admin.contactwidget.addsubmit'],
                                    'name'  => 'addContactwidgetForm',
                                    'id'    => 'addContactwidgetForm',
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
                                        <label for="UploadBannerWeb">Icon (class)<span class="red_star">*</span></label><br>                                        
                                        {{ Form::text('icon_class',null, array(
                                                                'id' => 'icon_class',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Icon',
                                                                'required' => 'required' )) }}
                                    </div>
                                    
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description<span class="red_star">*</span></label>
                                    {{ Form::textarea('description', null, array(
                                                                'id'=>'description',
                                                                'class' => 'form-control',
                                                                'rows' => 4,
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                        </div>                    
                        
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                               <a class="btn btn-block btn-default btn_width_reset" href="{{route('admin.contactwidget.list')}}">Cancel</a>
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