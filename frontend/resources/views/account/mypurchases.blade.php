@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                @include('partials.account-sidebar')
            </div>
            <div class="col-md-8">
                <div class="card p-0">
                    <div class="card-header p-4">
                        <h3 class="card-title">
                            My Purchases
                        </h3>
                        <small class="card-subtitle">
                            The list of order you’ve purchased yourself
                        </small>
                    </div>
                    <div class="list-group order-list">
                        @foreach([] as $order)
                            <a href="{{ route('orderdetail', '1') }}"
                               class="list-group-item list-group-item-action bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <dl>
                                            <dt>INV 20203389</dt>
                                            <dd>08 Sep 2018, 14:00 PM</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-5">
                                        <dl>
                                            <dt>4 Items</dt>
                                            <dd>Jolly t-shirt, Nocturnal pillow, Do...</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-2">
                                        <dl>
                                            <dt>IDR 500,000</dt>
                                            <dd>Bank Transfer</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-2">
                                        <dl>
                                            <dt>
                                            <span class="badge badge-success d-inline-block p-0"
                                                  style="width: 6px;height: 6px;"></span>
                                                Completed
                                            </dt>
                                        </dl>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
