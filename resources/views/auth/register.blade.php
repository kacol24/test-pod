@extends('layouts.auth')

@section('page_title', 'Register')

@section('content')
    <div class="container">
        <form method="post" action="{{ route('register') }}" novalidate
              x-data="{
                username: '{{ old('username') }}',
                name: '{{ old('name') }}',
                phone: '{{ old('phone') }}',
                email: '{{ old('email') }}',
                password: '',
                password_confirmation: '',
                description: '{{ old('description') }}',
                what_to_do: '{{ old('what_to_do') }}',
                terms: false,
                canRegister: function() {
                    return this.username && this.name && this.phone && this.email && this.password && this.password_confirmation && this.description && this.what_to_do && this.terms;
                }
              }">
            {{ csrf_field() }}
            <div class="card mx-auto p-md-5 position-relative" style="border-radius: 10px;max-width: 758px">
                <div class="position-absolute card-decoration" style="left: 0;z-index: -1;width: 100%;">
                    <img src="{{ asset('images/bg-card-decor.png') }}" alt="" class="img-fluid mx-auto d-block w-100"
                         style="max-width: 670px;">
                </div>
                <div class="card-body p-0">
                    @if (session('status'))
                        <x-alert type="success" dismissible icon>
                            {{ session('status') }}
                        </x-alert>
                    @endif
                    <fieldset>
                        <legend class="fw-600 font-poppins font-size:22">
                            Create Account
                        </legend>
                        <small class="font-size:12">
                            Let’s start by filling up these real quick! Or, have you had an account? <a
                                href="{{ route('login') }}" class="text-decoration-none">Sign In</a>
                        </small>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="username" class="text-uppercase text-color:black">
                                        Account Name
                                    </label>
                                    <input id="username" name="username" type="text"
                                           class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                           placeholder="Create a name for your account (eg. ELLISTORE)"
                                           @keydown.space.prevent x-model="username"
                                           value="{{ old('username') }}">
                                    @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="name" class="text-uppercase text-color:black">
                                        Your Name
                                    </label>
                                    <input id="name" name="name" type="text"
                                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                           placeholder="Your name" x-model="name"
                                           value="{{ old('name') }}">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="phone" class="text-uppercase text-color:black">
                                        Mobile Phone
                                    </label>
                                    <input id="phone" name="phone" type="tel"
                                           class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                           placeholder="Your mobile phone" x-model="phone"
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
                                           placeholder="Your email" x-model="email"
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
                                    <input type="password" id="password" name="password"
                                           class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                           x-model="password"
                                           placeholder="Your password">
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4 position-relative">
                                    <label for="password_confirmation" class="text-uppercase text-color:black">
                                        Repeat Password
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                           x-model="password_confirmation"
                                           placeholder="Retype password"
                                           class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                                    @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="description" class="text-uppercase text-color:black">
                                        Describe Yourself?
                                    </label>
                                    <select class="form-select {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                            id="description" name="description"
                                            x-model="description" required>
                                        <option selected disabled value="" hidden>
                                            Describe Yourself?
                                        </option>
                                        <option value="Illustrator/Designer">
                                            Illustrator/Designer
                                        </option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="what_to_do" class="text-uppercase text-color:black">
                                        What Would You Like To Do With Us
                                    </label>
                                    <select class="form-select {{ $errors->has('what_to_do') ? 'is-invalid' : '' }}"
                                            id="what_to_do" name="what_to_do" x-model="what_to_do"
                                            required>
                                        <option selected disabled value="" hidden>
                                            What Would You Like To Do With Us
                                        </option>
                                        <option value="Start my first online business">
                                            Start my first online business
                                        </option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                    @error('what_to_do')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row align-items-center">
                            <div class="col-md">
                                <div class="form-check {{ $errors->has('terms') ? 'is-invalid' : '' }}">
                                    <input class="form-check-input" type="checkbox" name="terms" x-model="terms"
                                           id="flexCheckIndeterminate">
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        I terms to Printerous <a href="TODO" class="text-decoration-none">Terms</a> and
                                        <a href="TODO" class="text-decoration-none">Privacy Policy</a>
                                    </label>
                                </div>
                                @error('terms')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-auto">
                                <button type="submit"
                                        disabled
                                        :disabled="!canRegister()"
                                        class="btn btn-primary w-100 text-center mt-3 mt-md-0 px-5">
                                    Create Account
                                </button>
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
