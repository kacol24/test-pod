<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://cdn.jsdelivr.net/" crossorigin>
    <link rel="preconnect" href="https://code.jquery.com/" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com">

    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net/">
    <link rel="dns-prefetch" href="https://code.jquery.com/">

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.1/css/all.min.css">
    <link rel="stylesheet" href="{{asset('backend/css/app.css')}}">

    <title>Reset Password | Goodcommerce</title>
</head>
<body class="h-100">

<main role="main" class="h-100 d-flex align-items-center justify-content-center"
      style="background:url({{asset('backend/images/img_gradient.png')}}) no-repeat center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-auto">
                <form method="post" action="{{route('admin.resetpassword.post', $token)}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="store_id" value="{{$store_id}}">

                    <div class="card p-0 mx-auto" style="max-width: 380px">
                        <div class="card-header border-0 p-0 position-relative">
                            <img src="{{asset('backend/images/img_login_banner.jpg')}}" alt=""
                                 class="w-100 img-fluid">
                            <div
                                class="position-absolute w-100 h-100 d-flex flex-column align-items-start justify-content-end p-4"
                                style="top: 0;left: 0;">
                                <img src="{{asset('backend/images/logo-white.png')}}" alt="goodcommerce logo"
                                     class="img-fluid">
                                <h6 class="card-subtitle font-size:18 font-weight-medium text-color:white mt-2">
                                    Welcome to Goodcommerce Dashboard
                                </h6>
                            </div>
                        </div>
                        <div class="card-body p-md-4">
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <img src="{{asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
                                </div>
                            @endforeach
                            @if (session('message'))
                                <div class="alert alert-success">
                                    <a href="javascript:void(0);" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <img src="{{asset('backend/images/success-icon.png')}}"
                                         alt=""> {{session('message')}}
                                </div>
                            @endif
                            <fieldset>
                                <legend class="font-weight-normal font-size:22 text-color:primary">
                                    Reset Password
                                </legend>
                                <div class="form-group">
                                    <label for="email" class="text-uppercase">
                                        Email
                                    </label>
                                    <input id="email" name="email" type="email"
                                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                           value="{{ old('email') }}">
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group position-relative">
                                    <label for="password" class="text-uppercase">New Password</label>
                                    <input type="password" id="password" name="password" class="form-control">
                                    <a href="{{ route('admin.login') }}" class="position-absolute"
                                       style="top: 0;right: 0;">
                                        <small>
                                            Login?
                                        </small>
                                    </a>
                                </div>
                                <button class="btn btn-primary btn-block text-center">
                                    Reset Password
                                </button>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>window.jQuery || document.write('<script src="/assets/js/jquery-3.5.1.min.js"><\/script>');</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>
<script src="{{asset('backend/js/app.js')}}"></script>
</body>
</html>
