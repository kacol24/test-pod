<h1 class="font-size:22 fw-600">
    Credit Card Payment
</h1>
<div x-data="paymentApp">
    <div class="border rounded p-3 border-color:black text-start">
        <dl class="d-flex justify-content-between font-size:12 mb-0">
            <dt class="fw-500 text-uppercase text-color:black">
                Total Payment
            </dt>
            <dd>
                <strong class="font-size:18 fw-600">
                    IDR {{ $order->formatted_total }}
                </strong>
            </dd>
        </dl>
        <dl class="d-flex justify-content-between font-size:12">
            <dt class="fw-500 text-uppercase text-color:black">
                Reference ID
            </dt>
            <dd class="font-size:14">
                {{ $order->serial_number }}
            </dd>
        </dl>
        <hr>
        <x-alert icon class="font-size:12">
            Please make payment before: {{ $order->created_at->addDays(1)->format('l, j F Y H:i') }}<br>
            23 hours : 10 minutes : 8 seconds
        </x-alert>
        <form action="{{ route('xendit.cc', ['order_id' => $order->id]) }}" id="paymentForm" method="post"
              @submit.prevent="submitForm($event)">
            @csrf
            <fieldset>
                <legend>
                    Credit Card Details
                </legend>
                <template x-if="error.isShown">
                    <x-alert icon class="font-size:12" type="danger">
                        <span x-text="error.message"></span>
                    </x-alert>
                </template>
                <div class="row">
                    <div class="mb-4">
                        <input type="text" name="cardholder" class="form-control"
                               id="cardholder" aria-label="Card Holder's Name"
                               x-model="card.name"
                               placeholder="Card Holder's Name">
                    </div>
                    <div class="mb-4">
                        <input type="tel" name="cardnumber" class="form-control"
                               id="Card Number" aria-label="Card Number"
                               x-model="card.number" maxlength="16"
                               placeholder="Card Number">
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <select name="expmonth" class="form-select"
                                    x-model="card.expMonth"
                                    aria-label="Expiry Month">
                                <option disabled selected hidden value="">
                                    Expiry Month
                                </option>
                                @foreach(range(1, 12) as $month)
                                    <option
                                        value="{{ str_pad($month, 2, 0, STR_PAD_LEFT) }}">
                                        {{ Carbon\Carbon::createFromDate(null, $month)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <select name="expyear" class="form-select"
                                    x-model="card.expYear"
                                    aria-label="Expiry Year">
                                <option disabled selected hidden value="">
                                    Expiry Year
                                </option>
                                @foreach(range(date('Y'), date('Y') + 5) as $year)
                                    <option value="{{ $year }}">
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <input type="password" name="cvv" class="form-control"
                                   id="cvv" aria-label="CVV Code"
                                   x-model="card.cvv" minlength="3" maxlength="3"
                                   placeholder="CVV Code">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <div class="form-text">
                                The 3 digits number at the back of your card
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="token_id" x-model="verifiedCard.token_id">
                <input type="hidden" name="authentication_id" x-model="verifiedCard.authentication_id">
            </fieldset>
        </form>
    </div>
    <hr class="mt-4">
    <div class="d-flex justify-content-between">
        <label class="text-uppercas fw-500 font-size:12 text-color:black d-flex align-items-center">
            Total Payment:
            <strong class="fw-600 font-size:18 ms-2">
                IDR {{ $order->formatted_total }}
            </strong>
        </label>
        <button type="submit" class="btn btn-primary px-5" form="paymentForm"
                disabled
                :disabled="disableSubmit || !isCardDetailComplete()">
            Make Payment Now
            <template x-if="disableSubmit">
                <i class="ri-loader-2-line ri-fw"></i>
            </template>
        </button>
    </div>
</div>

@push('scripts')
    <div class="overlay" style="display: none;"></div>
    <div id="three-ds-container" style="display: none;">
        <iframe id="sample-inline-frame" name="sample-inline-frame"></iframe>
    </div>
    <style>
        #three-ds-container {
            width: 550px;
            height: 450px;
            line-height: 200px;
            position: fixed;
            top: 25%;
            left: 40%;
            margin-top: -100px;
            margin-left: -150px;
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
            z-index: 10002; /* 1px higher than the overlay layer */
        }

        #sample-inline-frame {
            height: 100%;
            width: 100%;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10001;
        }

        @media only screen and (max-width: 767px) {
            #three-ds-container {
                top: 0;
                left: 0;
                margin-top: 0;
                margin-left: 0;
                width: 100%;
                height: 100%;
            }
        }
    </style>
    <script type="text/javascript" src="https://js.xendit.co/v1/xendit.min.js"></script>
    <script type="text/javascript">
        Xendit.setPublishableKey('{{ config('services.xendit.public_key') }}');

        document.addEventListener('alpine:init', () => {
            Alpine.data('paymentApp', () => ({
                originalAmount: '{{ $order->total }}',
                disableSubmit: false,
                card: {
                    name: '',
                    number: '',
                    expMonth: '',
                    expYear: '',
                    cvv: ''
                },
                verifiedCard: {
                    isVerified: false,
                    token_id: '',
                    authentication_id: ''
                },
                error: {
                    isShown: false,
                    message: ''
                },
                isCardDetailComplete: function() {
                    return this.card.name && this.card.number && this.card.expMonth && this.card.expYear &&
                        this.card.cvv;
                },
                xenditValidateCard: function() {
                    var cardVerified = 0;
                    if (Xendit.card.validateCardNumber(this.card.number)) cardVerified++;
                    if (Xendit.card.validateExpiry(this.card.expMonth, this.card.expYear)) cardVerified++;
                    if (Xendit.card.validateCvn(this.card.cvv)) cardVerified++;

                    return cardVerified;
                },
                submitForm: function(e) {
                    this.disableSubmit = true;
                    $('.overlay').show();
                    $('body').css('overflow', 'hidden');
                    var cardVerified = this.xenditValidateCard();

                    var that = this;
                    if (cardVerified !== 3) {
                        $('.overlay').hide();
                        $('body').css('overflow', 'auto');

                        this.error.message = 'Silahkan isi data pembayaran anda dengan benar';
                        this.error.isShown = true;
                        return this.disableSubmit = false;
                    }

                    Xendit.card.createToken({
                        amount: this.originalAmount,
                        card_number: this.card.number,
                        card_exp_month: this.card.expMonth,
                        card_exp_year: this.card.expYear,
                        card_cvn: this.card.cvv,
                        is_multiple_use: false
                    }, function(error, creditCardToken) {
                        if (error) {
                            that.error.message = error.message;
                            that.error.isShown = true;
                            that.disableSubmit = false;

                            return;
                        }

                        switch (creditCardToken.status) {
                            case 'VERIFIED':
                                that.verifiedCard.isVerified = true;
                                that.verifiedCard.token_id = creditCardToken.id;
                                that.verifiedCard.authentication_id = creditCardToken.authentication_id;
                                that.$nextTick(function() {
                                    e.target.submit();
                                });
                                break;
                            case 'IN_REVIEW':
                                window.open(creditCardToken.payer_authentication_url, 'sample-inline-frame');
                                $('#three-ds-container').show();
                                break;
                            default:
                                that.error.message = creditCardToken.failure_reason;
                                that.error.isShown = true;
                                that.disableSubmit = false;

                                break;
                        }
                    });
                }
            }));
        });
    </script>
@endpush
