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
                <div class="card p-0">
                    <div class="card-header p-4">
                        <h3 class="card-title">
                            My Profile
                        </h3>
                        <small class="card-subtitle">
                            You can update your information here.
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="mb-4">
                                <label for="name" class="text-uppercase text-color:black">
                                    Full Name
                                </label>
                                <input id="name" name="name" type="text"
                                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       value="{{ old('name') }}">
                                @error('name')
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
                                    <div class="mb-4 mb-md-0">
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
                                    <div class="mb-4 mb-md-0">
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
                        </form>
                    </div>
                    <div class="card-footer p-4 text-end">
                        <button class="btn btn-primary ms-auto px-4">
                            Save Changes
                        </button>
                    </div>
                </div>
                <div class="card p-0 mt-4">
                    <div class="card-header p-4">
                        <h3 class="card-title">
                            Change Password
                        </h3>
                        <small class="card-subtitle">
                            You can change your password here.
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="mb-4 position-relative">
                                <label for="old_password" class="text-uppercase text-color:black">
                                    Current Password
                                </label>
                                <input type="password" id="old_password" name="old_password" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4 position-relative">
                                        <label for="password" class="text-uppercase text-color:black">
                                            New Password
                                        </label>
                                        <input type="password" id="password" name="password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4 position-relative">
                                        <label for="password_confirmation" class="text-uppercase text-color:black">
                                            Repeat New Password
                                        </label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer p-4 text-end">
                        <button class="btn btn-primary ms-auto px-4">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection