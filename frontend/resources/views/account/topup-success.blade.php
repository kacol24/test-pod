@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="text-center mx-auto" style="max-width: 320px">
            <img src="{{ asset('images/inky-celebration-1-1.png') }}" alt="" class="img-fluid my-5">
            <h1 class="font-size:22 fw-600">
                Payment Successful
            </h1>
            <p class="font-size:12">
                Hooray! You’ve completed the payment.
            </p>
            <div class="bg-color:gray text-center py-3 w-100 font-size:12 text-uppercase">
                Total Payment
                <strong class="d-block font-size:18">
                    IDR {{ $orderable->formatted_total }}
                </strong>
            </div>
            <a href="{{ route('mywallet') }}" class="btn btn-primary w-100 mt-3">
                Back to dashboard
            </a>
        </div>
    </div>
@endsection
