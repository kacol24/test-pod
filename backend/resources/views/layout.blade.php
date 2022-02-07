<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="csrf-param" content="_token"/>
        <link rel="preconnect" href="https://cdn.jsdelivr.net/" crossorigin>
        <link rel="preconnect" href="https://code.jquery.com/" crossorigin>
        <link rel="preconnect" href="https://fonts.gstatic.com">

        <link rel="dns-prefetch" href="https://cdn.jsdelivr.net/">
        <link rel="dns-prefetch" href="https://code.jquery.com/">

        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&display=swap"
              rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
              integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.6/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.18.3/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5.0.1/bootstrap-4.min.css">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" href="{{asset('css/jpcc.css')}}">

        <title>{{$page_title}} | Goodcommerce</title>

        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.4.2/dist/cdn.min.js" defer></script>
        <script type="text/javascript">
          var lang = "{{session('language')}}";
        </script>
        <style type="text/css">
            #loading{
                position:fixed;
                top:0;
                left:0;
                height:100%;
                width:100%;
                background-color:rgba(0,0,0,0.5);
                z-index:10;
                display:none;
            }
            #loading:after{
                content:url({{asset('images/loading.gif')}});
                width:80px;
                height:80px;
                position:absolute;
                top:50%;
                left:50%;
                transform:translate(-50%, -50%)
            }

            @media (min-width: 768px) {
                .mt-md-n6 {
                    margin-top: -55px !important;
                }
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/highcharts@8.2.2/highcharts.min.js"></script>
    </head>
    <body class="fixed-header">
        <div id="loading" class="loading" style="z-index:1100;"></div>
        @include('partials.menu')

        @yield('content')

        <div class="modal fade" id="modalmessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Warning Messsage</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="sub-head">

                  </div>
                </div>
                <div class="modal-footer justify-content-end">
                  <a class="btn btn-primary text-uppercase" data-toggle="modal" data-target="#modalmessage" href="#">OK</a>
                </div>
              </div>
            </div>
        </div>

        <div class="modal fade" id="modalconfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="sub-head">
                    Are you sure you want to delete this record? This action cannot be undo
                  </div>
                </div>
                <div class="modal-footer justify-content-end">
                  <a class="btn btn-default" data-toggle="modal" data-target="#modalconfirm" href="#">Cancel</a>
                  <a class="btn btn-primary text-uppercase" href="javascript:void(0);" onclick='gc_confirm()'>Confirm</a>
                </div>
              </div>
            </div>
        </div>

        <script>
            var uploadUrl = '{{route("upload.content")}}';
        </script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script>window.jQuery || document.write('<script src="{{ asset('js/jquery-3.5.1.min.js') }}"><\/script>');</script>
        <script type="text/javascript">
            $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
        </script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.6/dist/flatpickr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
        <script src="{{asset('js/bootstrap-table.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.18.3/dist/themes/bootstrap-table/bootstrap-table.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.18.3/dist/locale/bootstrap-table-id-ID.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.18.3/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.18.3/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <script src="{{asset('js/ckeditor.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
        <script>
            var BootstrapSwal = Swal.mixin({
                customClass: {
                    confirmButton: 'mx-1 btn btn-primary',
                    cancelButton: 'mx-1 btn btn-danger',
                    denyButton: 'mx-1 btn btn-warning'
                },
                buttonsStyling: false
            });
        </script>
        <script src="{{asset('js/rails-swal.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@rails/ujs@6.1.4/lib/assets/compiled/rails-ujs.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
        <script src="{{asset('js/components.js')}}"></script>
        <script src="{{asset('js/app.js')}}"></script>
        <script>
            $(document).ready(function() {
                bsCustomFileInput.init();

                $('[data-datepicker]').flatpickr({
                    enableTime: true
                });

                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
        @stack('styles')
        @stack('scripts')
    </body>
</html>
