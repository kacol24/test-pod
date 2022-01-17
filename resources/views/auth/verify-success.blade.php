@extends('layouts.auth')

@section('page_title', 'Login')

@section('content')
    <div class="container">
        <div class="card mx-auto p-md-5 d-flex align-items-center justify-content-center position-relative"
             style="border-radius: 10px;max-width: 758px">
            <div class="position-absolute card-decoration" style="left: 0;z-index: -1;width: 100%;">
                <img src="{{ asset('images/bg-card-decor.png') }}" alt="" class="img-fluid mx-auto d-block w-100"
                     style="max-width: 670px;">
            </div>
            <div class="text-center py-5 mx-auto" style="max-width: 447px">
                <img src="{{ asset('images/inky-celebration-1-1.png') }}" alt="" class="mx-auto img-fluid mb-3"
                     style="max-width: 133px">
                <h1 class="font-poppins font-size:22 fw-600 mb-3">
                    Nice! Your account is now verified.
                </h1>
                <div class="font-size:12 mb-5">
                    Your account is now active. Login now to manage your account.
                </div>
                <a href="{{ route('login') }}" class="btn btn-primary d-inline-flex">
                    Login
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-decoration {
            bottom: -30px;
        }

        @media (min-width: 768px) {
            .card-decoration {
                bottom: -50px;
            }
        }
    </style>
@endpush
