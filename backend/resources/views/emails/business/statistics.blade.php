@component('mail::message')
# {{ $business->name }}, 3 bulan kemarin kamu mendapatkan:
{{ $visitor_sum }} Kunjungan

{{ $contact_sum }} Contact Clicks

{{ $coupon_sum }} Coupon Clicks

@component('mail::panel')
Rata-rata listing lain dalam 3 bulan mendapatkan:

{{ round($avg['visitor_sum']) }} Kunjungan

{{ round($avg['contact_sum']) }} Contact Clicks

{{ round($avg['coupon_sum']) }} Coupon Clicks
@endcomponent

Mau lihat performa lebih detil?
@component('mail::button', ['url' => config('app.frontend_url') . '/account/statistic/' . $business->slug])
Lihat Statistik
@endcomponent
@endcomponent
