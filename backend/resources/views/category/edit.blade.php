@extends('layout')

@section('content')
<main class="mb-3 mb-md-5" role="main" style="">
    @component('partials.page-header', [
        'title' => Lang::get('category.editproductcategory'),
        'size' => 'col-md-6'
    ])
        <div class="row justify-content-end">
            <div class="col-auto">
            </div>
        </div>
    @endcomponent
  @foreach ($errors->all() as $error)
      <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <img src="{{asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
      </div>
  @endforeach
  <form class="form-validate" action="{{route('category.update',['id' => $entity->id])}}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="container container--app" id="overview">
        <div class="row justify-content-between">
        </div>
    </div>
    <div class="container container--app mb-5">
        <div class="row justify-content-md-center">
            <div class="col col-md-6">
                <div class="card p-0">
                    <nav class="navbar navbar-expand px-0 px-lg-3 site-nav">
                        <ul class="navbar-nav nav">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#blogoverview">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#seo">
                                    SEO
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="blogoverview" role="tabpanel">
                    <div class="card p-0 mt-3">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-uppercase" for="stock">{{ __('general.set_as_parent') }}</label>
                                <select class="form-control" name="parent" id="stock">
                                    <option value="0">{{ __('general.parent') }}</option>
                                    @foreach($parents as $parent)
                                        @if($parent->id != $entity->id)
                                            <option @if($entity->parent_id==$parent->id) selected="selected"
                                                    @endif value="{{$parent->id}}">{{$parent->title}}</option>
                                        @endif
                                        @if(isset($parent->children))
                                            @foreach($parent->children as $child1)
                                                @if($child1->id != $entity->id)
                                                    <option @if($entity->parent_id==$child1->id) selected="selected"
                                                            @endif value="{{$child1->id}}">{{$parent->title}}
                                                        - {{$child1->title}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label class="text-uppercase" for="title">Category Name</label>
                                <input type="text" class="form-control" name="title" id="title"
                                       placeholder="Category Name"
                                       value="{{$entity->title}}" required>
                            </div>

                            <input type="hidden" name="order_weight" value="0">
                            <input type="hidden" name="is_featured" value="0">

                            <div class="form-group">
                                <label class="text-uppercase" for="is_active">{{Lang::get('general.active')}}</label>
                                <select name="is_active" class="form-control">
                                    <option @if($entity->is_active==1) selected="selected"
                                            @endif value="1">{{Lang::get('general.yes')}}</option>
                                    <option @if($entity->is_active==0) selected="selected"
                                            @endif value="0">{{Lang::get('general.no')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="seo" role="tabpanel">
                    @include('partials.editseo')
                  </div>
                </div>
            </div>
        </div>
        <div class="row mt-4 justify-content-md-center">
            <div class="col col-md-6">
                <div class="row justify-content-md-end">
                    <div class="col col-md-auto d-flex mt-3 mt-md-0">
                        <a class="btn btn-transparent btn-block" href="{{route('category.list')}}">
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
