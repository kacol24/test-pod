@extends('layout')

@php($entitySingular = 'banner')
@php($entityPlural = 'banners')

@section('content')
    <main class="pb-3 pb-md-5" role="main" style="">
        <form class="form-validate" action="{{route($entitySingular . '.store')}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="container pb-5"
                 x-data='{
                    language: "{{session('language')}}",
                    bannerSizes: @json(config('image.banner')),
                    bannerType: "{{ array_key_first($types) }}"
                 }'>
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <img src="{{asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            @component('partials.page-header', [
                            'title' => Lang::get($entitySingular . '.addbanner'),
                        ])
                                <div class="row justify-content-end">
                                    <div class="col-auto">

                                    </div>
                                </div>
                            @endcomponent
                        </div>
                        <div class="card p-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-image"></i>
                                    <h5 class="card-title d-inline-block">
                                        {{Lang::get('banner.desktopimage')}}
                                    </h5>
                                </div>
                                <small class="text-color:tertiary font-size:14" x-cloak>
                                    Image format .jpg or .png at <span
                                        x-text="Object.values(bannerSizes[bannerType].desktop).join('x')">800x600</span>
                                    px, saved for web, 72 dpi.
                                </small>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="desktop_image"
                                               name="desktop_image" required>
                                        <label class="custom-file-label" for="desktop_image">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card p-0 mt-3">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-image"></i>
                                    <h5 class="card-title d-inline-block">
                                        {{Lang::get('banner.mobileimage')}}
                                    </h5>
                                </div>
                                <small class="text-color:tertiary font-size:14" x-cloak>
                                    Image format .jpg or .png at <span
                                        x-text="Object.values(bannerSizes[bannerType].mobile).join('x')">800x600</span>
                                    px, saved for web, 72 dpi.
                                </small>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="mobile_image"
                                               name="mobile_image" required>
                                        <label class="custom-file-label" for="mobile_image">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card p-0 mt-3">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-image"></i>
                                    <h5 class="card-title d-inline-block">
                                        Publishing Detail
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-row align-items-center">
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="weight">
                                                Start Date
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="start_date" data-datepicker
                                                       placeholder="{{Lang::get('general.startdate')}}" required
                                                       id="start_date" aria-label="start date"
                                                       value="{{old('start_date')}}"/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-fw fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto" style="padding-top:13px;">-</div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="weight">
                                                End Date
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="end_date" data-datepicker
                                                       placeholder="{{Lang::get('general.enddate')}}"
                                                       id="end_date" aria-label="start date"
                                                       value="{{old('end_date')}}"/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-fw fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="weight">
                                                {{Lang::get('general.ordering')}}
                                            </label>
                                            <select class="form-control" name="order_weight">
                                                @for($i=1;$i<=20;$i++)
                                                <option>{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="sticky-top" style="top: 85px;">
                            <div class="form-group">
                                <label for="type" class="text-color:gray font-weight-medium font-size:16">
                                    {{ __('banner.banner_type') }}
                                </label>
                                <select name="type" id="type" class="custom-select" x-model="bannerType">
                                    @foreach($types as $key => $size)
                                        <option value="{{$key}}">
                                            {{ucwords(str_replace('_', ' ', $key))}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="title">{{Lang::get('banner.title')}}</label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Titlte"
                                       value="{{old('title')}}" required>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="url" class="text-color:gray font-weight-medium font-size:16">
                                    Link Url
                                </label>
                                <input type="text" class="form-control" name="url" id="url"
                                       placeholder="ex: http://google.com" value="{{old('url')}}">
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="is_active" class="text-color:gray font-weight-medium font-size:16">
                                    {{ __('general.active') }}
                                </label>
                                <select name="is_active" class="form-control" id="is_active">
                                    <option @if(old('is_active')==1) selected="selected" @endif value="1">
                                        {{ __('general.yes') }}
                                    </option>
                                    <option @if(old('is_active')==0) selected="selected" @endif value="0">
                                        {{ __('general.no') }}
                                    </option>
                                </select>
                            </div>
                            <hr>
                            <div class="form-group">
                                <a href="{{ route($entitySingular . '.index') }}" class="btn btn-transparent btn-block">
                                    Cancel
                                </a>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
