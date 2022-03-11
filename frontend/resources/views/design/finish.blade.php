@extends('layouts.layout')

@section('content')
    <div class="container container--app">
        @include('partials.product-nav')
        <div class="text-start">
            <h1 class="page-title font-size:22">
                Set the details
            </h1>
            <div class="font-size:14">
                Final step!
            </div>
        </div>
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{$error}}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endforeach
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{session('error')}}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <form method="post" action="{{route("design.store")}}">
            {{csrf_field()}}
            <div class="row mt-4">
                <div class="col-md-5 sticky-top" style="top: 140px;z-index: 1">
                    <div class="card p-0 mt-3" style="background: #F6F7F9;">
                        <div class="card-header border-0 d-flex align-items-center justify-content-between"
                             style="background: #F6F7F9;">
                            <div class="text-nowrap mr-3">
                                <i class="fas fa-fw fa-image"></i>
                                <h5 class="card-title d-inline-block">
                                    Product Detail
                                </h5>
                            </div>
                        </div>
                        <div class="card-body py-0" style="max-height: 400px;overflow-y: scroll;">
                            <div class="list-group product-selection-list">
                                @foreach(range(1, 5) as $list)
                                    <div
                                        class="list-group-item p-3 mb-4 d-flex justify-content-between align-items-center active">
                                        <label class="d-flex align-items-center" style="font-family: Poppins;font-style: normal;font-weight: normal;font-size: 14px;line-height: 22px;color: #000000;">
                                            <span class="me-2 d-block">
                                                <input class="form-check-input rounded-checkbox" type="checkbox" id="checkboxNoLabel">
                                            </span>
                                            <img src="{{ asset('images/product-thumbnail.png') }}" alt=""
                                                 class="img-fluid me-3"
                                                 width="42">
                                            T-Shirt
                                        </label>
                                        <div class="d-flex font-size:12">
                                            <a href="" class="text-color:blue text-decoration-none">
                                                Remove
                                            </a>
                                            <div class="mx-2 text-color:icon">|</div>
                                            <a href="" class="text-color:blue text-decoration-none">
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
                                    <strong>5 Products</strong> selected
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary text-white w-100">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card p-0 mt-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="text-nowrap mr-3">
                                <i class="fas fa-fw fa-image"></i>
                                <h5 class="card-title d-inline-block">
                                    Product Detail
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="" class="text-color:black text-uppercase">
                                            Title
                                        </label>
                                        <input type="text" name="title" value="{{old('title')}}" class="form-control">
                                    </div>
                                    <div class="mb-4">
                                        <label for="" class="text-color:black text-uppercase">
                                            Product Code (SKU)
                                        </label>
                                        <input type="text" name="sku_code" value="{{old('sku_code')}}" class="form-control">
                                    </div>
                                    <div class="mb-4 mb-md-0">
                                        <label for="" class="text-color:black text-uppercase">
                                            Visibility
                                        </label>
                                        <select class="form-select" name="is_publish" aria-label="Default select example">
                                            <option @if(old('is_publish')==1) selected @endif value="1">Public</option>
                                            <option @if(old('is_publish')==0) selected @endif value="0">Draft</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="">
                                        <label for="" class="text-color:black text-uppercase">
                                            Description
                                        </label>
                                        <textarea class="form-control h-100" name="description" id="exampleFormControlTextarea1"
                                                  rows="9">{{old('description')}}</textarea>
                                    </div>
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
        </form>
    </div>
@endsection
