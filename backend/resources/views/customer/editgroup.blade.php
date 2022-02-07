@extends($theme.'::backend.layout')

@section('content')
    <main class="pb-3 pb-md-5" role="main" style="">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <img src="{{asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
            </div>
        @endforeach
        <form class="form-validate" action="{{route('group.update',$group->id)}}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
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
                                    {{Lang::get('customer.editcustomergroup')}}
                                </h1>
                            </div>
                        </div>
                        <div class="card p-0 mt-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="text-uppercase" for="name">
                                        {{Lang::get('customer.groupname')}}
                                    </label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           placeholder="{{Lang::get('customer.groupname')}}" value="{{ $group->name }}"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label for="discount" class="text-uppercase">Discount (%)</label>
                                    <input type="text" class="form-control" name="discount" id="discount"
                                           {{ $group->id == 1 ? 'disabled' : '' }}
                                           placeholder="Discount" value="{{ $group->discount }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 justify-content-md-center">
                    <div class="col col-md-6">
                        <div class="row justify-content-md-end">
                            <div class="col col-md-auto d-flex mt-3 mt-md-0">
                                <a class="btn btn-transparent btn-block" href="{{route('group.list')}}">
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
