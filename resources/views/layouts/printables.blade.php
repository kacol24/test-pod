<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://cdn.jsdelivr.net/" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net/">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.min.css">
    <link rel="stylesheet" href="{{asset('backend/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/main.css')}}">
    @stack('styles')
    <title>@yield('page_title', 'Welcome') | {{ config('app.name') }}</title>
    <style>
        .page-break-inside-avoid {
            page-break-inside: avoid;
        }

        .page-break-after-always {
            page-break-after: always;
        }
    </style>
</head>
<body>

<main role="main" class="p-3">
    @yield('content')
</main>

@stack('scripts')
</body>
</html>
