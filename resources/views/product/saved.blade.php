@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="text-center mx-auto" style="max-width: 450px">
            <img src="{{ asset('images/inky-celebration-1-1.png') }}" alt="" class="img-fluid my-5">
            <h1 class="font-size:22 fw-600">
                Product successfully published!
            </h1>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-5">
                Go to products
            </a>
        </div>
    </div>
@endsection
