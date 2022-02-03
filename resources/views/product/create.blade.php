@extends('layouts.layout')

@section('content')
    <div class="container container--app">
        @include('partials.product-nav')
        <div class="text-center">
            <h1 class="page-title font-size:22">
                Select Product
            </h1>
            <div class="font-size:14">
                Tweak and finalize the design and price of this product
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md" style="max-width: 255px">
                <input type="search" class="form-control" placeholder="Search product">
                <ul class="list-group list-group-flush font-size:12 mt-3">
                    <li class="list-group-item ps-0 text-uppercase text-color:tertiary fw-400">
                        Categories
                    </li>
                    <li class="list-group-item ps-0">
                        <a href="" class="text-decoration-none text-color:black fw-500">
                            A second item
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md">
                <div class="row">
                    @foreach(range(1, 15) as $product)
                        <div class="col-md-3 mb-4">
                            <a href="#" class="product-item {{ $loop->last ? 'disabled' : '' }}">
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
                                        <div class="font-size:12 text-color:tertiary fw-500">
                                            Base cost IDR 100,000
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
