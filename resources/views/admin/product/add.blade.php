@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')

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
                
                {{ Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.product.addsubmit'],
                                    'name'  => 'addProductForm',
                                    'id'    => 'addProductForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Category<span class="red_star">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control" value="{{old('category_id')}}">
                                        <option value="">-Select-</option>
                                @if (count($categoryList))
                                    @foreach ($categoryList as $category)
                                        <option value="{{$category->id}}" @if($category->id == old('category_id') ) selected="selected" @endif>{{$category->title}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            
                        </div>

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
                                        <label for="price">Price<span class="red_star">*</span></label><br>
                                        {{ Form::text('price',null, array(  'id' => 'price',
                                                                            'class' => 'form-control',
                                                                            'placeholder' => 'Price',
                                                                            'required' => 'required' )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description<span class="red_star">*</span></label>
                                    {{ Form::textarea('description', null, array(
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
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="title">Label</label>
                                     {{ Form::text('feature_label[]', null, array(
                                                                'id' => '',
                                                                'placeholder' => 'Label',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                                <div class="col-md-7">
                                    <label for="title">Value</label>
                                     {{ Form::textarea('feature_value[]', null, array(
                                                                'id' => '',
                                                                'placeholder' => 'Value',
                                                                'rows' => 2,
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                                <div class="col-md-2">
                                    <label for="title">&nbsp;</label><br />
                                    <button class="btn btn-success add-more" id="addrow" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>                        
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.product.list') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
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
    // Attribute section start //
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
    // Attribute section end //

   
        CKEDITOR.replace('description');
        
        
});
</script>

@endsection