@extends('layouts.layout')

@section('content')
    <div class="container">
        @if (session('error'))
            <x-alert type="danger" dismissible icon>
                {{ session('error') }}
            </x-alert>
        @endif
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
                        @foreach($pendingTransactions as $pending)
                            <a href="{{ get_class($pending) == \App\Models\Topup::class ? route('topup', ['order_id' => $pending->serial_number]) : '#' }}"
                               class="list-group-item list-group-item-action">
                                <div class="row align-items-center">
                                    <div class="col-md">
                                        <dl>
                                            <dt>
                                                @if(get_class($pending) == \App\Models\Topup::class)
                                                    Wallet top up
                                                @else
                                                    Production Cost - T-shirt (2 pcs) //TODO
                                                @endif
                                            </dt>
                                            <dd>
                                                {{ $pending->created_at->format('d M Y, H:i A') }}
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl>
                                            <dt>
                                                {{ $pending->serial_number }}
                                            </dt>
                                            <dd>
                                                {{ $pending->payment }}
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <dl>
                                            <dt>
                                                IDR {{ $pending->formatted_total }}
                                            </dt>
                                        </dl>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="card p-0 mt-4">
                    <div class="card-header p-4">
                        <h3 class="card-title">
                            Transaction History
                        </h3>
                    </div>
                    <div class="list-group order-list">
                        @forelse($transactions as $transaction)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-md">
                                        <dl>
                                            <dt>
                                                @if($transaction->isDeposit())
                                                    Wallet top up
                                                @elseif($transaction->isCommission())
                                                    Referral commission
                                                @else
                                                    Production Cost - T-shirt (2 pcs) //TODO
                                                @endif
                                            </dt>
                                            <dd>
                                                {{ $transaction->created_at->format('d M Y, H:i A') }}
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-3">
                                        <dl>
                                            @if($transaction->isDeposit())
                                                <dt>
                                                    {{ $transaction->ref->serial_number }}
                                                </dt>
                                            @else
                                                <dt>
                                                    {{ $transaction->ref->store->storename }}
                                                </dt>
                                            @endif
                                            @unless($transaction->isCommission())
                                                <dd>
                                                    {{ $transaction->ref->payment }}
                                                </dd>
                                            @endunless
                                        </dl>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <dl>
                                            <dt class="{{ $transaction->isWithdrawal() ? 'text-color:red' : 'text-color:green' }}
                                                ">
                                                IDR
                                                {{ $transaction->formatted_given }}
                                            </dt>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center my-4">
                                You have no previous transactions.<br>
                                <a href="#modalTopup" class="btn btn-primary mt-3 d-inline-flex px-5"
                                   data-bs-toggle="modal">
                                    Top Up Balance
                                </a>
                            </p>
                        @endforelse
                    </div>
                    @if($transactions->hasPages())
                        <div class="card-footer justify-content-center d-flex">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalTopup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content"
                 x-data="{
                    amount: '',
                    formattedAmount: '',
                    originalAmount: '',
                    paymentMethod: ''
                  }">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Top Up Balance
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('topup') }}" method="post" id="paymentForm">
                        @csrf
                        <div class="mb-4">
                            <label for="amount" class="text-uppercase text-color:black">
                                Top Up Amount In IDR
                            </label>
                            <input type="tel" class="form-control text-end" id="amount" x-model="amount"
                                   @focus="amount = originalAmount"
                                   @input.lazy="formattedAmount = number_format(amount, 0, ',', '.'); originalAmount = amount"
                                   @blur="originalAmount = amount; amount = formattedAmount">
                            <input type="hidden" name="amount" x-model="originalAmount">
                        </div>
                        {{--                        <div class="alert alert-danger d-flex" role="alert" style="background: #EA001B;">--}}
                        {{--                            <i class="ri-information-line ri-fw align-middle ri-lg mt-1"></i>--}}
                        {{--                            <div class="font-size:12 ms-2">--}}
                        {{--                                Pending order: <strong>IDR 209,000</strong><br>--}}
                        {{--                                Please top up to avoid automatic cancellation.--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <h3 class="font-size:16 fw-500">
                            Select Payment Method
                        </h3>
                        <hr>
                        <div class="accordion accordion-flush" id="accordionPanelsStayOpenExample">
                            @foreach($paymentMethods as $group => $channels)
                                <div class="accordion-item mb-4 bg-color:gray border-0">
                                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                        <button class="accordion-button text-uppercase border-0" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-{{ $group }}" aria-expanded="true"
                                                aria-controls="panelsStayOpen-{{ $group }}">
                                            @switch($group)
                                                @case('VA')
                                                Transfer Virtual Account
                                                @break
                                                @case('eWallet')
                                                E-wallet
                                                @break
                                                @case('CC')
                                                Credit Card
                                                @break
                                            @endswitch
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-{{ $group }}" class="accordion-collapse collapse show"
                                         aria-labelledby="panelsStayOpen-headingOne">
                                        <div class="accordion-body pt-0">
                                            <div class="row">
                                                @foreach($channels as $payment)
                                                    <div class="col-6 col-md-3 mb-3">
                                                        <input type="radio" class="btn-check"
                                                               name="payment_method"
                                                               id="{{ Str::slug($payment['display_name']) }}"
                                                               x-model="paymentMethod"
                                                               autocomplete="off"
                                                               value="{{ $payment['display_name'] }}">
                                                        <label class="btn btn-default py-2"
                                                               for="{{ Str::slug($payment['display_name']) }}">
                                                            <img src="{{ asset($payment['logo']) }}"
                                                                 alt="logo {{ $payment['display_name'] }}"
                                                                 title="{{ $payment['display_name'] }}"
                                                                 class="img-fluid h-100" style="max-width: none">
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex w-100 justify-content-end">
                        <template x-if="originalAmount">
                            <label
                                class="bg-color:gray d-flex align-items-center justify-content-center font-size:12 px-5 w-100 me-3 text-color:black"
                                style="height: 39px;">
                                Total amount: <strong class="font-size:14 ms-1">
                                    IDR <span x-text="formattedAmount">0</span>
                                </strong>
                            </label>
                        </template>
                        <button type="submit" class="btn btn-primary px-5 my-0"
                                disabled form="paymentForm"
                                :disabled="!originalAmount || !paymentMethod">
                            Proceed
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pagination {
            margin-bottom: 0;
        }
    </style>
@endpush
