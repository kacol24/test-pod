<h1 class="font-size:22 fw-600">
    Awaiting Payment
</h1>
<img src="{{ asset('images/inky-time-is-money-1-1.png') }}" alt="" class="img-fluid my-5">
<p class="font-size:12">
    Mohon segera selesaikan pembayaran agar pesanan dapat diproses
    Kami telah mengirimkan rincian pesanan Anda ke <strong>hdewina@gmail.com</strong> dan harap
    menyelesaikan pembayaran
    dibawah ini:
</p>
<div class="border rounded p-3 border-color:black text-start">
    <strong class="font-size:16">
        Order ID: {{ $order->serial_number }}
    </strong>
    <hr>
    <dl class="d-flex justify-content-between font-size:12">
        <dt class="fw-400">
            {{ $order->payment }} Virtual Account
        </dt>
        <dd>
            <strong>
                {{ $log->account_number }}
                <a href="javascript:void(0)"
                   class="text-decoration-none fw-400" @include('partials.data-copy', ['copyText' => $log->account_number])>Copy</a>
            </strong>
        </dd>
    </dl>
    <dl class="d-flex justify-content-between font-size:12">
        <dt class="fw-400">
            Total Payment
        </dt>
        <dd>
            <strong>
                IDR {{ $order->formatted_total }}
                <a href="javascript:void(0)"
                   class="text-decoration-none fw-400" @include('partials.data-copy', ['copyText' => $order->total])>Copy</a>
            </strong>
        </dd>
    </dl>
    <x-alert icon class="font-size:12"
             x-data="countdown(new Date('{{ $order->created_at->addDays(1)->toDateTimeString() }}'))"
             x-init="init();">
        Please make payment before: {{ $order->created_at->addDays(1)->format('l, j F Y H:i') }}<br>
        <span x-text="time().hours">00</span> hours : <span x-text="time().minutes">00</span> minutes : <span
            x-text="time().seconds">00</span> seconds
    </x-alert>
</div>
<div class="accordion accordion-flush text-start mt-4" id="accordionPanelsStayOpenExample">
    <div class="accordion-item mb-4 bg-color:gray border-0">
        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
            <button class="accordion-button text-uppercase border-0" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                    aria-controls="panelsStayOpen-collapseOne">
                Cara Pembayaran
            </button>
        </h2>
        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
             aria-labelledby="panelsStayOpen-headingOne">
            <div class="accordion-body pt-0">
                <div class="mb-4">
                    <select class="form-select" aria-label="Default select example">
                        <option>Via M-BCA</option>
                        <option>Via M-BCA</option>
                        <option>Via M-BCA</option>
                        <option>Via M-BCA</option>
                    </select>
                </div>
                <ol class="list-unstyled font-size:12 custom-list">
                    <li class="mb-3">
                        <span class="numbered-bullet me-2">1</span>
                        Buka Aplikasi BCA Mobile
                    </li>
                    <li class="mb-3">
                        <span class="numbered-bullet me-2">1</span>
                        Buka Aplikasi BCA Mobile
                    </li>
                    <li class="mb-3">
                        <span class="numbered-bullet me-2">1</span>
                        Buka Aplikasi BCA Mobile
                    </li>
                    <li class="mb-3">
                        <span class="numbered-bullet me-2">1</span>
                        Buka Aplikasi BCA Mobile
                    </li>
                    <li class="mb-3">
                        <span class="numbered-bullet me-2">1</span>
                        Buka Aplikasi BCA Mobile
                    </li>
                    <li>
                        <span class="numbered-bullet me-2">1</span>
                        Buka Aplikasi BCA Mobile
                    </li>
                </ol>
            </div>
        </div>
    </div>
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
