<h1 class="font-size:22 fw-600">
    {{ $order->payment }} Payment
</h1>
<x-alert icon class="font-size:12 text-start"
         x-data="countdown(new Date('{{ $order->created_at->addDays(1)->toDateTimeString() }}'))"
         x-init="init();">
    Please make payment before: {{ $order->created_at->addDays(1)->format('l, j F Y H:i') }}<br>
    <span x-text="time().hours">00</span> hours : <span x-text="time().minutes">00</span> minutes : <span
        x-text="time().seconds">00</span> seconds
</x-alert>
<div class="border rounded p-3 border-color:black text-start">
    <div class="text-center">
        <img src="{{ asset($paymentChannel['logo']) }}" alt="" class="img-fluid" style="height: 32px;">
    </div>
    <hr>
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
    <form action="{{ route('xendit.ewallet', ['order_id' => $order->id]) }}" id="paymentForm" method="post">
        @csrf
        @if($paymentChannel['bank_code'] == 'ID_OVO')
            <hr>
            <div class="mb-4">
                <label for="phone">
                    Mobile Phone Number
                </label>
                <input type="tel" name="phone" class="form-control"
                       id="phone" aria-label="Mobile Phone Number"
                       placeholder="Mobile Phone Number" required>
            </div>
        @endif
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
    <button type="submit" class="btn btn-primary px-5" form="paymentForm">
        Make Payment Now
    </button>
</div>

@push('scripts')
    <script>
        function countdown(expiry) {
            return {
                expiry: expiry,
                remaining: null,
                init() {
                    this.setRemaining()
                    setInterval(() => {
                        this.setRemaining();
                    }, 1000);
                },
                setRemaining() {
                    const diff = this.expiry - new Date().getTime();
                    this.remaining = parseInt(diff / 1000);
                },
                days() {
                    return {
                        value: this.remaining / 86400,
                        remaining: this.remaining % 86400
                    };
                },
                hours() {
                    return {
                        value: this.days().remaining / 3600,
                        remaining: this.days().remaining % 3600
                    };
                },
                minutes() {
                    return {
                        value: this.hours().remaining / 60,
                        remaining: this.hours().remaining % 60
                    };
                },
                seconds() {
                    return {
                        value: this.minutes().remaining,
                    };
                },
                format(value) {
                    return ("0" + parseInt(value)).slice(-2)
                },
                time() {
                    return {
                        days: this.format(this.days().value),
                        hours: this.format(this.hours().value),
                        minutes: this.format(this.minutes().value),
                        seconds: this.format(this.seconds().value),
                    }
                },
            }
        }
    </script>
@endpush
