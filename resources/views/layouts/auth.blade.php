<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://cdn.jsdelivr.net/" crossorigin>
    <link rel="preconnect" href="https://code.jquery.com/" crossorigin>

    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net/">
    <link rel="dns-prefetch" href="https://code.jquery.com/">

    {{--    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&display=swap" rel="stylesheet">--}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">--}}
    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.1/css/all.min.css">--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.min.css">
    <link rel="stylesheet" href="{{asset('backend/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/main.css')}}">

    <title>@yield('page_title', 'Welcome') | {{ config('app.name') }}</title>
</head>
<body class="h-100 bg-color:blue-3">

<main role="main" class="h-100 d-flex align-items-center justify-content-center">
    @yield('content')
</main>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>window.jQuery || document.write('<script src="{{ asset('js/jquery-3.5.1.min.js') }}"><\/script>');</script>
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>--}}
</body>
</html>
