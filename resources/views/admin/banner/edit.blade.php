@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.banner.list')}}"><i class="fa fa-picture-o"></i> Banner List</a></li>
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
                                    'route' => ['admin.banner.editsubmit', $details["id"]],
                                    'title'  => 'updateBannerForm',
                                    'id'    => 'updateBannerForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Title<span class="red_star">*</span></label>
                                {{ Form::text('title', $details["title"], array(
                                                            'id' => 'title',
                                                            'placeholder' => 'Title',
                                                            'class' => 'form-control',
                                                            'required' => 'required'
                                                             )) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="UploadBannerWeb">Image</label><br>
                                {{ Form::file('image', array(
                                                            'id' => 'image',
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Image' )) }}
                            </div>
                            <span>Select file dimensions {{AdminHelper::ADMIN_BANNER_THUMB_IMAGE_WIDTH}}px X {{AdminHelper::ADMIN_BANNER_THUMB_IMAGE_HEIGHT}}px</span>

                            <div class="form-group">
                                @if ($details['image'] != null)
                                    @if(file_exists(public_path('/uploads/banner/'.$details['image']))) 
                                        <embed src="{{ asset('uploads/banner/'.$details['image']) }}" width="100px" />
                                    @endif
                                @endif
                            </div>


                            
                        </div>
                        
                </div>
                <div class="box-footer">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('admin.banner.list').'?page='.$data['pageNo'] }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection