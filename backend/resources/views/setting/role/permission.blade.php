@extends('layout')

@section('content')
<main class="pb-3 pb-md-5" role="main" style="">
  @foreach ($errors->all() as $error)
      <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <img src="{{asset('images/error-icon.png')}}" alt=""> {{$error}}
      </div>
  @endforeach
  <form id="form" class="form-validate" action="{{route('permission.update',$id)}}" method="post"
          enctype="multipart/form-data">
        {{ csrf_field() }}
    <div class="container container--app" id="overview">
        <div class="row justify-content-between">
        </div>
    </div>
    <div class="container container--app mb-5">
        <div class="row justify-content-md-center">
            <div class="col col-md-9">
                <div class="row no-gutters justify-content-between mb-3">
                    <div class="col-md-auto">
                        <h1 class="page-title m-0">
                            {{Lang::get('admin.editrolepermission')}}
                        </h1>
                    </div>
                </div>
                <div class="card p-0 mt-3">
                  @foreach($features as $feature)
                  <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="card-title d-inline-block">
                          {{$feature->title}}
                      </h5>
                  </div>
                  <div class="card-body" style="padding-bottom:0;">
                    <div class="row">
                    @foreach($feature->permissions as $permission)
                      <div class="form-group col-3">
                          <input type="checkbox"
                                 @if(in_array($permission->id, $role_permissions)) checked="checked"
                                 @endif name="permission[]" value="{{$permission->id}}"
                                 id="checkbox{{$permission->id}}">
                          <label for="checkbox{{$permission->id}}">
                              {{$permission->title}}
                          </label>
                      </div>
                    @endforeach
                    </div>
                  </div>
                  @endforeach
                </div>
            </div>
        </div>
        <div class="row mt-4 justify-content-md-center">
            <div class="col col-md-9">
                <div class="row justify-content-md-end">
                    <div class="col col-md-auto d-flex mt-3 mt-md-0">
                        <a class="btn btn-transparent btn-block" href="{{route('role.list')}}">
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