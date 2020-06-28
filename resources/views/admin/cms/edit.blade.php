@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.CMS.list')}}"><i class="fa fa-database" aria-hidden="true"></i> CMS List</a></li>
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
                                    'route' => ['admin.CMS.editsubmit', $details->id],
                                    'name'  => 'updateCmsForm',
                                    'id'    => 'updateCmsForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Name">Name<span class="red_star">*</span></label>
                                    {{ Form::text('name', $details->name, array(
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Name',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="NameArabic">Title<span class="red_star">*</span></label>
                                    {{ Form::text('title', $details->title, array(
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Name',
                                                                'required' => 'required')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Name">Description<span class="red_star">*</span></label>
                                    {{ Form::textarea('description', $details->description, array(
                                                                                                'id'=>'description',
                                                                                                'class' => 'form-control',
                                                                                                )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="NameArabic">Description 2</label>
                                    {{ Form::textarea('description2', $details->description2, array(
                                                                                                'id'=>'description2',
                                                                                                'class' => 'form-control',
                                                                                                'required' => 'required')) }}
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
                                                                'placeholder' => 'Upload Image',))
                                                                }}
                                </div>
                                {{-- <span>Select file dimensions {{AdminHelper::ADMIN_CMS_THUMB_IMAGE_WIDTH}}px X {{AdminHelper::ADMIN_CMS_THUMB_IMAGE_HEIGHT}}px</span> --}}
                            @if($details['image'])
                                <div class="form-group">						        
							    @if(file_exists(public_path('/uploads/cms/'.$details->image))) 
								    <embed src="{{ asset('uploads/cms/'.$details->image) }}"  height=50 />
                                @endif
                                </div>
						    @endif
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Page Banner</label><br>
                                    {{ Form::file('page_banner', array(
                                                                'id' => 'page_banner',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Page Banner Image')) }}
                                </div>
                            @if($details->page_banner)
                                <div class="form-group">						
							    @if(file_exists(public_path('/uploads/cms/'.$details->page_banner))) 
								    <embed src="{{ asset('uploads/cms/'.$details->page_banner) }}"  height=50 />
							    @endif
					            </div>
                            @endif
                            </div> --}}
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Name">Meta Title<span class="red_star">*</span></label>
                                    {{ Form::text('meta_title', $details->meta_title, array(
                                                                'class' => 'form-control' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Name">Meta Keyword<span class="red_star">*</span></label>
                                    {{ Form::text('meta_keyword', $details->meta_keyword, array(
                                                                'class' => 'form-control' )) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="NameArabic">Meta Description<span class="red_star">*</span></label>
                                    {{ Form::textarea('meta_description', $details->meta_description, array(
                                                                'style'=>'height:100px;',
                                                                'class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                    </div>                
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.CMS.list') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
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
        CKEDITOR.replace('description2');
    })
</script>
@endsection