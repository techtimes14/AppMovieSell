@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.service.list')}}"><i class="fa fa-cubes"></i> Service List</a></li>
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
                                    'route' => ['admin.service.editsubmit', $details["id"]],
                                    'title'  => 'editServiceForm',
                                    'id'    => 'editServiceForm',
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
                                    <label for="image">Upload Image</label><br>
                                        {{ Form::file('image', array(
                                                                    'id' => 'image',
                                                                    'class' => 'form-control',
                                                                    'placeholder' => 'Upload Image',
                                                                     )) }}
                                </div>
                                <span>Select file dimensions {{AdminHelper::ADMIN_SERVICE_THUMB_IMAGE_WIDTH}}px X {{AdminHelper::ADMIN_SERVICE_THUMB_IMAGE_HEIGHT}}px</span>
                                @if($details->image)
                                <div class="form-group">
						            @if ($details->image != null)
							            @if(file_exists(public_path('/uploads/service/'.$details->image))) 
								            <embed src="{{ asset('uploads/service/'.$details->image) }}"  height=50 />
							            @endif
						            @endif
					            </div>
					            @endif
                            </div>
                        </div>

                        <div class="row">
                           
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description<span class="red_star">*</span></label>
                                    {{ Form::textarea('description', $details->description, array(
                                                'id'=>'description',
                                                'rows' => 6,
                                                'class' => 'form-control',
                                                'required' => 'required')) }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.service.list').'?page='.$data['pageNo'] }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
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
        CKEDITOR.replace('description');
    })
</script>
@endsection