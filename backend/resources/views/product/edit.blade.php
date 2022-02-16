@extends('layout')

@section('content')
    <main class="pb-3 pb-md-5" role="main" style=""
          x-data="{language: '{{session('language')}}', status: {{($entity->is_publish) ? $entity->is_publish : 0}}}">
        <div class="container container--crud mb-3 mb-md-0">
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <img src="{{asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
                </div>
            @endforeach
            <div class="row justify-content-between">
                <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
                    <h1 class="page-title m-0">
                        {{Lang::get('product.addproduct')}}
                    </h1>
                </div>
                <div class="col-12 col-md">
                    <div class="row justify-content-end">
                        <div class="col-auto">
                            <div class="dropdown">
                                <button aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-default w-100 dropdown-toggle"
                                        data-toggle="dropdown" id="dropdownMenuButton" type="button">
                                    <span x-text="status==0 ? 'Inactive' : 'Active'"></span>
                                </button>
                                <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);" x-on:click="status=1">Active</a>
                                    <a class="dropdown-item" href="javascript:void(0);"
                                       x-on:click="status=0">Inactive</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container container--crud mt-3">
            <form class="form-validate" action="{{route('product.update', $entity->id)}}" method="post"
                  enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card p-0 sticky-top sticky-top--header border-0" style="background: transparent;">
                    <nav class="navbar navbar-expand px-0 px-lg-3 site-nav bg-white">
                        <ul class="navbar-nav nav flex-column flex-md-row w-100" style="overflow-x: auto;">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#product_overview">
                                    Product Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#product_variants">
                                    {{Lang::get('product.itemoptions')}}
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="ml-auto mt-3 pr-md-3 mt-md-n6"
                         x-cloak
                         x-data="{ shown: false }"
                         @scroll.window="window.scrollY > 140 ? shown = true : shown = false"
                         x-show="shown" x-transition>
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="dropdown mr-3">
                                <button aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-default w-100 dropdown-toggle"
                                        data-toggle="dropdown" type="button">
                                    <span x-text="status==0 ? 'Inactive' : 'Active'"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:void(0);"
                                       x-on:click="status=1">Active</a>
                                    <a class="dropdown-item" href="javascript:void(0);"
                                       x-on:click="status=0">Inactive</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="product_overview" role="tabpanel">
                        <div class="card p-0 mt-3">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="text-nowrap mr-3">
                                    <i class="fas fa-fw fa-image"></i>
                                    <h5 class="card-title d-inline-block">
                                        Photo
                                    </h5>
                                </div>
                                <small class="text-color:tertiary font-size:14 text-right">
                                    Image format .jpg or .png at 800 x 600 px, saved for web, 72 dpi.
                                </small>
                            </div>
                            <div class="card-body">
                                @include('components.drop-upload', [
                                    'route' => route('product.upload'),
                                    'images' => json_encode(array_map(function($image){
                                        return [
                                            'filename' => $image['image'],
                                            'images' => [
                                                '175x175' => image_url('175x175',$image['image'])
                                            ]
                                        ];
                                    }, $entity->images->toArray())
                                )])
                            </div>
                        </div>
                        <div class="card p-0 mt-3">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-file-alt"></i>
                                    <h5 class="card-title d-inline-block">
                                        Product Information
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="title">Product Name</label>
                                            <input type="text" class="form-control" name="title" id="title" required
                                                   placeholder="Product Name" value="{{$entity->title}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-uppercase" for="production_cost">
                                            Production Cost (IDR)
                                        </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                            </div>
                                            <input type="tel" class="form-control price text-right" id="production_cost"
                                                   required
                                                   name="default_production_cost"
                                                   value="{{$entity->default_sku->production_cost}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-uppercase" for="fulfillment_cost">
                                            Fulfillment Cost (IDR)
                                        </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                            </div>
                                            <input type="tel" class="form-control price text-right"
                                                   id="fulfillment_cost" required
                                                   name="default_fulfillment_cost"
                                                   value="{{$entity->default_sku->fulfillment_cost}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-uppercase" for="selling_price">
                                            Selling Price
                                        </label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                            </div>
                                            <input type="tel" class="form-control price text-right"
                                                   id="selling_price" required
                                                   name="default_selling_price"
                                                   value="{{$entity->default_sku->selling_price}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="prism_id">Product ID in PRISM</label>
                                            <input type="text" class="form-control" name="prism_id" id="prism_id"
                                                   required
                                                   placeholder="Product ID in PRISM" value="{{$entity->prism_id}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="production_time">Production Time
                                                (days)</label>
                                            <input type="number" class="form-control text-right" name="production_time"
                                                   id="production_time" required
                                                   placeholder="Production Time (days)"
                                                   value="{{$entity->production_time}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="fulfillment_time">
                                                Fulfillment Time (days)
                                            </label>
                                            <input type="number" class="form-control text-right" name="fulfillment_time"
                                                   id="fulfillment_time"
                                                   placeholder="Fulfillment Time (days)" required
                                                   value="{{$entity->fulfillment_time}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="text-uppercase" for="description">Product Description</label>
                                    <textarea class="form-control editor" name="description"
                                              id="description"
                                              placeholder="Write Description Here">{{$entity->description}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="text-uppercase" for="size_chart">Size Chart (optional)</label>
                                    <textarea class="form-control editor" name="size_chart"
                                              id="size_chart">{{$entity->size_chart}}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="weight">{{Lang::get('product.weight')}}
                                                (gram)</label>
                                            <input type="number" class="form-control default" name="default_weight"
                                                   id="default_weight" placeholder="{{Lang::get('product.weight')}}"
                                                   value="{{$entity->default_sku->weight}}"
                                                   data-toggle="tooltip" required
                                                   data-placement="top" title="{{Lang::get('product.youcanweight')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="sku"> Stock Keeping Unit (SKU)
                                                Code</label>
                                            <input type="text" class="form-control default" name="default_sku" id="sku"
                                                   placeholder="SKU" value="{{$entity->default_sku->sku_code}}"
                                                   data-toggle="tooltip"
                                                   data-placement="top" title="{{Lang::get('product.youcansku')}}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="default_stock">
                                                Stock
                                            </label>
                                            <input type="number" class="form-control default" name="default_stock"
                                                   id="default_stock" value="{{$entity->default_sku->stock}}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="width">Width (cm)</label>
                                            <input type="number" class="form-control default" name="default_width"
                                                   id="width" placeholder="{{Lang::get('product.width')}}"
                                                   value="{{$entity->default_sku->width}}"
                                                   data-toggle="tooltip" min="0" data-placement="top" required
                                                   title="{{Lang::get('product.youcanwidth')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="length">Length (CM)</label>
                                            <input type="number" class="form-control default" name="default_length"
                                                   id="length" placeholder="{{Lang::get('product.length')}}"
                                                   value="{{$entity->default_sku->length}}"
                                                   data-toggle="tooltip" min="0" data-placement="top" required
                                                   title="{{Lang::get('product.youcanlength')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="height">Height (CM)</label>
                                            <input type="number" class="form-control default" name="default_height"
                                                   id="height" placeholder="{{Lang::get('product.height')}}"
                                                   value="{{$entity->default_sku->height}}"
                                                   data-toggle="tooltip" data-placement="top" min="0" required
                                                   title="{{Lang::get('product.youcanheight')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card p-0 mt-3">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-dollar-sign"></i>
                                    <h5 class="card-title d-inline-block">
                                        Product Categories
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category_id">
                                                Category
                                            </label>
                                            <select class="custom-select" id="category_id" name="category_id" required>
                                                @foreach(\App\Models\Product\Category::active()->get() as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tokopedie_category_id">
                                                Category in Tokopedia
                                            </label>
                                            <select class="custom-select" id="tokopedie_category_id" required
                                                    name="tokopedie_category_id">
                                                <option selected>Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="desty_category_id">
                                                Category in Desty
                                            </label>
                                            <select class="custom-select" id="desty_category_id" required
                                                    name="desty_category_id">
                                                <option selected>Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="shopee_category_id">
                                                Category in Shopee
                                            </label>
                                            <select class="custom-select" id="shopee_category_id" required
                                                    name="shopee_category_id">
                                                <option selected>Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card p-0 mt-3">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-dollar-sign"></i>
                                    <h5 class="card-title d-inline-block">
                                        Template Information
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="" class="text-uppercase">
                                                Shape
                                            </label>
                                            <div>
                                                @foreach(['square', 'circle'] as $radio)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="shape"
                                                               {{ $entity->shape == $radio ? 'checked' : '' }}
                                                               id="shape_{{ $radio }}" value="{{ $radio }}">
                                                        <label class="form-check-label" for="shape_{{ $radio }}">
                                                            {{ Str::title($radio) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="" class="text-uppercase">
                                                Orientation
                                            </label>
                                            <div>
                                                @foreach(['portrait', 'landscape'] as $radio)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="orientation"
                                                               {{ $entity->orientation == $radio ? 'checked' : '' }}
                                                               id="orientation_{{ $radio }}" value="{{ $radio }}">
                                                        <label class="form-check-label" for="orientation_{{ $radio }}">
                                                            {{ Str::title($radio) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="" class="text-uppercase">
                                                Unit
                                            </label>
                                            <div>
                                                @foreach(['mm', 'cm'] as $radio)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="unit"
                                                               {{ $entity->unit == $radio ? 'checked' : '' }}
                                                               id="unit_{{ $radio }}" value="{{ $radio }}">
                                                        <label class="form-check-label" for="unit_{{ $radio }}">
                                                            {{ Str::title($radio) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="" class="text-uppercase">
                                                Enable Resize?
                                            </label>
                                            <div>
                                                @foreach(['no', 'yes'] as $index => $radio)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="enable_resize"
                                                               {{ $entity->enable_resize == $radio ? 'checked' : '' }}
                                                               id="resize_{{ $radio }}" value="{{ $index }}">
                                                        <label class="form-check-label" for="resize_{{ $radio }}">
                                                            {{ Str::title($radio) }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="text-uppercase" for="bleed">
                                        Bleed
                                    </label>
                                    <textarea class="form-control" name="bleed" id="bleed">{{$entity->bleed}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="text-uppercase" for="safety_line">
                                        Safety Line
                                    </label>
                                    <textarea class="form-control" name="safety_line"
                                              id="safety_line">{{$entity->safety_line}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="text-uppercase" for="template_width">
                                        Template Width
                                    </label>
                                    <textarea class="form-control" name="template_width"
                                              id="template_width">{{$entity->template_width}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="text-uppercase" for="template_height">
                                        Template Height
                                    </label>
                                    <textarea class="form-control" name="template_height"
                                              id="template_height">{{$entity->template_height}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="text-uppercase" for="ratio">
                                        Ratio
                                    </label>
                                    <textarea class="form-control" name="ratio" id="ratio">{{$entity->ratio}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card p-0 mt-3"
                             x-data="{
                                    templates: ['']
                                 }">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-dollar-sign"></i>
                                    <h5 class="card-title d-inline-block">
                                        Template File
                                    </h5>
                                </div>
                                <small class="text-color:tertiary font-size:14 text-right">
                                    <a href="#" class="btn btn-primary btn-sm py-0"
                                       @click.prevent="templates.push('')">
                                        <i class="fas fa-plus fa-fw"></i>
                                        Add
                                    </a>
                                </small>
                            </div>
                            <div class="card-body">
                                <template x-for="(template, index) in templates" :key="index">
                                    <div class="card p-0 mb-3 rounded">
                                        <div
                                            class="card-header p-3 rounded d-flex align-items-center justify-content-between">
                                            <div class="form-group w-100 m-0 d-flex align-items-center">
                                                <label class="text-uppercase mb-0 mr-3" for="template_file">
                                                    File
                                                </label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="template_file"
                                                           name="template_file[]">
                                                    <label class="custom-file-label" for="template_file">
                                                        Choose file
                                                    </label>
                                                </div>
                                            </div>
                                            <template x-if="index !== 0">
                                                <a href="#" class="btn btn-danger btn-sm text-white ml-3"
                                                   @click.prevent="templates.splice(index, 1)">
                                                    <i class="fas fa-fw fa-trash m-0 text-white"></i>
                                                </a>
                                            </template>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-uppercase" for="template_design_name">
                                                            Design Name
                                                        </label>
                                                        <input type="text" class="form-control"
                                                               name="template_design_name[]"
                                                               id="template_design_name"
                                                               value="{{$entity->template_design_name[0]}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-uppercase" for="template_page_name">
                                                            Page Name
                                                        </label>
                                                        <input type="text" class="form-control"
                                                               name="template_page_name[]"
                                                               id="template_page_name"
                                                               value="{{$entity->template_page_name[0]}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="card p-0 mt-3"
                             x-data="{
                                    templates: ['']
                                 }">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-dollar-sign"></i>
                                    <h5 class="card-title d-inline-block">
                                        Preview File
                                    </h5>
                                </div>
                                <small class="text-color:tertiary font-size:14 text-right">
                                    <a href="#" class="btn btn-primary btn-sm py-0"
                                       @click.prevent="templates.push('')">
                                        <i class="fas fa-plus fa-fw"></i>
                                        Add
                                    </a>
                                </small>
                            </div>
                            <div class="card-body">
                                <template x-for="(template, index) in templates" :key="index">
                                    <div class="card p-0 mb-3">
                                        <div
                                            class="card-header p-3 rounded d-flex align-items-center justify-content-between">
                                            <div class="form-group w-100 m-0 d-flex align-items-center">
                                                <label class="text-uppercase mb-0 mr-3" for="preview_file">
                                                    File
                                                </label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="preview_file"
                                                           name="preview_file[]">
                                                    <label class="custom-file-label" for="preview_file">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                            <template x-if="index !== 0">
                                                <a href="#" class="btn btn-danger btn-sm text-white ml-3"
                                                   @click.prevent="templates.splice(index, 1)">
                                                    <i class="fas fa-fw fa-trash m-0 text-white"></i>
                                                </a>
                                            </template>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-uppercase" for="preview_name">
                                                            Preview Name
                                                        </label>
                                                        <input type="text" class="form-control" name="preview_name[]"
                                                               id="preview_name" value="{{$entity->preview_name[0]}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-uppercase" for="preview_thumbnail_name">
                                                            Thumbnail Name
                                                        </label>
                                                        <input type="text" class="form-control"
                                                               name="preview_thumbnail_name[]"
                                                               id="preview_thumbnail_name"
                                                               value="{{$entity->preview_thumbnail_name[0]}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-uppercase" for="preview_file_config">
                                                    File Config
                                                </label>
                                                <textarea class="form-control" name="preview_file_config[]"
                                                          id="preview_file_config">{{$entity->preview_file_config[0]}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="product_variants" role="tabpanel">
                        <div class="card card--widget p-0">
                            <div class="card-header border-0"
                                 style="background: rgba(99,195,235,0.05);">
                                <div class="row flex-fill justify-content-between align-items-center">
                                    <div class="col-md">
                                        <div class="d-flex">
                                            <div>
                                                <img alt="" class="card-title-icon img-fluid"
                                                     src="{{asset('images/icons/icon_customers.png')}}">
                                            </div>
                                            <h5 class="card-title">
                                                <small class="d-block">
                                                    You can manage variant group (eg. colour, size, style, material),
                                                    and
                                                    variants (eg. red, blue) <strong>per outlet</strong>. Each row in
                                                    the
                                                    table
                                                    below represents a variant on this product.
                                                </small>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-5 mt-3 mt-md-0">
                                        <div class="row align-items-center justify-content-end">
                                            <div
                                                class="col-md-12 d-flex justify-content-between justify-content-md-end">
                                                <a class="btn btn-default" href="#" data-toggle="modal"
                                                   data-target="#modaloptionset">
                                                    <i class="fas fa-fw fa-plus-circle"></i>
                                                    Use Variant Set
                                                </a>
                                                <a class="btn btn-default ml-md-3" href="#" data-toggle="modal"
                                                   data-target="#modaloptionmanual">
                                                    <i class="fas fa-fw fa-plus-circle"></i>
                                                    Add Manual
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3 p-0">
                            <div class="card-body p-0">
                                <table id="table-variant" data-mobile-responsive="true" data-toggle="table">
                                    <thead class="text-uppercase">
                                    <tr>
                                        <th data-field="variant">Variant</th>
                                        <th data-field="sku" data-width="100">SKU</th>
                                        <th data-field="stock" data-width="100">Stock</th>
                                        <th data-width="180" data-field="dimensi">Dimension (cm)</th>
                                        <th data-width="10" data-field="weight">{{Lang::get('product.weight')}}(gr)</th>
                                        <th data-field="production_cost" data-width="100">
                                            Production Cost
                                        </th>
                                        <th data-field="fulfillment_cost" data-width="100">
                                            Fulfillment Cost
                                        </th>
                                        <th data-field="selling_price" data-width="100">
                                            Selling Price
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 justify-content-end align-items-center">
                    <div class="col-12 col-md-auto text-color:icon">
                        Set status as Active to publish. Product will be hidden while status in Inactive.
                    </div>
                    <div class="col-12 col-md-auto d-flex align-items-center justify-content-end mt-3 mt-md-0">
                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-default w-100 dropdown-toggle"
                                    data-toggle="dropdown" id="dropdownMenuButton" type="button">
                                <span x-text="status==0 ? 'Inactive' : 'Active'"></span>
                            </button>
                            <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);" x-on:click="status=1">Active</a>
                                <a class="dropdown-item" href="javascript:void(0);" x-on:click="status=0">Inactive</a>
                            </div>
                        </div>
                        <button class="btn btn-primary px-5 ml-3" type="submit">
                            Save Product
                        </button>
                    </div>
                </div>
                <input type="hidden" name="is_publish" x-model="status">
                <input type="hidden" name="product_options" value="{{json_encode($product_options)}}"/>
                <input type="hidden" name="product_skus" value=""/>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    <script type="text/javascript">
        var base_url = "{{url('js/library')}}";
        var upload_url = "{{route('product.upload')}}";
        var options = <?php echo json_encode($product_options); ?>;
        var old_sku = <?php echo json_encode($entity->skus); ?>;
        var option_rows = [];
        var bind_image_to_sku = false;
    </script>
    <script src="{{asset('js/product.js')}}"></script>
    <script src="{{asset('js/jquery.priceformat.min.js')}}"></script>
    <script src="{{asset('js/typeahead.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-tagsinput.js')}}"></script>
    <script type="text/javascript">
        set_select_category();
        generate_option();
    </script>
    @include('product.partials.modaloptionset')
    @include('product.partials.modaloptionmanual')
@endpush

@push('styles')
    <link rel="stylesheet" href="{{asset('css/bootstrap-tagsinput.css')}}"/>
    <style>
        .bootstrap-tagsinput {
            width: 100%;
            height: 39px;
            display: flex;
            align-items: center;
        }

        .bootstrap-tagsinput .tag {
            color: #fff;
            background-color: #343a40;

            border-radius: 10rem;
            display: inline-block;
            padding: .25em .6em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }
    </style>
@endpush

