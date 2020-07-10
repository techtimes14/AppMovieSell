@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.category.list')}}"><i class="fa fa-book" aria-hidden="true"></i> Category List</a></li>
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
                                    'route' => ['admin.category.editsubmit', $details["id"]],
                                    'title'  => 'editCategoryForm',
                                    'id'    => 'editCategoryForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title<span class="red_star">*</span></label>
                                    {{ Form::text('title', $details->title, array(
                                                                'id' => 'title',
                                                                'placeholder' => 'Title',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="allow_format">Allow Format<span class="red_star">*</span></label>
                                    {{ Form::text('allow_format', $details->allow_format, array(
                                                                'id' => 'allow_format',
                                                                'placeholder' => 'Allow Format',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                        <label for="image">Image</label><br>
                                        
                                        {{ Form::file('image', array(
                                                                    'id' => 'image',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Upload Image',
                                                                     )) }}
                                </div>
                                
                                @if ($details->image != null)
                                <div class="form-group">
							     @if(file_exists(public_path('/uploads/category/'.$details->image))) 
								    <embed src="{{ asset('uploads/category/'.$details->image) }}"  height=50 />
							     @endif
					           </div>
					           @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" title="Submit">Update</button>
                            <a href="{{ route('admin.category.list').'?page='.$data['pageNo'] }}" title="Cancel" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection