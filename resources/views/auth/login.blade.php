@extends('layouts.auth')

@section('page_title', 'Login')

@section('content')
    <div class="container">
        <form method="post" action="TODO">
            {{ csrf_field() }}
            <div class="card mx-auto p-md-5" style="border-radius: 10px;max-width: 445px">
                <div class="card-body p-2">
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
                            Sign In
                        </legend>
                        <small class="font-size:12">
                            Welcome! Please submit your credentials to login.
                        </small>
                        <hr>
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
                        <div class="mb-4 position-relative">
                            <label for="password" class="text-uppercase text-color:black">Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                            <a href="TODO" class="position-absolute text-decoration-none"
                               style="top: 0;right: 0;">
                                <small>
                                    Forgot Password?
                                </small>
                            </a>
                        </div>
                        <button class="btn btn-primary w-100 d-block text-center mb-4">
                            Sign In
                        </button>
                        <div class="text-center font-size:12">
                            Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Create Account</a>
                        </div>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
@endsection
