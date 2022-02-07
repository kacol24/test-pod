@component('mail::message')
# Hello, {{ $user->name }}!
Terima kasih telah bergabung di JPCC Business Catalog. Hanya selangkah lagi agar bisnis Anda dapat ditampilkan di website kami. Keuntungan menambahkan bisnis di website kami:
- Menggunakan halaman bisnis sebagai mini website
- Berkesempatan membuat kupon khusus yang akan ditampilkan di halaman depan website
- Berkesempatan untuk ditayangkan di akun Instagram <a href="https://instagram.com/jpccmarketplace" target="_blank">@jpccmarketplace</a>
- Melihat statistik halaman bisnis Anda
@component('mail::button', ['url' => config('app.frontend_url') . '/account'])
Daftarkan Bisnis Anda Sekarang
@endcomponent
@endcomponent
