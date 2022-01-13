@extends('layouts.auth')

@section('page_title', 'Login')

@section('content')
    <div class="container">
        @if(session('storename'))
            <form method="post" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="card mx-auto p-md-5 position-relative" style="border-radius: 10px;max-width: 445px"
                     x-data="{ passwordFieldShown: false }">
                    <div class="position-absolute" style="bottom: -30px;left: 0;z-index: -1;width: 100%;">
                        <img src="{{ asset('images/bg-card-decor.png') }}" alt=""
                             class="img-fluid mx-auto d-block w-100"
                             style="max-width: 400px;">
                    </div>
                    <div class="card-body p-0">
                        @foreach ($errors->all() as $error)
                            <x-alert type="danger" dismissible icon>
                                {{ $error }}
                            </x-alert>
                        @endforeach
                        @if (session('status'))
                            <x-alert type="success" dismissible icon>
                                {{ session('status') }}
                            </x-alert>
                        @endif
                        <fieldset>
                            <legend
                                class="fw-600 font-poppins font-size:22 d-flex justify-content-between align-items-center">
                                Sign In to {{ session('storename') }}
                                <small class="fw-400 text-end font-size:12">
                                    <a href="{{ route('switch_account') }}" class="text-decoration-none">
                                        Switch Account
                                    </a>
                                </small>
                            </legend>
                            <hr>
                            <div class="mb-4">
                                <label for="email" class="text-uppercase text-color:black">
                                    Email Address
                                </label>
                                <input id="email" name="email" type="text"
                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       value="{{ old('email') }}"
                                       autofocus>
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-4 position-relative">
                                <label for="password" class="text-uppercase text-color:black">Password</label>
                                <div class="input-group mb-3 rounded align-items-center shadow-none"
                                     x-data="{ show: false }">
                                    <input type="password" id="password" name="password" class="form-control"
                                           :type="!show ? 'password' : 'text'">
                                    <button class="text-color:black btn border-0 shadow-0 hover:shadow-none pe-0"
                                            type="button"
                                            @click="show = !show">
                                        <i class="ri-fw text-color:black"
                                           :class="show ? 'ri-eye-line' : 'ri-eye-close-line'"></i>
                                    </button>
                                </div>
                                <a href="{{ route('password.request') }}" class="position-absolute text-decoration-none"
                                   style="top: 0;right: 0;">
                                    <small>
                                        Forgot Password?
                                    </small>
                                </a>
                            </div>
                            <button type="submit"
                                    class="btn btn-primary w-100 d-block text-center mb-4 d-flex align-items-center">
                                Sign In
                            </button>
                            <div class="text-center font-size:12">
                                Looking to create an account instead?
                                <a href="{{ route('register') }}" class="text-decoration-none">
                                    Create Account <i class="ri-arrow-right-line align-middle ri-fw d-inline-block"
                                                      style="margin-top: -2px;"></i>
                                </a>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </form>
        @else
            <form method="post" action="{{ route('check_store') }}" x-data="{ username: '{{ old('username') }}' }">
                {{ csrf_field() }}
                <div class="card mx-auto p-md-5 position-relative" style="border-radius: 10px;max-width: 445px">
                    <div class="position-absolute" style="bottom: -30px;left: 0;z-index: -1;width: 100%;">
                        <img src="{{ asset('images/bg-card-decor.png') }}" alt=""
                             class="img-fluid mx-auto d-block w-100"
                             style="max-width: 400px;">
                    </div>
                    <div class="card-body p-0">
                        @if (session('status'))
                            <x-alert type="success" dismissible icon>
                                {{ session('status') }}
                            </x-alert>
                        @endif
                        @if (session('message'))
                            <x-alert type="success" dismissible icon>
                                {{ session('message') }}
                            </x-alert>
                        @endif
                        <fieldset>
                            <legend class="fw-600 font-poppins font-size:22">
                                Sign In to your account
                            </legend>
                            <hr>
                            <div class="mb-4">
                                <label for="username" class="text-uppercase text-color:black">
                                    Account Name
                                </label>
                                <input id="username" name="username" type="text"
                                       class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                       value="{{ old('username') }}"
                                       autofocus required
                                       @keydown.space.prevent
                                       x-model="username">
                                @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <button class="btn btn-primary w-100 d-block text-center mb-4"
                                    type="submit"
                                    disabled
                                    :disabled="!username">
                                Continue
                            </button>
                            <div class="text-center font-size:12">
                                Looking to create an account instead?
                                <a href="{{ route('register') }}" class="text-decoration-none">
                                    Create Account <i class="ri-arrow-right-line align-middle ri-fw d-inline-block"
                                                      style="margin-top: -2px;"></i>
                                </a>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection
