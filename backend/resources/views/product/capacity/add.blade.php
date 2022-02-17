@extends('layout')

@section('content')
    <main class="mb-3 mb-md-5" role="main" style="" x-data="{language: '{{session('language')}}'}">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <img src="{{asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
            </div>
        @endforeach
        <form class="form-validate" action="{{route('capacity.store')}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @component('partials.page-header', [
              'title' => 'Add Capacity',
              'size' => 'col-md-6'
            ])
            @endcomponent
            <div class="container container--app">
                <div class="row justify-content-md-center">
                    <div class="col col-md-6">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="blogoverview" role="tabpanel">
                                <div class="card p-0 mt-3">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="title">Title</label>
                                            <input type="text" class="form-control" name="title" id="title"
                                                   placeholder="Title"
                                                   value="{{old('title')}}">
                                        </div>

                                        <div class="form-group">
                                            <label class="text-uppercase" for="capacity">Capacity</label>
                                            <input type="text" class="form-control" name="capacity" id="capacity"
                                                   placeholder="Capacity"
                                                   value="{{old('capacity')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 justify-content-md-center">
                    <div class="col col-md-6">
                        <div class="row justify-content-md-end">
                            <div class="col col-md-auto d-flex mt-3 mt-md-0">
                                <a class="btn btn-transparent btn-block" href="{{route('capacity.list')}}">
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
