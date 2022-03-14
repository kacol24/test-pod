@extends('layouts.layout')

@section('content')
    <div class="container container--app">
        @include('partials.product-nav')
        <div class="text-start">
            <h1 class="page-title font-size:22">
                Great design. Now you can add more products!
            </h1>
            <div class="font-size:14">
                To help you get up and running, we’ve created some additional products based on your original design.
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card p-0 sticky-top" style="background: #F6F7F9;top: 140px;">
                    <div class="card-header border-0 pb-0 d-flex align-items-center justify-content-between"
                         style="background: #F6F7F9;">
                        <div class="text-nowrap mr-3">
                            <h5 class="card-title d-inline-block">
                                Selected Products
                            </h5>
                        </div>
                    </div>
                    <div class="card-body pb-0" style="max-height: 400px; overflow-y: auto;">
                        <div class="list-group">
                            @foreach($designProducts as $product)
                                <div
                                    class="list-group-item p-3 mb-4 d-flex justify-content-between align-items-center {{ $designs->pluck('master_product_id')->contains($product->id) ? 'border border-color:black' : '' }}">
                                    <label
                                        class="d-flex align-items-center font-size:14 text-color:black font-weight-normal"
                                        style="font-family: Poppins;font-style: normal">
                                        <span class="me-2 d-block">
                                            <input class="form-check-input rounded-checkbox" type="checkbox"
                                                   {{ $designs->pluck('master_product_id')->contains($product->id) ? 'checked' : '' }}
                                                   readonly disabled>
                                        </span>
                                        <img src="{{ $product->thumbnail_url }}"
                                             alt="{{ $product->title }} thumbnail"
                                             class="img-fluid me-3" width="42">
                                        {{ $product->title }}
                                    </label>
                                    <div class="d-flex font-size:12">
                                        <a href="{{ route('design.remove-product', $product->id) }}"
                                           class="text-color:blue text-decoration-none">
                                            Remove
                                        </a>
                                        <div class="mx-2 text-color:icon">|</div>
                                        <a href="{{ route('design', [$product->id, 'edit']) }}"
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
                            <a href="{{ route('design', $designProducts->first()->id) }}"
                               class="text-color:blue text-decoration-none">
                                <i class="fas fa-arrow-left fa-fw"></i>
                                Back
                            </a>
                            <div>
                                <strong>{{ $designProducts->count() }} {{ Str::plural('product', $designProducts->count()) }}</strong>
                                selected
                            </div>
                        </div>
                        <a href="{{ route('design.finish') }}"
                           class="btn btn-primary w-100 text-white" {{ $designProducts->pluck('id')->diff($designs->pluck('master_product_id'))->count() ? 'disabled' : '' }}>
                            Continue
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row"
                     x-data="{
                        modalProduct: {}
                     }">
                    <div class="modal fade" id="productViewModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="row g-0 m-0">
                                        <div class="col-md-6">
                                            <img :src="modalProduct.thumbnail_url"
                                                 :alt="modalProduct.title"
                                                 class="img-fluid w-100">
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <button type="button" class="btn-close position-absolute"
                                                    data-bs-dismiss="modal" aria-label="Close"
                                                    style="top: 15px;right: 15px;">
                                            </button>
                                            <div class="p-5 h-100 w-100">
                                                <h6 class="text-uppercase font-size:12"
                                                    x-text="modalProduct.firstcategory_name">
                                                    Category
                                                </h6>
                                                <h5 class="modal-title mb-4"
                                                    x-text="modalProduct.title">
                                                    Product Title
                                                </h5>
                                                <div style="font-weight: 500;line-height: 22px;color: #707A83;"
                                                     class="font-size:14"
                                                     x-html="modalProduct.description">
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
                        <div class="col-md-4 mb-4">
                            @php($href = $designProducts->pluck('id')->contains($product->id) ? 'remove' : 'add')
                            <a href="{{ route('design.additional', [$href => $product->id]) }}"
                               class="product-item {{ $designProducts->pluck('id')->contains($product->id) ? 'active' : '' }}">
                                <div class="card p-0 rounded-0 shadow-sm position-relative">
                                    <div class="position-absolute pe-auto" style="z-index: 1; top: 13px;left: 13px;"
                                         @click.prevent='modalProduct = @json($product); $nextTick(function() { productViewModal.show() })'>
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
                                        <div class="d-flex justify-content-between">
                                            <div class="font-size:12 text-color:tertiary fw-500">
                                                Base cost IDR {{number_format(($product->base_cost),0,",",".")}}
                                            </div>
                                            <input class="form-check-input" type="checkbox"
                                                   {{ $designProducts->pluck('id')->contains($product->id) ? 'checked' : '' }} readonly
                                                   disabled>
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
