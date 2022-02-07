@extends('layouts.auth')

@section('page_title', 'Forgot Password')

@section('content')
    <div class="container">
        <form method="post" action="{{ route('password.email') }}">
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
                            Forgot Password
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
                        <button type="submit"
                                class="btn btn-primary w-100 d-block text-center mb-4 d-flex align-items-center">
                            {{ __('Email Password Reset Link') }}
                        </button>
                        <div class="text-center font-size:12">
                            Found your password?
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                Login <i class="ri-arrow-right-line align-middle ri-fw d-inline-block"
                                         style="margin-top: -2px;"></i>
                            </a>
                        </div>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
@endsection

