@component('mail::message')
# Hello, {{ $business->user->name }}

@if($business->status == \App\Models\Business\Business::STATUS_APPROVED)
Listing Anda telah tampil di JPCC Business Catalog. Jangan lupa lengkapi profil bisnis Anda untuk mendapatkan fitur:
- Membuat kupon khusus yang akan ditampilkan di halaman depan website
- Melihat statistik halaman bisnis Anda
- Bisnis akan dirotasi untuk tampil di halaman depan website
@component('mail::button', ['url' => config('app.frontend_url') . '/' . $business->slug])
Kunjungi Halaman {{ $business->name }}
@endcomponent

@else
Maaf, {{ $business->name }} belum dapat tampil di JPCC Business Catalog karena <em>{{ $reason }}</em>. Silahkan perbaiki listing mengikuti informasi dari admin.
@component('mail::button', ['url' => config('app.frontend_url') . '/account'])
Edit Bisnis {{ $business->name }}
@endcomponent
@endif
@endcomponent
