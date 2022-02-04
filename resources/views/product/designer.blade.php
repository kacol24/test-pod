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
                <div class="p-3 p-md-4" style="background: #F6F7F9;">
                    <div class="card p-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="text-nowrap mr-3">
                                <i class="fas fa-fw fa-image"></i>
                                <h5 class="card-title d-inline-block">
                                    Pricing
                                </h5>
                            </div>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-md">
                                    <label for="" class="text-color:black text-uppercase">
                                        Set Your Price (IDR)
                                    </label>
                                </div>
                                <div class="col-12 order-md-5">
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-auto order-md-3">
                                    <small class="text-color:green font-size:12 text-end">
                                        IDR 30,000 profit/order
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card p-0 mt-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fas fa-fw fa-cubes"></i>
                                <h5 class="card-title d-inline-block">
                                    Variants
                                </h5>
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
                                </div>
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
                                </div>
                                <div class="col-md-6">
                                    <div
                                        class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                   id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                All Variants
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card p-0 mt-3 border-0" style="background-color: transparent;">
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <a href="" class="text-color:blue text-decoration-none font-size:12">
                                <i class="fas fa-arrow-left fa-fw"></i>
                                Back
                            </a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Continue
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem commodi distinctio doloribus ea esse
                facilis fugiat illo iure maiores nemo nihil nostrum porro quod quos reprehenderit, soluta tempora totam,
                vel!
            </div>
        </div>
    </div>
@endsection
