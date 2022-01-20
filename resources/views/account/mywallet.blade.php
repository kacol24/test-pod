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
                                IDR {{ number_format($storeBalanceComposer, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        {{--                        <div class="alert alert-danger d-flex" role="alert" style="background: #EA001B;">--}}
                        {{--                            <i class="ri-information-line ri-fw align-middle ri-lg mt-1"></i>--}}
                        {{--                            <div class="font-size:12 ms-2">--}}
                        {{--                                Pending order: <strong>IDR 209,000</strong><br>--}}
                        {{--                                Please top up to avoid automatic cancellation.--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <a href="#modalTopup" class="btn btn-primary w-100" data-bs-toggle="modal">
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
    <div class="modal fade" id="modalTopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form action="{{ route('topup') }}">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">
                            Top Up Balance
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="amount" class="text-uppercase text-color:black">
                                Top Up Amount In IDR
                            </label>
                            <input type="text" class="form-control" id="amount" name="amount">
                        </div>
                        <div class="alert alert-danger d-flex" role="alert" style="background: #EA001B;">
                            <i class="ri-information-line ri-fw align-middle ri-lg mt-1"></i>
                            <div class="font-size:12 ms-2">
                                Pending order: <strong>IDR 209,000</strong><br>
                                Please top up to avoid automatic cancellation.
                            </div>
                        </div>
                        <h3 class="font-size:16 fw-500">
                            Select Payment Method
                        </h3>
                        <hr>
                        <div class="accordion accordion-flush" id="accordionPanelsStayOpenExample">
                            <div class="accordion-item mb-4 bg-color:gray border-0">
                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                    <button class="accordion-button text-uppercase border-0" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                            aria-controls="panelsStayOpen-collapseOne">
                                        Transfer Virtual Account
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                                     aria-labelledby="panelsStayOpen-headingOne">
                                    <div class="accordion-body pt-0">
                                        <div class="row">
                                            <div class="col-6 col-md-3">
                                                <input type="radio" class="btn-check" name="payment_method" id="option1"
                                                       autocomplete="off" value="virtual_bca"
                                                       checked>
                                                <label class="btn btn-default py-0" for="option1">
                                                    <img src="{{ asset('images/icons/logo_bca 1.png') }}" alt=""
                                                         class="img-fluid w-100">
                                                </label>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <input type="radio" class="btn-check" name="payment_method" id="option2"
                                                       autocomplete="off">
                                                <label class="btn btn-default py-0" for="option2">
                                                    <img src="{{ asset('images/icons/logo_bca 1.png') }}" alt=""
                                                         class="img-fluid w-100">
                                                </label>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <input type="radio" class="btn-check" name="payment_method" id="option3"
                                                       autocomplete="off">
                                                <label class="btn btn-default py-0" for="option3">
                                                    <img src="{{ asset('images/icons/logo_bca 1.png') }}" alt=""
                                                         class="img-fluid w-100">
                                                </label>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <input type="radio" class="btn-check" name="payment_method" id="option4"
                                                       autocomplete="off">
                                                <label class="btn btn-default py-0" for="option4">
                                                    <img src="{{ asset('images/icons/logo_bca 1.png') }}" alt=""
                                                         class="img-fluid w-100">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-4 bg-color:gray border-0">
                                <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                    <button class="accordion-button text-uppercase border-0 collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                                            aria-controls="panelsStayOpen-collapseTwo">
                                        Credit Card
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse"
                                     aria-labelledby="panelsStayOpen-headingTwo">
                                    <div class="accordion-body pt-0">
                                        <strong>This is the second item's accordion body.</strong> It is hidden by
                                        default,
                                        until the collapse plugin adds the appropriate classes that we use to style each
                                        element. These classes control the overall appearance, as well as the showing
                                        and
                                        hiding via CSS transitions. You can modify any of this with custom CSS or
                                        overriding
                                        our default variables. It's also worth noting that just about any HTML can go
                                        within
                                        the <code>.accordion-body pt-0</code>, though the transition does limit
                                        overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item bg-color:gray border-0">
                                <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                    <button class="accordion-button text-uppercase border-0 collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                                            aria-controls="panelsStayOpen-collapseThree">
                                        E-Money
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse"
                                     aria-labelledby="panelsStayOpen-headingThree">
                                    <div class="accordion-body pt-0">
                                        <strong>This is the third item's accordion body.</strong> It is hidden by
                                        default,
                                        until the collapse plugin adds the appropriate classes that we use to style each
                                        element. These classes control the overall appearance, as well as the showing
                                        and
                                        hiding via CSS transitions. You can modify any of this with custom CSS or
                                        overriding
                                        our default variables. It's also worth noting that just about any HTML can go
                                        within
                                        the <code>.accordion-body pt-0</code>, though the transition does limit
                                        overflow.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex w-100">
                            <label
                                class="bg-color:gray d-flex align-items-center justify-content-center font-size:12 px-5 w-100 me-3 text-color:black"
                                style="height: 39px;">
                                Total amount: <strong class="font-size:14 ms-1">IDR 1,000,000</strong>
                            </label>
                            <button type="submit" class="btn btn-primary px-5 my-0">Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
