@extends('layout')

@section('content')
    <main class="pb-3 pb-md-5" role="main" style=""
          x-data="{language: '{{session('language')}}', status: {{(old('is_publish')) ? old('is_publish') : 0}}}">
        <div class="container-fluid mb-3 mb-md-0">
            @if($errors->any())
                <div class="alert alert-danger d-flex justify-content-between">
                    <div class="d-flex">
                        <div class="mr-3">
                            <img src="{{asset('images/error-icon.png')}}" alt="">
                        </div>
                        <ul class="list-unstyled m-0">
                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>
            @endif
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
        <div class="container-fluid mt-3">
            <form class="form-validate" action="{{route('product.store')}}" method="post" enctype="multipart/form-data">
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
                                            'filename' => $image,
                                            'images' => [
                                                '175x175' => image_url('masterproduct',$image)
                                            ]
                                        ];
                                    }, old('images', []))
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="title">Product Name</label>
                                            <input type="text" class="form-control" name="title" id="title" required
                                                   placeholder="Product Name" value="{{old('title')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="prism_id">Product ID in PRISM</label>
                                            <input type="text" class="form-control" name="prism_id" id="prism_id"
                                                   required
                                                   placeholder="Product ID in PRISM" value="{{old('prism_id')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="capacity_id">Capacity</label>
                                            <select class="form-control" name="capacity_id">
                                                @foreach($capacities as $capacity)
                                                    <option value="{{$capacity->id}}" {{ old('capacity_id') == $capacity->id ? 'selected' : '' }}>
                                                        {{$capacity->title}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
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
                                                   value="{{old('default_production_cost')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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
                                                   value="{{old('default_fulfillment_cost')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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
                                                   value="{{old('default_selling_price')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="production_time">Production Time
                                                (days)</label>
                                            <input type="number" class="form-control text-right" name="production_time"
                                                   id="production_time" required
                                                   placeholder="Production Time (days)"
                                                   value="{{old('production_time')}}">
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
                                                   value="{{old('fulfillment_time')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="threshold">
                                                Threshold
                                            </label>
                                            <input type="number" class="form-control text-right" name="threshold"
                                                   id="threshold"
                                                   placeholder="Threshold" required
                                                   value="{{old('threshold')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="text-uppercase" for="description">Product Description</label>
                                    <textarea class="form-control" name="description" id="description" rows="10">{{old('description')}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="text-uppercase" for="size_chart">Size Chart (optional)</label>
                                    <textarea class="form-control" name="size_chart" id="size_chart" rows="10">{{old('size_chart')}}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="weight">{{Lang::get('product.weight')}}
                                                (gram)</label>
                                            <input type="number" class="form-control default" name="default_weight"
                                                   id="default_weight" placeholder="{{Lang::get('product.weight')}}"
                                                   value="{{old('default_weight')}}" data-toggle="tooltip" required
                                                   data-placement="top" title="{{Lang::get('product.youcanweight')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="sku">SKU Code</label>
                                            <input type="text" class="form-control default" name="default_sku" id="sku"
                                                   placeholder="SKU" value="{{old('default_sku')}}"
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
                                                   id="default_stock" value="{{old('default_stock')}}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="width">Width (cm)</label>
                                            <input type="number" class="form-control default" name="default_width"
                                                   id="width" placeholder="{{Lang::get('product.width')}}"
                                                   value="{{(old('default_width')) ? old('default_width') : 1}}"
                                                   data-toggle="tooltip" min="0" data-placement="top" required
                                                   title="{{Lang::get('product.youcanwidth')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="length">Length (CM)</label>
                                            <input type="number" class="form-control default" name="default_length"
                                                   id="length" placeholder="{{Lang::get('product.length')}}"
                                                   value="{{(old('default_length')) ? old('default_length') : 1}}"
                                                   data-toggle="tooltip" min="0" data-placement="top" required
                                                   title="{{Lang::get('product.youcanlength')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="height">Height (CM)</label>
                                            <input type="number" class="form-control default" name="default_height"
                                                   id="height" placeholder="{{Lang::get('product.height')}}"
                                                   value="{{(old('default_height')) ? old('default_height') : 1}}"
                                                   data-toggle="tooltip" data-placement="top" min="0" required
                                                   title="{{Lang::get('product.youcanheight')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card p-0 mt-3"
                             x-cloak
                             id="ColorApp"
                             x-data="{
                                    colors: [],
                                    showDelete: function() {
                                        return true;
                                    },
                                    deleteItem: function(index) {
                                        this.colors.splice(index, 1);
                                    }
                                 }">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-dollar-sign"></i>
                                    <h5 class="card-title d-inline-block">
                                        Design Colors
                                    </h5>
                                </div>
                                <small class="text-color:tertiary font-size:14 text-right">
                                    <a href="#" class="btn btn-primary btn-sm py-0"
                                       @click.prevent="colors.push({})">
                                        <i class="fas fa-plus fa-fw"></i>
                                        Add
                                    </a>
                                </small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <template x-for="(color, index) in colors" :key="index">
                                        <div class="col-md-3">
                                            <div class="card p-0 mb-3">
                                                <div class="card-body p-3 position-relative">
                                                    <template x-if="showDelete()">
                                                        <div class="position-absolute" style="right: 0;top: 0;">
                                                            <a href="#" class="text-color:red"
                                                               @click.prevent="deleteItem(index);">
                                                                <i class="fas fa-fw fa-times m-0"></i>
                                                            </a>
                                                        </div>
                                                    </template>
                                                    <div class="form-group">
                                                        <label class="text-uppercase">
                                                            Color
                                                        </label>
                                                        <input type="color" class="form-control"
                                                               :name="'colors['+ index +'][color]'"
                                                               x-model="color.color">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-uppercase">
                                                            Name
                                                        </label>
                                                        <input type="text" class="form-control"
                                                               :name="'colors['+ index +'][name]'"
                                                               x-model="color.name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
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
                                            <label class="text-uppercase" for="categories">Categories</label>
                                            <div class="dropdown">
                                                <div class="input-group" id="dropdownCategory" data-toggle="dropdown"
                                                     aria-haspopup="true" aria-expanded="false">
                                                    <input type="text"
                                                           class="form-control selectcategory dropdown-toggle"
                                                           placeholder="Category">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon1"><img
                                                            src="{{asset('images/selectcat-image.png')}}"
                                                            alt=""></span>
                                                    </div>
                                                </div>
                                                <div class="dropdown-menu" aria-labelledby="dropdownCategory">
                                                    @foreach($categories as $category)
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                           tabIndex="-1">
                                                            <label class="m-0 d-flex align-items-center">
                                                                <input class="category mr-1"
                                                                       data-value="{{$category->name}}"
                                                                       id="checkbox{{$category->id}}"
                                                                       name="category_id[]"
                                                                       parent="{{$category->parent_id}}"
                                                                       type="checkbox"
                                                                       value="{{$category->id}}"/>&nbsp;{{$category->name}}
                                                            </label>
                                                        </a>
                                                        @if(isset($category->children))
                                                            @foreach($category->children as $child1)
                                                                <a class="dropdown-item" href="javascript:void(0);"
                                                                   tabIndex="-1">
                                                                    <label class="pl-3 m-0 d-flex align-items-center">
                                                                        <input class="category mr-1"
                                                                               data-value="{{$child1->name}}"
                                                                               id="checkbox{{$child1->id}}"
                                                                               name="category_id[]"
                                                                               parent="{{$child1->parent_id}}"
                                                                               type="checkbox"
                                                                               value="{{$child1->id}}"/>&nbsp;{{$child1->name}}
                                                                    </label>
                                                                </a>
                                                                @if(isset($child1->children))
                                                                    @foreach($child1->children as $child2)
                                                                        <a class="dropdown-item"
                                                                           href="javascript:void(0);">
                                                                            <label
                                                                                class="pl-5 m-0 d-flex align-items-center">
                                                                                <input class="category mr-1"
                                                                                       data-value="{{$child2->name}}"
                                                                                       id="checkbox{{$child2->id}}"
                                                                                       name="category_id[]"
                                                                                       parent="{{$child2->parent_id}}"
                                                                                       type="checkbox"
                                                                                       value="{{$child2->id}}"/>&nbsp;{{$child2->name}}
                                                                            </label>
                                                                        </a>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
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
                        <div class="card p-0 mt-3"
                             x-cloak
                             id="TemplateApp"
                             x-data="{
                                    templates: []
                                 }">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-dollar-sign"></i>
                                    <h5 class="card-title d-inline-block">
                                        Templates
                                    </h5>
                                </div>
                                <small class="text-color:tertiary font-size:14 text-right">
                                    <a href="#" class="btn btn-primary btn-sm py-0"
                                       @click.prevent="templates.push({design_name: '', designs: [], previews: []}); $nextTick(function() { $('.price').priceFormat({ prefix: '', centsLimit: 0 }) })">
                                        <i class="fas fa-plus fa-fw"></i>
                                        Add
                                    </a>
                                </small>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="accordionExample">
                                    <template x-for="(template, index) in templates" :key="index">
                                        <div class="card p-0 mb-3 rounded border" x-id="['template-accordion']">
                                            <div
                                                class="card-header m-0 rounded p-3 d-flex align-items-center justify-content-between">
                                                <div class="form-group w-100 m-0 d-flex align-items-center">
                                                    <button class="btn btn-link" type="button"
                                                            data-toggle="collapse"
                                                            :data-target="'#' + $id('template-accordion', index)"
                                                            aria-expanded="true" aria-controls="collapseOne">
                                                        <i class="fas fa-fw fa-angle-down m-0"></i>
                                                    </button>
                                                    <label class="text-nowrap m-0" x-text="template.design_name">
                                                        Design Name
                                                    </label>
                                                </div>
                                                <template x-if="templates.length > 0">
                                                    <a href="#" class="btn btn-danger btn-sm text-white ml-3"
                                                       @click.prevent="templates.splice(index, 1)">
                                                        <i class="fas fa-fw fa-trash m-0 text-white"></i>
                                                    </a>
                                                </template>
                                            </div>
                                            <div :id="$id('template-accordion', index)" class="collapse show">
                                                <div class="card-body p-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="text-uppercase"
                                                                       for="template_design_name">
                                                                    Design Name
                                                                </label>
                                                                <input type="text" class="form-control"
                                                                       :name="'templates['+ index +'][design_name]'"
                                                                       id="template_design_name"
                                                                       x-model="template.design_name">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="text-uppercase" for="template_price">
                                                                    Price
                                                                </label>
                                                                <input type="tel" class="form-control price text-right"
                                                                       :name="'templates['+ index +'][price]'"
                                                                       id="template_price" x-model="template.price">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="" class="text-uppercase">
                                                                    Shape
                                                                </label>
                                                                <div>
                                                                    @foreach(['square', 'circle'] as $radio)
                                                                        <div class="form-check form-check-inline">
                                                                            <label class="form-check-label">
                                                                                <input class="form-check-input"
                                                                                       type="radio"
                                                                                       {{ $loop->first ? 'checked' : '' }}
                                                                                       :name="'templates['+ index +'][shape]'"
                                                                                       value="{{ $radio }}" {{ $loop->first ? 'checked' : '' }}>
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
                                                                            <label class="form-check-label">
                                                                                <input class="form-check-input"
                                                                                       type="radio"
                                                                                       {{ $loop->first ? 'checked' : '' }}
                                                                                       :name="'templates['+ index +'][orientation]'"
                                                                                       value="{{ $radio }}" {{ $loop->first ? 'checked' : '' }}>
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
                                                                            <label class="form-check-label">
                                                                                <input class="form-check-input"
                                                                                       type="radio"
                                                                                       {{ $loop->first ? 'checked' : '' }}
                                                                                       :name="'templates['+ index +'][unit]'"
                                                                                       value="{{ $radio }}" {{ $loop->first ? 'checked' : '' }}>
                                                                                {{ $radio }}
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
                                                                            <label class="form-check-label">
                                                                                <input class="form-check-input"
                                                                                       type="radio"
                                                                                       {{ $loop->first ? 'checked' : '' }}
                                                                                       :name="'templates['+ index +'][enable_resize]'"
                                                                                       value="{{ $index }}" {{ $loop->first ? 'checked' : '' }}>
                                                                                {{ Str::title($radio) }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-uppercase">
                                                            Bleed
                                                        </label>
                                                        <textarea class="form-control"
                                                                  :name="'templates['+ index +'][bleed]'"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-uppercase">
                                                            Safety Line
                                                        </label>
                                                        <textarea class="form-control"
                                                                  :name="'templates['+ index +'][safety_line]'"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-uppercase">
                                                            Template Width
                                                        </label>
                                                        <textarea class="form-control"
                                                                  :name="'templates['+ index +'][width]'"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-uppercase">
                                                            Template Height
                                                        </label>
                                                        <textarea class="form-control"
                                                                  :name="'templates['+ index +'][height]'"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-uppercase">
                                                            Ratio
                                                        </label>
                                                        <textarea class="form-control"
                                                                  :name="'templates['+ index +'][ratio]'"></textarea>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div
                                                                class="form-group d-flex align-items-center justify-content-between">
                                                                <h5 class="card-title">
                                                                    Designs
                                                                </h5>
                                                                <small
                                                                    class="text-color:tertiary font-size:14 text-right">
                                                                    <a href="#" class="btn btn-primary btn-sm py-0"
                                                                       @click.prevent="template.designs.push('')">
                                                                        <i class="fas fa-plus fa-fw"></i>
                                                                        Add
                                                                    </a>
                                                                </small>
                                                            </div>
                                                            <template x-for="(design, designIndex) in template.designs"
                                                                      :key="designIndex">
                                                                <div class="card p-0 mb-3">
                                                                    <div class="card-body p-3 position-relative">
                                                                        <template x-if="template.designs.length > 0">
                                                                            <div class="position-absolute"
                                                                                 style="right: 0;top: 0;">
                                                                                <a href="#" class="text-color:red"
                                                                                   @click.prevent="template.designs.splice(designIndex, 1)">
                                                                                    <i class="fas fa-fw fa-times m-0"></i>
                                                                                </a>
                                                                            </div>
                                                                        </template>
                                                                        <div class="form-group">
                                                                            <label class="text-uppercase">
                                                                                Page Name
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                   :name="'templates['+ index +'][design]['+ designIndex +'][page_name]'">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-uppercase">
                                                                                Design File
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                   :name="'templates['+ index +'][design]['+ designIndex +'][customer_canvas]'">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-uppercase">
                                                                                Mockup File
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                   :name="'templates['+ index +'][design]['+ designIndex +'][mockup_customer_canvas]'">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-uppercase">
                                                                                Position
                                                                            </label>
                                                                            <select class="custom-select"
                                                                                    :name="'templates['+ index +'][design]['+ designIndex +'][position]'">
                                                                                <option value="down">Down</option>
                                                                                <option value="up">Up</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="text-uppercase">
                                                                                        Mockup Width
                                                                                    </label>
                                                                                    <input type="number"
                                                                                           class="form-control text-right"
                                                                                           :name="'templates['+ index +'][design]['+ designIndex +'][mockup_width]'">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="text-uppercase">
                                                                                        Mockup Height
                                                                                    </label>
                                                                                    <input type="number"
                                                                                           class="form-control text-right"
                                                                                           :name="'templates['+ index +'][design]['+ designIndex +'][mockup_height]'">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-uppercase">
                                                                                Design Location
                                                                            </label>
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="input-group">
                                                                                        <div
                                                                                            class="input-group-prepend">
                                                                                            <span
                                                                                                class="input-group-text"
                                                                                                style="background-color:#e9ecef;">
                                                                                                X
                                                                                            </span>
                                                                                        </div>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               :name="'templates['+ index +'][design]['+ designIndex +'][location_x]'">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="input-group">
                                                                                        <div
                                                                                            class="input-group-prepend">
                                                                                            <span
                                                                                                class="input-group-text"
                                                                                                style="background-color:#e9ecef;">
                                                                                                Y
                                                                                            </span>
                                                                                        </div>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               :name="'templates['+ index +'][design]['+ designIndex +'][location_y]'">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div
                                                                class="form-group d-flex align-items-center justify-content-between">
                                                                <h5 class="card-title">
                                                                    Previews
                                                                </h5>
                                                                <small
                                                                    class="text-color:tertiary font-size:14 text-right">
                                                                    <a href="#" class="btn btn-primary btn-sm py-0"
                                                                       @click.prevent="template.previews.push('')">
                                                                        <i class="fas fa-plus fa-fw"></i>
                                                                        Add
                                                                    </a>
                                                                </small>
                                                            </div>
                                                            <template
                                                                x-for="(preview, previewIndex) in template.previews"
                                                                :key="previewIndex">
                                                                <div class="card p-0 mb-3">
                                                                    <div class="card-body p-3 position-relative">
                                                                        <template x-if="template.previews.length > 0">
                                                                            <div class="position-absolute"
                                                                                 style="right: 0;top: 0;">
                                                                                <a href="#" class="text-color:red"
                                                                                   @click.prevent="template.previews.splice(previewIndex, 1)">
                                                                                    <i class="fas fa-fw fa-times m-0"></i>
                                                                                </a>
                                                                            </div>
                                                                        </template>
                                                                        <div class="form-group">
                                                                            <label class="text-uppercase"
                                                                                   for="template_file">
                                                                                File
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                   :name="'templates['+ index +'][preview]['+previewIndex+'][customer_canvas]'">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-uppercase">
                                                                                Preview Name
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                   :name="'templates['+ index +'][preview]['+previewIndex+'][preview_name]'">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-uppercase">
                                                                                Thumbnail Name
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                   :name="'templates['+ index +'][preview]['+previewIndex+'][thumbnail_name]'">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="text-uppercase">
                                                                                File Config
                                                                            </label>
                                                                            <input type="text" class="form-control"
                                                                                   :name="'templates['+ index +'][preview]['+previewIndex+'][file_config]'">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
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
                                    <thead>
                                    <tr>
                                        <th data-field="variant">Variant</th>
                                        <th data-field="sku">SKU</th>
                                        <th data-width="100" data-field="stock">Stock</th>
                                        <th data-width="250" data-field="dimensi">Dimension (cm)</th>
                                        <th data-width="10" data-field="weight">{{Lang::get('product.weight')}}(gr)</th>
                                        <th data-width="80" data-field="production_cost">
                                            Production Cost
                                        </th>
                                        <th data-width="80" data-field="fulfillment_cost">
                                            Fulfillment Cost
                                        </th>
                                        <th data-width="80" data-field="selling_price">
                                            Selling Price
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
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
                            Create Product
                        </button>
                    </div>
                </div>
                <input type="hidden" name="is_publish" x-model="status">
                <input type="hidden" name="product_options" value=""/>
                <input type="hidden" name="product_skus" value=""/>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    <script type="text/javascript">
        var base_url = "{{url('js/library')}}";
        var upload_url = "{{route('product.upload')}}";
        var bind_image_to_sku = false;
        var options = [];
        var old_sku = [];
        var option_rows = [];
        var lang = "{{session('language')}}";
        var outlets = [];
        var stocks = [];
    </script>
    <script src="{{asset('js/product.js')}}"></script>
    <script src="{{asset('js/jquery.priceformat.min.js')}}"></script>
    <script src="{{asset('js/typeahead.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-tagsinput.js')}}"></script>
    @include('product.partials.modaloptionset')
    @include('product.partials.modaloptionmanual')
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

@push('styles')
    <link rel="stylesheet" href="{{asset('css/bootstrap-tagsinput.css')}}"/>
@endpush
