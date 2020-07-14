@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.product.list')}}"><i class="fa fa-dropbox" aria-hidden="true"></i> Product List</a></li>
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
                                    'route' => ['admin.product.editsubmit', $details["id"]],
                                    'title'  => 'editProductForm',
                                    'id'    => 'editProductForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Category<span class="red_star">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control select2" value="{{old('category_id')}}">
                                        <option value="">-Select-</option>
                                @if (count($categoryList))
                                    @foreach ($categoryList as $category)
                                        <option value="{{$category->id}}" @if($category->id == $details['category_id']) selected="selected" @endif>{{$category->title}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
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
                            
                        </div>

                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Price<span class="red_star">*</span></label><br>
                                    {{ Form::text('price', $details->price, array(
                                                                'id' => 'price',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Price',
                                                                    )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Is Feature</label><br>
                                    <input  type="checkbox" name="is_feature" value="1" @if($details['is_feature']) checked="checked" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description<span class="red_star">*</span></label>
                                    {{ Form::textarea('description', $details->description, array(
                                                                'id' => 'description',
                                                                'rows' => 6,
                                                                'placeholder' => 'Description',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                        </div>
                        <div class="addField">
                        @foreach ($details->features as $key => $feature)
                                       
                            <div class="row featured_div_{{$feature->id}}">
                                <div class="col-md-3">
                                @if ($key == 0)
                                    <label for="title">Label</label>
                                @endif
                                    {{ Form::text('feature_label[]', $feature->feature_label, array(
                                                            'placeholder' => 'Label',
                                                            'class' => 'form-control',
                                                            'required' => 'required'
                                                                )) }}
                                </div>
                                    <div class="col-md-7">
                                    @if ($key == 0)
                                        <label for="title">Value</label>
                                    @endif
                                            {{ Form::textarea('feature_value[]', $feature->feature_value, array(
                                                            'placeholder' => 'Value',
                                                            'rows' => 2,
                                                            'class' => 'form-control',
                                                            'required' => 'required'
                                                                )) }}
                                    </div>
                                <div class="col-md-2">
                                @if ($key == 0)
                                    <label for="title">&nbsp;</label><br />
                                @endif
                                    <button class="btn btn-danger feature_move_to_trash" data-featureid="{{$feature->id}}" type="button"><i class="fa fa-trash"></i></button>
                                @if ($key == 0)
                                    <button class="btn btn-success add-more" id="addrow" type="button"><i class="fa fa-plus"></i></button>
                                @endif
                                </div>
                            </div>
                            <br></br>
                        
                        @endforeach
                        </div>
                        </div>
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" title="Submit">Update</button>
                            <a href="{{ route('admin.product.list').'?page='.$data['pageNo'] }}" title="Cancel" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<script type="text/javascript">
$(function () {
    // Productfeature section start //
    var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
        var cols = '';
        var newRow = $('<div class="row" style="margin-top: 10px;">');
        cols += '<div class="col-md-3"><input placeholder="Label" class="form-control" required="required" name="feature_label[]" type="text"></div>';
        cols += '<div class="col-md-7"><textarea placeholder="Value" class="form-control" required="required" name="feature_value[]"></textarea></div>';
        cols += '<div class="col-md-2"><a class="deleteRow btn btn-danger ibtnDel" href="javascript: void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a></div>';

        newRow.append(cols);
        $(".addField").append(newRow);
       
    });
    $(".row").on("click", ".ibtnDel", function (event) {
        $(this).closest(".row").remove();
        counter--;
    });
    // Productfeature section end //

    // Delete feature start //
    $('.feature_move_to_trash').on('click', function() {
        
        var productFeatureId = $(this).data('featureid');
        
        Swal.fire({
			title: 'Warning',
            text: 'Are you sure you want to delete?',
            icon: 'warning',
            allowOutsideClick: false,
            // confirmButtonColor: '#d6f55b',
            // cancelButtonColor: '#141516',
            showCancelButton: true,
            cancelButtonText: 'Cancle',
            confirmButtonText: 'Yes',
		}).then((result) => {
			if (result.value) {
				var deleteFeatureUrl = '{{route('admin.product.delete-product-feature')}}';
                $('#whole-area').show();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: deleteFeatureUrl,
                    method: 'POST',
                    data: {
                        product_feature_id: productFeatureId,
                    },
                    dataType: 'json',
                    success: function (response) {
                        if(response.type == 'success') {
                            $('.featured_div_'+productFeatureId).remove();
                            $('#whole-area').hide(); // Hide loader
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                icon: response.type,
                                showCancelButton: false,
                                // confirmButtonColor: '#d6f55b',
                                confirmButtonText: "Ok",
                                cancelButtonText: "",
                                closeOnConfirm: true,
                                closeOnCancel: false
                            });                            
                        } else{
                            $('#whole-area').hide(); // Hide loader
                            Swal.fire({
                                title: response.title,
                                text: response.message,
                                icon: response.type,
                                showCancelButton: false,
                                // confirmButtonColor: '#d6f55b',
                                confirmButtonText: "Ok",
                                cancelButtonText: "",
                                closeOnConfirm: true,
                                closeOnCancel: false
                            });
                        }
                    }
                });
			}
		});


    });
    // Delete Feature end //
    CKEDITOR.replace('description');
});
</script>

@endsection