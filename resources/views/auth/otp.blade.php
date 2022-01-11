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
            <form action="TODO">
                <div class="card-body"
                     x-data="{
                        otpCountdown: 60,
                        otp: [
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                        ]
                     }"
                     x-init="setInterval(function() {
                        otpCountdown--;
                     }, 1000)">
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="ri-arrow-left-line align-middle"></i>
                            Back
                        </a>
                        <h3 class="card-title font-size:22 fw-600 my-3">
                            Please check you email
                        </h3>
                        <p class="font-size:12 fw-400">
                            A 6-digit code was sent was sent to <strong>juntalia@gmail.com</strong>. Enter it within 10
                            minutes.
                        </p>
                        <div class="mb-4">
                            <div class="row align-items-center">
                                <div class="col">
                                    <input type="number" x-ref="otpField1" x-model="otp[0]"
                                           class="form-control form-control-lg form-control--arrowless text-center"
                                           autofocus aria-label="otp 1" max="9" min="0"
                                           @keyup="if ($event.keyCode >= 48 && $event.keyCode <= 57) $refs.otpField2.focus()"
                                           @keyup.backspace="$refs.otpField2.focus()">
                                </div>
                                <div class="col">
                                    <input type="number" x-ref="otpField2" x-model="otp[1]"
                                           class="form-control form-control-lg form-control--arrowless text-center"
                                           aria-label="otp 2" max="9" min="0"
                                           @keyup="if ($event.keyCode >= 48 && $event.keyCode <= 57) $refs.otpField3.focus()"
                                           @keyup.backspace="$refs.otpField2.focus()">
                                </div>
                                <div class="col">
                                    <input type="number" x-ref="otpField3" x-model="otp[2]"
                                           class="form-control form-control-lg form-control--arrowless text-center"
                                           aria-label="otp 3" max="9" min="0"
                                           @keyup="if ($event.keyCode >= 48 && $event.keyCode <= 57) $refs.otpField4.focus()"
                                           @keyup.backspace="$refs.otpField3.focus()">
                                </div>
                                <div class="col px-0 text-center">
                                    -
                                </div>
                                <div class="col">
                                    <input type="number" x-ref="otpField4" x-model="otp[3]"
                                           class="form-control form-control-lg form-control--arrowless text-center"
                                           aria-label="otp 4" max="9" min="0"
                                           @keyup="if ($event.keyCode >= 48 && $event.keyCode <= 57) $refs.otpField5.focus()"
                                           @keyup.backspace="$refs.otpField4.focus()">
                                </div>
                                <div class="col">
                                    <input type="number" x-ref="otpField5" x-model="otp[4]"
                                           class="form-control form-control-lg form-control--arrowless text-center"
                                           aria-label="otp 5" max="9" min="0"
                                           @keyup="if ($event.keyCode >= 48 && $event.keyCode <= 57) $refs.otpField6.focus()"
                                           @keyup.backspace="$refs.otpField5.focus()">
                                </div>
                                <div class="col">
                                    <input type="number" x-ref="otpField6" x-model="otp[5]"
                                           class="form-control form-control-lg form-control--arrowless text-center"
                                           aria-label="otp 6" max="9" min="0"
                                           @keyup="if ($event.keyCode >= 48 && $event.keyCode <= 57) $nextTick(function() { $refs.btnNext.click() })"
                                           @keyup.backspace="$refs.otpField5.focus()">
                                </div>
                            </div>
                        </div>
                        <template x-if="otpCountdown > 0">
                            <div>
                                Resend in <span x-text="otpCountdown">60</span>s
                            </div>
                        </template>
                        <template x-if="otpCountdown <= 0">
                            <a href="" @click.prevent="otpCountdown = 60">
                                Resend
                            </a>
                        </template>
                        <button class="btn btn-primary mx-auto mt-3 px-5" x-ref="btnNext"
                                disabled
                                :disabled="!otp[0] || !otp[1] || !otp[2] || !otp[3] || !otp[4] || !otp[5]">
                            Next
                        </button>
                    </div>
                </div>
            </form>
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
