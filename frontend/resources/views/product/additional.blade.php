@extends('layouts.layout')

@section('content')
    <div class="container container--app">
        @include('partials.product-nav')
        <div class="text-center">
            <h1 class="page-title font-size:22">
                Design Your Product
            </h1>
            <div class="font-size:14">
                Tweak and finalize the design and price of this product
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-5">
                <input type="search" class="form-control" placeholder="Search product">
                <ul class="list-group list-group-flush font-size:12 mt-3 d-none d-md-block">
                    <li class="list-group-item ps-0 text-uppercase text-color:tertiary fw-400">
                        Categories
                    </li>
                    <li class="list-group-item ps-0">
                        <a href="" class="text-decoration-none text-color:black fw-500">
                            A second item
                        </a>
                    </li>
                </ul>
                <div class="d-block d-md-none my-3">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>All Categories</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <div class="card p-0 mt-3 sticky-top" style="background: #F6F7F9;top: 140px;">
                    <div class="card-header border-0 pb-0 d-flex align-items-center justify-content-between"
                         style="background: #F6F7F9;">
                        <div class="text-nowrap mr-3">
                            <h5 class="card-title d-inline-block">
                                Selected Products
                            </h5>
                        </div>
                    </div>
                    <div class="card-body pb-0" style="max-height: 400px; overflow-y: scroll;">
                        <div class="list-group">
                            @foreach(range(1, 5) as $list)
                                <div
                                    class="list-group-item p-3 mb-4 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('images/product-thumbnail.png') }}" alt=""
                                             class="img-fluid me-3"
                                             width="42">
                                        T-Shirt
                                    </div>
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
                        <button type="submit" class="btn btn-primary w-100">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
                    @foreach(range(1, 15) as $product)
                        <div class="col-md-4 mb-4">
                            <a href="#" class="product-item {{ $loop->last ? 'disabled' : '' }}"
                               :class="selected ? 'active' : ''"
                               x-data="{
                                    selected: false
                               }" @click.prevent="selected = !selected">
                                <div class="card p-0 rounded-0 shadow-sm">
                                    <div class="card-header p-0 position-relative">
                                        <img src="{{ asset('images/candle.jpeg') }}" alt="" class="img-fluid w-100">
                                        <div class="product-item__overlay">
                                            <span class="badge bg-dark">Coming Soon</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="text-uppercase font-size:12 fw-400">
                                            Decor
                                        </div>
                                        <h3 class="font-size:14 m-0 fw-600">
                                            Poster - 24” x 36”
                                        </h3>
                                        <div class="d-flex justify-content-between">
                                            <div class="font-size:12 text-color:tertiary fw-500">
                                                Base cost IDR 100,000
                                            </div>
                                            <input class="form-check-input" type="checkbox" x-model="selected">
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
