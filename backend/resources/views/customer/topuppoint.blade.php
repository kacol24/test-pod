@extends($theme.'::backend.layout')

@section('content')
<main class="mb-3 mb-md-5" role="main" style="">
  @foreach ($errors->all() as $error)
      <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <img src="{{asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
      </div>
  @endforeach
  <form class="form-validate" action="{{route('customer.topuppoint.store', $user->id)}}" method="post" enctype="multipart/form-data">
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
                            Point - {{$user->name}}
                        </h1>
                    </div>
                    <div class="col-md-auto d-flex align-items-center">
                        @if(session('store')->multi_language)
                        @include($theme.'::backend.partials.language')
                        @endif
                    </div>
                </div>
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="blogoverview" role="tabpanel">
                    <div class="card p-0 mt-3">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-uppercase" for="type">Type</label>
                                <select class="form-control" name="type">
                                    <option value="Get">Plus</option>
                                    <option value="Use">Minus</option>
                                    <option value="Adjust">Adjust</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="text-uppercase" for="amount">{{Lang::get('customer.pointamount')}}</label>
                                <input type="text" class="form-control" name="amount" id="amount"
                                           placeholder="{{Lang::get('customer.pointamount')}}" value="{{old('amount')}}"
                                           required="true">
                            </div>
                            <div class="form-group" {{xCloak('id')}} x-show="language == 'id'">
                                <label class="text-uppercase" for="description_id">Deskripsi</label>
                                <textarea class="form-control" name="description_id" id="description_id"
                                          rows="6"
                                          placeholder="Tulis deskripsi disini" {{validate_language('id')}}>{{old('description_id')}}</textarea>
                            </div>
                            <div class="form-group" {{xCloak('en')}} x-show="language == 'en'">
                                 <label class="text-uppercase" for="description_en">Description</label>
                                <textarea class="form-control" name="description_en" id="description_en"
                                              rows="6"
                                              placeholder="Write your description here" {{validate_language('en')}}>{{old('description_en')}}</textarea>
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
                        <a class="btn btn-transparent btn-block" href="{{route('brand.list')}}">
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
