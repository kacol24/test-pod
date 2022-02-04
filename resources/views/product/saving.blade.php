@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="text-center mx-auto" style="max-width: 700px">
            <img src="{{ asset('images/inky-time-is-money-1-1.png') }}" alt="" class="img-fluid my-5">
            <h1 class="font-size:22 fw-600 mb-4">
                Hold tight! Your great designs will be live shortly
            </h1>
            <p class="font-size:12 mb-5">
                Uploading times can vary depending on how many products you create.<br>
                <strong>Please do not close the tab.</strong>
            </p>
            <ul class="list-unstyled text-start mx-auto font-size:14" style="max-width: 400px"
                x-data="{
                    currentStep: 0
                }"
                x-init="
                    setInterval(function() {currentStep++}, 3000);
                    $watch('currentStep', function(value) { if (value === 4) window.location.href = '{{ route('products.saved') }}'  });
                ">
                <li class="d-flex align-items-center mb-4"
                    :class="{
                        'text-color:green': currentStep >= 1,
                        'text-muted': currentStep < 0
                    }">
                    <div style="width: 20px;height: 20px;" class="me-2">
                        <template x-if="currentStep == 0">
                            <i class="fas fa-fw fa-circle-notch fa-spin"></i>
                        </template>
                        <template x-if="currentStep >= 1">
                            <i class="fas fa-fw fa-check-circle"></i>
                        </template>
                    </div>
                    Generated product images for your listing
                </li>
                <li class="d-flex align-items-center mb-4"
                    :class="{
                        'text-color:green': currentStep >= 2,
                        'text-muted': currentStep < 1
                    }">
                    <div style="width: 20px;height: 20px;" class="me-2">
                        <template x-if="currentStep == 1">
                            <i class="fas fa-fw fa-circle-notch fa-spin"></i>
                        </template>
                        <template x-if="currentStep >= 2">
                            <i class="fas fa-fw fa-check-circle"></i>
                        </template>
                    </div>
                    Saving your product images: 1 of 5 products
                </li>
                <li class="d-flex align-items-center mb-4"
                    :class="{
                        'text-color:green': currentStep >= 3,
                        'text-muted': currentStep < 2
                    }">
                    <div style="width: 20px;height: 20px;" class="me-2">
                        <template x-if="currentStep == 2">
                            <i class="fas fa-fw fa-circle-notch fa-spin"></i>
                        </template>
                        <template x-if="currentStep >= 3">
                            <i class="fas fa-fw fa-check-circle"></i>
                        </template>
                    </div>
                    Preparing your listings
                </li>
            </ul>
        </div>
    </div>
@endsection
