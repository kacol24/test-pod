@extends('layouts.layout')

@section('content')
    <div class="container container--app">
        @include('partials.product-nav')
        <div class="text-start">
            <h1 class="page-title font-size:22">
                Select Product
            </h1>
            <div class="font-size:14">
                Tweak and finalize the design and price of this product
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-3">
                <input type="search" class="form-control" placeholder="Search product">
                <ul class="list-group list-group-flush font-size:12 mt-3 d-none d-md-block">
                    <li class="list-group-item ps-0 text-uppercase text-color:tertiary fw-400">
                        Categories
                    </li>
                    @foreach($categories as $category)
                    <li class="list-group-item ps-0">
                        <a href="?category_id={{$category->id}}" class="text-decoration-none text-color:black fw-500">
                            {{$category->name}}
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="d-block d-md-none my-3">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>All Categories</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
            <div class="col-md">
                <div class="row">
                    @foreach($products as $product)
                        <div class="col-md-3 mb-4">
                            <a href="{{ route('design', $product->id) }}" class="product-item">
                                <div class="card p-0 rounded-0 shadow-sm">
                                    <div class="card-header p-0 position-relative">
                                        <img src="{{ env('BACKEND_URL').'/storage/masterproduct/'.$product->thumbnail() }}" alt="" class="img-fluid w-100">
                                        <div class="product-item__overlay">
                                            <span class="badge bg-dark">Coming Soon</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="text-uppercase font-size:12 fw-400">
                                            {{$product->firstcategory()->name}}
                                        </div>
                                        <h3 class="font-size:14 m-0 fw-600">
                                            {{$product->title}}
                                        </h3>
                                        <div class="font-size:12 text-color:tertiary fw-500">
                                            Base cost IDR {{number_format(($product->default_sku->production_cost+$product->default_sku->fulfillment_cost),0,",",".")}}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
