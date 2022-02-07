@extends('layouts.layout')

@section('content')
    <div class="container">
        <h1 class="page-title mb-4">
            My Account
        </h1>
        <div class="row">
            <div class="col-md-4">
                @include('partials.account-sidebar')
            </div>
            <div class="col-md-8">
                <a href="{{ route('myorders') }}"
                   class="text-decoration-none font-size:12 d-flex align-items-center mb-2">
                    <i class="ri-arrow-left-line align-middle me-1"></i>
                    Back
                </a>
                @include('partials.invoice')
            </div>
        </div>
    </div>
@endsection
