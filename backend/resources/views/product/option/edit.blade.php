@extends('layout')

@section('content')
    <main class="pb-3 pb-md-5" role="main" style="" x-data="{language: '{{session('language')}}'}">
        @component('partials.page-header', [
            'title' => Lang::get('option.editproductoption'),
            'size' => 'col-md-6'
          ])
        @endcomponent
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <img src="{{asset('images/error-icon.png')}}" alt=""> {{$error}}
            </div>
        @endforeach
        <form class="form-validate" action="{{route('option.update',['id' => $entity->id])}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="container container--app">
                <div class="row justify-content-md-center">
                    <div class="col col-md-6">
                        <div class="card p-0">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="text-uppercase" for="title_en">Option Name</label>
                                    <input type="text" class="form-control" name="title_en" id="title_en"
                                           placeholder="ex: Color, Size"
                                           value="{{$entity->title}}">
                                </div>

                                <div class="form-group value-container">
                                    <label class="text-uppercase"
                                           for="weight">{{Lang::get('option.optionvalues')}}</label>
                                    @foreach($entity->details as $idx => $detail)
                                        <div class="d-flex">
                                            <input type="text" class="form-control pull-left"
                                                   style="width:90%;" name="valueen[]" id="weight"
                                                   placeholder="ex: Red, Small" value="{{$detail->title}}">
                                            <button type="button"
                                                    class="btn btn-danger pull-left remove-value"
                                                    style="width:40px;margin-left:10px;margin-bottom:5px;">X
                                            </button>
                                        </div>
                                    @endforeach

                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" id="add-value"
                                            type="button">{{Lang::get('option.addvalue')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 justify-content-md-center">
                    <div class="col col-md-6">
                        <div class="row justify-content-md-end">
                            <div class="col col-md-auto d-flex mt-3 mt-md-0">
                                <a class="btn btn-transparent btn-block" href="{{route('option.list')}}">
                                    {{Lang::get('general.cancel')}}
                                </a>
                                <button class="btn btn-primary px-5 ml-3" type="submit">
                                    {{Lang::get('general.save')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection

@push('scripts')
    <script type="text/javascript">
        var use_option_image = 0;
        var idx = 1;
        $(function() {

            $('#add-value').click(function() {
                $('.value-container').append(
                    '<div class="d-flex"><input type="text" class="form-control pull-left" style="width:90%;" name="valueen[]" id="weight" placeholder="ex: Red" value=""><button type="button" class="btn btn-danger pull-left remove-value" style="width:40px;margin-left:10px;margin-bottom:5px;">X</button></div>');

                set_display_lang();
            });

            $('.value-container').on('click', '.remove-value', function(e) { //user click on remove text
                e.preventDefault();
                $(this).parent('div').remove();
            });
        });
    </script>
@endpush
