@extends('layout')

@section('content')
<main class="pb-3 pb-md-5" role="main" style="">
  @foreach ($errors->all() as $error)
      <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <img src="{{asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
      </div>
  @endforeach
    <div class="container container--app" id="overview">
        <div class="row justify-content-between">
        </div>
    </div>
    <div class="container container--app mb-5">
        <div class="row justify-content-md-center">
            <div class="col col-md-6">
                <div class="row no-gutters justify-content-between mb-3">
                    <div class="col-md-auto">
                        <h1 class="page-title m-0">
                            {{Lang::get('help.sizeguide')}}
                        </h1>
                    </div>
                </div>
                <div class="card p-0 mt-3">
                    <div class="card-body">
                        @foreach(config('image') as $key => $sizes)
                            @if($key!='driver')
                            <h3 style="font-weight:bold;">{{Lang::get($key.'.'.$key)}}</h3>
                            @foreach($sizes as $title => $size)
                                @if(in_array($key, array('banner')))
                                  <h5>{{ucwords(str_replace('_', ' ', $title))}}</h5>
                                  <ul>
                                    @foreach($size as $title => $data)
                                    <li>{{ucfirst($title)}} : {{$data['w']}}x{{$data['h']}}px</li>
                                    @endforeach
                                  </ul>
                               @endif
                            @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
