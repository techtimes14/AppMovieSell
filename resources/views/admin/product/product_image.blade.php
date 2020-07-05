@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')

@php
$countProductImage = 0;
if ($products->productImage != null) {
  $countProductImage = count($products->productImage);
}
@endphp

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.product.list')}}"><i class="fa fa-list-alt" aria-hidden="true"></i> Product List</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                @include('admin.elements.notification')
                
				<div class="box-body">
					<div class="row">                            
						<form action="{{ route('admin.product.store',$products->id)}}" enctype="multipart/form-data" class="dropzone" id="dropzone" method="POST">
							@csrf
							<div class="fallback">
              					<input name="file" type="files" multiple accept="image/jpeg, image/png, image/jpg" />
            				</div>
						</form>
            <span>Select file dimensions {{AdminHelper::ADMIN_PRODUCT_SLIDER_IMAGE_WIDTH}}px X {{AdminHelper::ADMIN_PRODUCT_SLIDER_IMAGE_HEIGHT}}px</span><br />
            <div class="form-group defultImageMessage">
              &nbsp;
            </div>
						<div class="row">
            	@if($countProductImage != 0)
              @foreach($products->productImage as $images)
              <div class="col-lg-2 col-6 image_wrap">
                @if(file_exists(public_path('/uploads/product/list_thumbs/'.$images->image)))
                    {!! '<img class="img-thumbnail img-fluid" src="' . URL::to('/') . '/uploads/product/list_thumbs/' . $images->image . '" >' !!}
                    @if($images->default_image == 'Y')
                      {{ Form::radio('default_image', 'Y' , true, array('style'=>'cursor: pointer;','class'=>'default_img image_hidden_id','data-id'=>base64_encode($images->id),'data-name'=>base64_encode($products->id))) }}
                    @else
                      {{ Form::radio('default_image', 'N' , false, array('style'=>'cursor: pointer;','class'=>'default_img image_hidden_id','data-id'=>base64_encode($images->id),'data-name'=>base64_encode($products->id))) }}
                    @endif

                    <a onclick="return sweetalertMessageRender(this, 'Are you sure you want to delete?', 'warning',  true)" href="javascript:void(0)" title="Delete" data-href="{{ route('admin.product.delete-product-image', [$images->id, $images->product_id]) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>

                @else
                    {!! '<img style="width:200px; height: 200px;" class="img-thumbnail img-fluid" src="' . URL::to('/') . '/images/no_images.png" >' !!}
                    @if($images->default_image == 'Y')
                      {{ Form::radio('default_image', 'Y' , true, array('style'=>'cursor: pointer;','class'=>'default_img','data-id'=>base64_encode($images->id),'data-name'=>base64_encode($products->id))) }}
                    @else
                      {{ Form::radio('default_image', 'N' , false, array('style'=>'cursor: pointer;','class'=>'default_img','data-id'=>base64_encode($images->id),'data-name'=>base64_encode($products->id))) }}
                    @endif

                    <a onclick="return sweetalertMessageRender(this, 'Are you sure you want to delete?', 'warning',  true)" href="javascript:void(0)" title="Delete" data-href="{{ route('admin.product.delete-product-image', [$images->id, $images->product_id]) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                @endif
              </div>
              @endforeach
            	@endif
          	</div>
					</div>
				</div>						
				<div class="box-footer">
					<div class="col-md-6">
						<a href="{{ route('admin.product.list').'?page='.$pageNo }}" title="Back" class="btn btn-block btn-default btn_width_reset">Back</a>
					</div>
				</div>
                
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<link rel="stylesheet" href="{{asset('css/admin/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('css/admin/basic.css')}}">
<script src="{{asset('js/admin/dropzone.js')}}"></script>

<style type="text/css">
    .dropzone {
        border:2px dashed #999999;
        border-radius: 10px;
    }
    .dropzone .dz-default.dz-message {
        height: 171px;
        background-size: 132px 132px;
        margin-top: -101.5px;
        background-position-x:center;

    }
    .dropzone .dz-default.dz-message span {
        display: block;
        margin-top: 145px;
        font-size: 20px;
        text-align: center;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function(){ $(".font-weight-light").hide(); }, 6000);

        $('.defultImageMessage').hide();

        $("input[name=default_image]").on('change', function() {
            var image_id = $(this).attr('data-id');
            var product_id = $(this).attr('data-name');
            var ajax_url = '{{ route("admin.product.make_default_image") }}';
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                url: ajax_url,
                method: 'post',
                data: {
                    image_id: image_id,
                    product_id: product_id,
                },
                success: function(data){
                    if(data == 1){
                      $('.defultImageMessage').html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Default image successfully updated.</div>');
                      $('.defultImageMessage').show();
                    }else{
                      $('.defultImageMessage').html('<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Danger!</strong> Error in processing request for update default image.</div>');
                      $('.defultImageMessage').show();
                    }
                    setTimeout(function(){ $('.defultImageMessage').hide(); }, 4000);
                }
            });
        });
    });

    Dropzone.options.dropzone =
    {
        maxFiles: '<?php echo (AdminHelper::ADMIN_PRODUCT_MAX_NUMBER_OF_IMAGE_UPLOAD - $countProductImage); ?>',
        maxThumbnailFilesize: '<?php echo (AdminHelper::ADMIN_PRODUCT_MAX_NUMBER_OF_IMAGE_UPLOAD - $countProductImage); ?>',
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
            var file_name = file.name.split('.');
            var extension = file_name[1];
           	return 'product-'+(Math.floor(Math.random() * 999999) + 100000)+time+'.'+extension;
        },
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        addRemoveLinks: true,
        timeout: 50000,
        removedfile: function(file)
        {
            var name = file.upload.filename;

            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.product.image-delete") }}',
                data: {filename: name},
                success: function (data){
                    console.log("File has been successfully removed!!");
                },
                error: function(e) {
                    console.log(e);
                }});
                var fileRef;
                return (fileRef = file.previewElement) != null ?
                fileRef.parentNode.removeChild(file.previewElement) : void 0;
        },
        init: function () {
          var totalFiles = 0,
          completeFiles = 0;
          this.on("addedfile", function (file) {
              totalFiles += 1;
          });
          this.on("removed file", function (file) {
              totalFiles -= 1;
          });
          this.on("complete", function (file) {
              completeFiles += 1;
              if (completeFiles === totalFiles) {
                //   location.reload();
              }
          });

          this.on("maxfilesexceeded", function(file){
							// alert("Add upto 5 images.");
						swal.fire({
							title: 'Maximum Limit',
							text: 'You can able to upload upto {{AdminHelper::ADMIN_PRODUCT_MAX_NUMBER_OF_IMAGE_UPLOAD}} images',
							icon: 'warning',
							allowOutsideClick: false,
							confirmButtonColor: '#1279cf',
							cancelButtonColor: '#333333',
							showCancelButton: false,
							confirmButtonText: 'Ok',
							// closeOnConfirm: false,
						});
              this.removeFile(file);
          });
        },
        success: function(file, response)
        {
            // if(response.success){
            //   location.reload();
            // }
            console.log(response);
        },
        error: function(file, response)
        {
           return false;
        }
    };
</script>

@endsection