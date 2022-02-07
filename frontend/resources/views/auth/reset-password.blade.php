@extends('layouts.auth')

@section('page_title', 'Reset Password')

@section('content')
    <div class="container">
        <form method="post" action="{{ route('password.update') }}">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="card mx-auto p-md-5 position-relative" style="border-radius: 10px;max-width: 445px">
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
                            Reset Password
                        </legend>
                        <hr>
                        <div class="mb-4">
                            <label for="email" class="text-uppercase text-color:black">
                                E-mail Address
                            </label>
                            <input id="email" name="email" type="email"
                                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   placeholder="Your email"
                                   required
                                   value="{{ old('email', $request->email) }}">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-4 position-relative">
                            <label for="password" class="text-uppercase text-color:black">Password</label>
                            <input type="password" id="password" name="password" required autofocus
                                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Your password">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-4 position-relative">
                            <label for="password_confirmation" class="text-uppercase text-color:black">
                                Repeat Password
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   placeholder="Retype password" required
                                   class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
                            @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button type="submit"
                                class="btn btn-primary w-100 d-block text-center mb-4 d-flex align-items-center">
                            {{ __('Reset Password') }}
                        </button>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
@endsection
