@extends('layouts.layout')

@section('content')

    <div class="container">
        <div class="row justify-content-between">
            <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
                <h1 class="page-title m-0">
                    {{ __('Edit Product') }}
                </h1>
            </div>
            <div class="col-12 col-md">
                <div class="row justify-content-end">
                    <div class="col-auto mt-3 mt-md-0">
                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-default w-100 dropdown-toggle"
                                    data-bs-toggle="dropdown" id="dropdownMenuButton" type="button">
                                Active
                            </button>
                            <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);">Active</a>
                                <a class="dropdown-item" href="javascript:void(0);">Inactive</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-auto">
                        <a class="btn btn-default btn-block"
                           href="{{route('design.destroy', 1)}}">
                            <i class="fas fa-fw fa-trash"></i>
                            {{ __('Delete') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 sticky-top sticky-top--header">
                <div class="card p-0" style="background: #F6F7F9;">
                    <div class="card-header border-0 d-flex align-items-center justify-content-between"
                         style="background: #F6F7F9;">
                        <div class="text-nowrap mr-3">
                            <i class="fas fa-fw fa-image"></i>
                            <h5 class="card-title d-inline-block">
                                Products
                            </h5>
                        </div>
                    </div>
                    <div class="card-body pb-0" style="max-height: 400px;overflow-y: scroll;">
                        <div class="list-group">
                            @foreach($designProducts as $product)
                                <div
                                    class="list-group-item p-3 mb-4 d-flex justify-content-between align-items-center border border-color:black">
                                    <label
                                        class="d-flex align-items-center font-size:14 text-color:black font-weight-normal"
                                        style="font-family: Poppins;font-style: normal">
                                        <span class="me-2 d-block">
                                            <input class="form-check-input rounded-checkbox" type="checkbox" checked
                                                   readonly disabled>
                                        </span>
                                        <img src="{{ $product->masterproduct->thumbnail_url }}"
                                             alt="{{ $product->title }} thumbnail"
                                             class="img-fluid me-3" width="42">
                                        {{ $product->title }}
                                    </label>
                                    <div class="d-flex font-size:12">
                                        <a href="{{ route('design.remove-product', $product->id) }}"
                                           data-confirm="Are you sure you want to remove this product from your list?"
                                           class="text-color:blue text-decoration-none">
                                            Remove
                                        </a>
                                        <div class="mx-2 text-color:icon">|</div>
                                        <a href="{{ route('design.product.edit', [$design->id, $product->id]) }}"
                                           class="text-color:blue text-decoration-none">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer border-0 pt-0" style="background: #F6F7F9;">
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <a href="" class="text-color:blue text-decoration-none">
                                Back
                            </a>
                            <div>
                                <strong>{{ $designProducts->count() }} {{ Str::plural('product', $designProducts->count()) }}</strong> selected
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card p-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="text-nowrap mr-3">
                            <i class="fas fa-fw fa-image"></i>
                            <h5 class="card-title d-inline-block">
                                Product Detail
                            </h5>
                        </div>
                    </div>
                    <div class="card-body"
                         x-data="{
                                title: '{{ $design->title }}',
                                description: '{!! $design->description !!}',
                                titleMax: 55,
                                descriptionMax: 700
                             }">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="title" class="text-color:black text-uppercase">
                                    Artwork Name
                                </label>
                                <div class="text-end text-muted font-size:12">
                                    <span x-text="title.length">0</span>/<span
                                        x-text="titleMax">55</span>
                                </div>
                            </div>
                            <input type="text" name="title" value="{{old('title')}}" class="form-control" id="title"
                                   x-model="title" :maxlength="titleMax">
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="description" class="text-color:black text-uppercase">
                                    Artwork Description
                                </label>
                                <div class="text-end text-muted font-size:12">
                                    <span x-text="description.length">0</span>/<span
                                        x-text="descriptionMax">700</span>
                                </div>
                            </div>
                            <textarea class="form-control h-auto" name="description" id="description"
                                      rows="5" x-model="description"
                                      :maxlength="descriptionMax">{{old('description')}}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="" class="text-color:black text-uppercase">
                                    Artwork Code
                                </label>
                                <input type="text" name="sku_code" value="{{ $design->sku_code }}"
                                       class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="" class="text-color:black text-uppercase">
                                    Visibility
                                </label>
                                <select class="form-select" name="is_publish"
                                        aria-label="Default select example">
                                    <option @if($design->is_publish==1) selected @endif value="1">Public
                                    </option>
                                    <option @if($design->is_publish==0) selected @endif value="0">Draft
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-0 mt-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-fw fa-cubes"></i>
                            <h5 class="card-title d-inline-block">
                                Stores
                            </h5>
                        </div>
                        <div class="font-size:14 text-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value=""
                                       id="select_all">
                                <label class="form-check-label" for="select_all">
                                    Select All
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div
                                    class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                               id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Tokopedia
                                        </label>
                                    </div>
                                </div>
                                <div
                                    class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                               id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Tokopedia
                                        </label>
                                    </div>
                                </div>
                                <div
                                    class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                               id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Tokopedia
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
