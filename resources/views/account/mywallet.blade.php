@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-0 sticky-top mb-4 account-sidebar" style="top: 85px;">
                    <div class="card-header p-4 d-flex align-items-center">
                        <div class="me-3">
                            <img src="{{ asset('images/icons/icon_gross_income.png') }}" alt="" class="img-fluid"
                                 style="width: 55px;height: 55px;">
                        </div>
                        <div>
                            <small class="card-subtitle d-block text-uppercase mb-1">
                                Balance
                            </small>
                            <h3 class="card-title font-size:30 fw-400">
                                IDR 2,300
                            </h3>
                        </div>
                    </div>
                    <div class="card-body p-3 d-none d-md-block">
                        <div class="alert alert-danger d-flex" role="alert" style="background: #EA001B;">
                            <i class="ri-information-line ri-fw align-middle ri-lg mt-1"></i>
                            <div class="font-size:12 ms-2">
                                Pending order: <strong>IDR 209,000</strong><br>
                                Please top up to avoid automatic cancellation.
                            </div>
                        </div>
                        <a href="" class="btn btn-primary w-100">
                            Top Up Balance
                        </a>
                        <a href="" class="text-decoration-none font-size:12 mt-3 d-block">
                            Withdraw funds <i class="ri-arrow-right-line ri-fw align-middle"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card p-0">
                    <div class="card-header p-4">
                        <h3 class="card-title">
                            Pending
                        </h3>
                    </div>
                    <div class="list-group order-list">
                        <a href="{{ route('shipmentdetail', '1') }}" class="list-group-item list-group-item-action">
                            <div class="row align-items-center">
                                <div class="col-md">
                                    <dl>
                                        <dt>
                                            Production Cost - T-shirt (2 pcs)
                                        </dt>
                                        <dd>
                                            08 Sep 2018, 14:00 PM
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-md-3">
                                    <dl>
                                        <dt>INV 20203389</dt>
                                        <dd>Desty</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2 text-end">
                                    <dl>
                                        <dt>IDR 500,000</dt>
                                    </dl>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('shipmentdetail', '1') }}" class="list-group-item list-group-item-action">
                            <div class="row align-items-center">
                                <div class="col-md">
                                    <dl>
                                        <dt>
                                            Production Cost - T-shirt (2 pcs)
                                        </dt>
                                        <dd>
                                            08 Sep 2018, 14:00 PM
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-md-3">
                                    <dl>
                                        <dt>INV 20203389</dt>
                                        <dd>Desty</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2 text-end">
                                    <dl>
                                        <dt>IDR 500,000</dt>
                                    </dl>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card p-0 mt-4">
                    <div class="card-header p-4">
                        <h3 class="card-title">
                            Transaction History
                        </h3>
                    </div>
                    <div class="list-group order-list">
                        <a href="{{ route('shipmentdetail', '1') }}" class="list-group-item list-group-item-action">
                            <div class="row align-items-center">
                                <div class="col-md">
                                    <dl>
                                        <dt>
                                            Production Cost - T-shirt (2 pcs)
                                        </dt>
                                        <dd>
                                            08 Sep 2018, 14:00 PM
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-md-3">
                                    <dl>
                                        <dt>INV 20203389</dt>
                                        <dd>Desty</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2 text-end">
                                    <dl>
                                        <dt class="text-color:red">IDR 500,000</dt>
                                    </dl>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('shipmentdetail', '1') }}" class="list-group-item list-group-item-action">
                            <div class="row align-items-center">
                                <div class="col-md">
                                    <dl>
                                        <dt>
                                            Production Cost - T-shirt (2 pcs)
                                        </dt>
                                        <dd>
                                            08 Sep 2018, 14:00 PM
                                        </dd>
                                    </dl>
                                </div>
                                <div class="col-md-3">
                                    <dl>
                                        <dt>INV 20203389</dt>
                                        <dd>Desty</dd>
                                    </dl>
                                </div>
                                <div class="col-md-2 text-end">
                                    <dl>
                                        <dt class="text-color:green">IDR 500,000</dt>
                                    </dl>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
