@extends('layouts.auth')

@section('page_title', 'Login')

@section('content')
    <div class="container">
        <form method="post" action="TODO">
            {{ csrf_field() }}
            <div class="card mx-auto p-md-5 position-relative" style="border-radius: 10px;max-width: 758px">
                <div class="position-absolute card-decoration" style="left: 0;z-index: -1;width: 100%;">
                    <img src="{{ asset('images/bg-card-decor.png') }}" alt="" class="img-fluid mx-auto d-block w-100"
                         style="max-width: 670px;">
                </div>
                <div class="card-body p-0">
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <img src="{{asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
                        </div>
                    @endforeach
                    @if (session('message'))
                        <div class="alert alert-success">
                            <a href="javascript:void(0);" class="close" data-dismiss="alert"
                               aria-label="close">&times;</a>
                            <img src="{{asset('backend/images/success-icon.png')}}"
                                 alt=""> {{session('message')}}
                        </div>
                    @endif
                    <fieldset>
                        <legend class="fw-600 font-poppins font-size:22">
                            Join As Creator
                        </legend>
                        <small class="font-size:12">
                            Let’s start by filling up these real quick! Or, have you had an account? <a
                                href="{{ route('login') }}" class="text-decoration-none">Sign In</a>
                        </small>
                        <hr>
                        <div class="mb-4">
                            <label for="company" class="text-uppercase text-color:black">
                                Your Company Name
                            </label>
                            <input id="company" name="company" type="text"
                                   class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}"
                                   value="{{ old('company') }}">
                            @error('company')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="phone" class="text-uppercase text-color:black">
                                        Mobile Phone
                                    </label>
                                    <input id="phone" name="phone" type="tel"
                                           class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="email" class="text-uppercase text-color:black">
                                        E-mail Address
                                    </label>
                                    <input id="email" name="email" type="email"
                                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                           value="{{ old('email') }}">
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4 position-relative">
                                    <label for="password" class="text-uppercase text-color:black">Password</label>
                                    <input type="password" id="password" name="password" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4 position-relative">
                                    <label for="password_confirmation" class="text-uppercase text-color:black">
                                        Repeat Password
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="describe" class="text-uppercase text-color:black">
                                        Describe Yourself?
                                    </label>
                                    <select class="form-select" id="describe">
                                        <option selected>Illustrator/Designer</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="intention" class="text-uppercase text-color:black">
                                        What Would You Like To Do With Us
                                    </label>
                                    <select class="form-select" id="intention">
                                        <option selected>Start my first online business</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row align-items-center">
                            <div class="col-md">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                           id="flexCheckIndeterminate">
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        I agree to Printerous <a href="TODO" class="text-decoration-none">Terms</a> and
                                        <a href="TODO" class="text-decoration-none">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('register_success') }}" class="btn btn-primary w-100 text-center mt-3 mt-md-0">
                                    Create Account
                                </a>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .card-decoration {
            bottom: -30px;
        }

        @media (max-width: 767px) {
            main.d-flex {
                display: block !important;
            }
        }

        @media (min-width: 768px) {
            .card-decoration {
                bottom: -50px;
            }
        }
    </style>
@endpush
