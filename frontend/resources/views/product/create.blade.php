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
                            <a href="?category_id={{$category->id}}"
                               class="text-decoration-none text-color:black fw-500">
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
                <div class="row"
                     x-data="{
                        selectedProduct: {}
                     }">
                    <div class="modal fade" id="productViewModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="row g-0 m-0">
                                        <div class="col-md-6">
                                            <img :src="selectedProduct.thumbnail_url"
                                                 :alt="selectedProduct.title"
                                                 class="img-fluid w-100">
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <button type="button" class="btn-close position-absolute"
                                                    data-bs-dismiss="modal" aria-label="Close"
                                                    style="top: 15px;right: 15px;">
                                            </button>
                                            <div class="p-5 h-100 w-100">
                                                <h6 class="text-uppercase font-size:12"
                                                    x-text="selectedProduct.firstcategory_name">
                                                    Category
                                                </h6>
                                                <h5 class="modal-title mb-4"
                                                    x-text="selectedProduct.title">
                                                    Product Title
                                                </h5>
                                                <div style="font-weight: 500;line-height: 22px;color: #707A83;"
                                                     class="font-size:14"
                                                     x-html="selectedProduct.description">
                                                    Description
                                                </div>
                                                <a href="" class="btn btn-primary d-inline-flex px-5 mt-4">
                                                    Select This Product
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach($products as $product)
                        <div class="col-md-3 mb-4">
                            <a href="{{ route('design', $product->id) }}" class="product-item">
                                <div class="card p-0 rounded-0 shadow-sm position-relative">
                                    <div class="position-absolute pe-auto" style="z-index: 1; top: 13px;left: 13px;"
                                         @click.prevent='selectedProduct = @json($product); $nextTick(function() { productViewModal.show() })'>
                                        <span
                                            class="badge bg-dark text-white rounded-circle p-0 m-0 d-flex align-items-center justify-content-center"
                                            style="width: 20px;height: 20px;">
                                            <i class="fas fa-fw fa-info m-0 fa-xs"></i>
                                        </span>
                                    </div>
                                    <div class="card-header p-0 position-relative">
                                        <img
                                            src="{{ $product->thumbnail_url }}"
                                            alt="{{ $product->title }}" class="img-fluid w-100">
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
                                            Base cost
                                            IDR {{number_format(($product->default_sku->production_cost+$product->default_sku->fulfillment_cost),0,",",".")}}
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

@push('scripts')
    <script>
        var productViewModal = new bootstrap.Modal(document.getElementById('productViewModal'));
    </script>
@endpush
