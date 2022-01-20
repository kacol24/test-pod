<h1 class="font-size:22 fw-600">
    {{ $order->payment }} Payment
</h1>
<x-alert icon class="font-size:12 text-start">
    Please make payment before: {{ $order->created_at->addDays(1)->format('l, j F Y H:i') }}<br>
    23 hours : 10 minutes : 8 seconds
</x-alert>
<div class="border rounded p-3 border-color:black text-start">
    <div class="text-center">
        <img src="{{ asset($paymentChannel['logo']) }}" alt="" class="img-fluid">
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
