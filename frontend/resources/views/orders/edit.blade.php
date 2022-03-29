@extends('layouts.layout')

@section('content')
    <main class="pb-3 pb-md-5" role="main" style="">
        @if (session('status'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <img src="{{asset('images/success-icon.png')}}" alt=""> {{session('status')}}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <img src="{{asset('images/error-icon.png')}}" alt=""> {{session('error')}}
            </div>
        @endif
        <div class="container mb-3 container--app mb-md-0">
            <a class="btn btn-link ps-0 d-inline-flex text-decoration-none" href="{{route('orders.index')}}">
                <i class="fas fa-fw fa-arrow-left"></i>
                Back
            </a>
            <div class="row justify-content-between">
                <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
                    <h1 class="m-0 page-title">
                        Order #{{ $order->order_no }}
                    </h1>
                    <div class="px-3 ms-3">
                        <span class="badge badge-status-{{ $order->status_id }} d-inline-block p-0"
                              style="width: 6px;height: 6px;"></span>
                        {{ $order->status->title }}
                    </div>
                    <span class="ms-3 badge badge-success">
                        // TODO
                    </span>
                </div>
            </div>
        </div>
        <div class="container pb-3 mt-4 container--app">
            <div class="p-0 card">
                <div class="card-header d-flex align-items-center">
                    <i class="ri-truck-line align-middle me-2"></i>
                    <h5 class="card-title d-inline-block">
                        Shipping
                    </h5>
                </div>
                <div class="stepper sticky-top sticky-top--header">
                    <div class="stepper__steps">
                        <div
                            class="stepper__step {{ $order->status_id > 0 ? 'stepper__step--stepped' : '' }}">
                            <div class="stepper__numbering">1</div>
                            <div class="stepper__step-content">
                                <div class="d-none d-md-block">
                                    Order Placed
                                </div>
                            </div>
                        </div>
                        <div
                            class="stepper__step {{ $order->status_id > 1 ? 'stepper__step--stepped' : '' }}">
                            <div class="stepper__numbering">2</div>
                            <div class="stepper__step-content">
                                <div class="d-none d-md-block">
                                    Order Paid
                                </div>
                            </div>
                        </div>
                        <div
                            class="stepper__step {{ $order->status_id > 3 ? 'stepper__step--stepped' : '' }}">
                            <div class="stepper__numbering">3</div>
                            <div class="stepper__step-content">
                                <div class="d-none d-md-block">
                                    Production
                                </div>
                            </div>
                        </div>
                        <div
                            class="stepper__step {{ $order->status_id > 4? 'stepper__step--stepped' : '' }}">
                            <div class="stepper__numbering">4</div>
                            <div class="stepper__step-content">
                                <div class="d-none d-md-block">
                                    Shipment
                                </div>
                            </div>
                        </div>
                        <div
                            class="stepper__step {{ $order->status_id > 6 ? 'stepper__step--stepped' : '' }}">
                            <div class="stepper__numbering">4</div>
                            <div class="stepper__step-content">
                                <div class="d-none d-md-block">
                                    Completed
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-0 card mt-4">
                <div class="card-header d-flex align-items-center">
                    <i class="ri-file-list-line align-middle me-2"></i>
                    <h5 class="card-title d-inline-block">
                        Customer Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="font-size:14 w-100">
                            <tr>
                                <th style="width: 150px;">Name</th>
                                <td>
                                    {{ $order->shipping->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>
                                    {!! $order->full_address !!}
                                </td>
                            </tr>
                            <tr>
                                <th>Phone No.</th>
                                <td>
                                    <a href="tel:{{ $order->shipping->phone }}">
                                        {{ $order->shipping->phone }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    // TODO
                                </td>
                            </tr>
                            {{--                            <tr>--}}
                            {{--                                <th>Message</th>--}}
                            {{--                                <td class="text-color:green">--}}
                            {{--                                    Please include a note: “Happy Birthday!”--}}
                            {{--                                </td>--}}
                            {{--                            </tr>--}}
                        </table>
                    </div>
                </div>
            </div>
            <div class="p-0 mt-4 card">
                <div class="card-header d-flex align-items-center">
                    <i class="ri-shopping-cart-line align-middle me-2"></i>
                    <h5 class="card-title d-inline-block">
                        Item List
                    </h5>
                </div>
                <div class="p-0 card-body">
                    <div class="table-responsive">
                        <table class="table m-0 table-hover">
                            <thead class="text-uppercase">
                            <tr>
                                <th class="col-6">ITEMS</th>
                                <th class="text-center col-1">QTY</th>
                                <th class="text-center col-2">PRICE</th>
                                <th class="text-center col-3">SUBTOTAL</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->details as $detail)
                                <tr class="text-nowrap">
                                    <td>
                                        <div class="d-inline-flex">
                                            <div class="d-flex">
                                                <img src="{{ asset('images/product-thumbnail.png') }}" alt=""
                                                     style="border-radius:4px;width:40px;height:40px;">
                                            </div>
                                            <div class="d-block ms-3">
                                                <strong style="font-size: 14px;color: #3E3F42;line-height: 22px;">
                                                    {{ $detail->title }}
                                                </strong>
                                                @if($detail->sku)
                                                    @if($detail->sku->option_detail1()->first())
                                                        <br>
                                                        <div style="font-size: 12px;color: #3E3F42;line-height: 18px;">
                                                            {{ $detail->sku->option_detail1()->first()->option->title }}
                                                            :
                                                            {{ $detail->sku->option_detail1()->first()->title }}
                                                        </div>
                                                    @endif
                                                    @if($detail->sku->option_detail2()->first())
                                                        <div
                                                            style="font-size: 12px;color: #3E3F42;line-height: 18px;">
                                                            {{ $detail->sku->option_detail2()->first()->option->title }}
                                                            :
                                                            {{ $detail->sku->option_detail2()->first()->title }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td class="text-end">
                                        IDR {{ $detail->formatted_price }}
                                    </td>
                                    <td class="text-end">
                                        IDR {{ $detail->formatted_subtotal }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="font-size:14 text-nowrap">
                            <tr class="text-end">
                                <th colspan="3" class="border-0 text-color:icon font-weight-normal">Subtotal</th>
                                <td class="border-0">
                                    IDR {{ $order->formatted_subtotal }}
                                </td>
                            </tr>
                            @if($order->discount_voucher)
                                <tr class="text-end">
                                    <th colspan="3" class="border-0 text-color:icon font-weight-normal">Promotion</th>
                                    <td class="border-0">
                                        (-) IDR {{ $order->formatted_discount_voucher }}
                                    </td>
                                </tr>
                            @endif
                            @if($order->shipping_fee)
                                <tr class="text-end">
                                    <th colspan="3" class="border-0 text-color:icon font-weight-normal">
                                        Shipping (1kg)
                                    </th>
                                    <td class="border-0">
                                        IDR {{ $order->formatted_shipping_fee }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="text-end">
                                <th colspan="3" class="border-0 font-weight-bold">Total</th>
                                <td class="border-0 font-weight-bold font-size:16">
                                    IDR {{ $order->formatted_grand_total }}
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-right">
                        <button class="btn btn-default">
                            <i class="fas fa-fw fa-plus-circle"></i>
                            Add Item
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-0 mt-4 card">
                <div class="card-header">
                    <div>
                        <i class="fas fa-fw fa-file-alt"></i>
                        <h5 class="card-title d-inline-block">
                            {{Lang::get('order.internalnote')}}
                        </h5>
                    </div>
                </div>
                @if(count(range(1, 3)))
                    <div class="list-group list-group-flush">
                        <div class="py-4 list-group-item">
                            @foreach(range(1, 3) as $note)
                                <div class="d-flex w-100 align-items-center justify-content-between">
                                    <div>
                                        <h5 class="m-0 font-size:14 font-weight-normal">
                                            $note->message
                                        </h5>
                                        <small class="font-size:12 text-color:icon">
                                            2 hours ago by $note->admin->name
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="border-0 card-footer">
                    <form id="form" class="form-validate" action="route('order.addnote',['id'=> $order->id])"
                          method="post">
                        {{ csrf_field() }}
                        <div class="form-group mb-3">
                            <label class="text-uppercase" for="note">{{Lang::get('order.note')}}</label>
                            <textarea class="form-control" name="message" rows="4"></textarea>
                        </div>
                        <div class="text-right">
                            <input type="hidden" name="status" value="low">
                            <button class="px-5 btn btn-primary" type="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script src="{{asset('js/typeahead.min.js')}}"></script>
    <script src="{{asset('js/form-validate.js')}}"></script>
    <script src="{{asset('js/number_format.js')}}"></script>
@endpush
