@extends('layout')

@section('content')
    <main class="pb-3 pb-md-5" role="main" style="">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <img src="{{asset('images/error-icon.png')}}" alt=""> {{$error}}
            </div>
        @endforeach
        <form class="form-validate" action="{{ route('optionset.update', ['id' => $entity->id]) }}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="container container--app" id="overview">
                <div class="row justify-content-between">
                </div>
            </div>
            <div class="container container--app mb-5" x-data="{language: '{{session('language')}}'}">
                <div class="row justify-content-md-center">
                    <div class="col col-md-6">
                        <div class="row no-gutters justify-content-between mb-3">
                            <div class="col-md-auto">
                                <h1 class="page-title m-0">
                                    {{Lang::get('option.editproductoptionset')}}
                                </h1>
                            </div>
                            <div class="col-md-auto d-flex align-items-center">
                            </div>
                        </div>
                        <div class="card p-0 mt-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title_id">
                                        {{ __('option.optionsetname') }}
                                    </label>
                                    <input type="text" class="form-control" name="title" id="title_id"
                                           placeholder="ex: Size & Color" value="{{$entity->title}}" required>
                                </div>

                                <div class="row">
                                    @foreach($options as $option)
                                        <div class="col-6">
                                            <label>
                                                <input type="checkbox"
                                                       @if(in_array($option->id,json_decode($entity->value,true))) checked="checked"
                                                       @endif value="{{$option->id}}" name="selected[]">
                                                {{ $option->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 justify-content-md-center">
                    <div class="col col-md-6">
                        <div class="row justify-content-md-end">
                            <div class="col col-md-auto d-flex mt-3 mt-md-0">
                                <a class="btn btn-transparent btn-block" href="{{route('optionset.list')}}">
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
        $(function() {
            $('input[type=\'checkbox\']').click(function() {
                if ($('input[type=\'checkbox\']:checked').length == 3) {
                    $('#modalmessage .sub-head').html('{{ __('option.max_two_option_set') }}');
                    $('#modalmessage').modal('show');
                    return false;
                }
            });
        });
    </script>
@endpush
