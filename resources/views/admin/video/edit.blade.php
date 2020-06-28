@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.video.list')}}"><i class="fa fa-video-camera" aria-hidden="true"></i> Video List</a></li>
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
                                    'route' => ['admin.video.editsubmit', $data["id"]],
                                    'title'  => 'editVideoForm',
                                    'id'    => 'editVideoForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title<span class="red_star">*</span></label>
                                    {{ Form::text('title', $data['details']['title'], array(
                                                                'id' => 'title',
                                                                'placeholder' => 'Title',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Video  Url<span class="red_star">*</span></label>
                                    {{ Form::text('video_url', $data['details']['video_url'], array(
                                                                'id' => 'video_url',
                                                                'placeholder' => 'Video Url',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">                            
                                <div class="form-group">                                    
                                    <label for="categories">Category(s)<span class="red_star">*</span></label>
                                    <select name="categories[]" id="catgories" multiple="multiple" required class="form-control select2">
                                    @foreach ($data['categoryList'] as $keyCategory => $valCategory)
                                        <option value="{{$keyCategory}}" @if (in_array($keyCategory, $data['categoryIds']))selected @endif>{{$valCategory}}</option>
                                    @endforeach
                                    </select>                                    
                                </div>                            
                            </div>
                            <div class="col-md-6">                            
                                <div class="form-group">                                    
                                    <label for="tags">Tag(s)<span class="red_star">*</span></label>
                                    <select name="tags[]" id="tags" multiple="multiple" required class="form-control select2">
                                    @foreach ($data['tagList'] as $keyTag => $valTag)
                                        <option value="{{$keyTag}}" @if (in_array($keyTag, $data['tagIds']))selected @endif>{{$valTag}}</option>
                                    @endforeach
                                    </select>                                    
                                </div>                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">                            
                                <div class="form-group">                                    
                                    <label for="brands">Brand(s)<span class="red_star">*</span></label>
                                    <select name="brands[]" id="brands" multiple="multiple" required class="form-control select2">
                                    @foreach ($data['brandList'] as $keyBrand => $valBrand)
                                        <option value="{{$keyBrand}}" @if (in_array($keyBrand, $data['brandIds']))selected @endif>{{$valBrand}}</option>
                                    @endforeach
                                    </select>                                    
                                </div>                            
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" title="Submit">Update</button>
                            <a href="{{ route('admin.video.list').'?page='.$data['pageNo'] }}" title="Cancel" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection