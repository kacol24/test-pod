@extends('layouts.layout')

@section('content')
    <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Add new Address
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="name" class="text-uppercase text-color:black">
                                        Recipient's Full Name
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
                            </div>
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
                                    <label for="address_label" class="text-uppercase text-color:black">
                                        Address Label
                                    </label>
                                    <input id="address_label" name="address_label" type="tel"
                                           class="form-control {{ $errors->has('address_label') ? 'is-invalid' : '' }}"
                                           value="{{ old('address_label') }}">
                                    @error('address_label')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="country" class="text-uppercase text-color:black">
                                        Country
                                    </label>
                                    <select class="form-select" id="country" name="country">
                                        <option selected>Select Province</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                    @error('phone')
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
                                    <label for="address" class="text-uppercase text-color:black">
                                        Address
                                    </label>
                                    <textarea name="address" id="address" style="height: 120px;"
                                              class="form-control"></textarea>
                                    @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="province" class="text-uppercase text-color:black">
                                        Province/State
                                    </label>
                                    <select class="form-select" id="province">
                                        <option selected>Illustrator/Designer</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="city" class="text-uppercase text-color:black">
                                        City
                                    </label>
                                    <select class="form-select" id="city">
                                        <option selected>Illustrator/Designer</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="postcode" class="text-uppercase text-color:black">
                                        Post Code
                                    </label>
                                    <input id="postcode" name="postcode" type="postcode"
                                           class="form-control {{ $errors->has('postcode') ? 'is-invalid' : '' }}"
                                           value="{{ old('postcode') }}">
                                    @error('postcode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a href=""
                                   class="btn btn-default mt-4 d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="ri-map-pin-2-line"></i>
                                        Select on map
                                    </div>
                                    <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save address</button>
                </div>
            </div>
        </div>
    </div>
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
                            Address
                        </h3>
                        <small class="card-subtitle">
                            Your saved addresses.
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($addresses as $address)
                                <div class="col-md-6 mb-4">
                                    <div class="card p-0 card--address-default">
                                        <div class="card-header border-0 pb-0 p-3 d-flex justify-content-end">
                                            <div class="d-flex align-items-center">
                                                <a href="" class="text-color:black text-decoration-none me-3">
                                                    <i class="ri-pencil-line ri-fw ri-lg align-middle"></i>
                                                </a>
                                                <div class="dropdown">
                                                    <a href="" class="text-color:black text-decoration-none"
                                                       data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-line ri-fw ri-lg align-middle"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li>
                                                            <a class="dropdown-item font-size:12 fw-500 p-4" href="#">
                                                                Make Default Address
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item font-size:12 fw-500 p-4" href="#">
                                                                Delete Address
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <h4 class="card-title font-size:14 fw-500">
                                                Home
                                            </h4>
                                            <address class="font-size:12 m-0">
                                                Junitalia<br>
                                                08996782233<br>
                                                Jl.Pattimura blok D2 no. 11,<br>
                                                Jakarta Pusat, DKI Jakarta,<br>
                                                Indonesia 16143
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-6 mb-4">
                                <a href="#addressModal" class="text-decoration-none" data-bs-toggle="modal">
                                    <div class="card card--address-placeholder" style="height: 192px;">
                                        <div class="d-flex align-items-center">
                                            <i class="me-3 ri-add-line ri-xl ri-fw d-flex align-items-center justify-content-center rounded-circle"
                                               style="margin-top: -5px;width: 45px;height: 45px;background: rgba(22, 101, 216, 0.1);"></i>
                                            <span class="text-color:black">
                                                Add New Address
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
