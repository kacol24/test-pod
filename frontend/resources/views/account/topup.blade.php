@extends('layouts.layout')

@section('page_title', 'Finish Checkout')

@section('content')
    <div class="container">
        <div class="text-center mx-auto" style="max-width: 550px">
            @include('checkout.xendit.' . strtolower($paymentType))
        </div>
    </div>
@endsection
