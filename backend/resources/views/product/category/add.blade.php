@extends($theme.'::backend.layout')

@section('content')
<main class="mb-3 mb-md-5" role="main" style="" x-data="{language: '{{session('language')}}'}">
  @foreach ($errors->all() as $error)
      <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <img src="{{theme_asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
      </div>
  @endforeach
  <form class="form-validate" action="{{route('category.store')}}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
      @component($theme . '::backend.partials.page-header', [
        'title' => Lang::get('category.addproductcategory'),
        'size' => 'col-md-6'
      ])
          @if(session('store')->multi_language)
              <div class="row justify-content-end">
                  <div class="col-auto">
                      @include($theme.'::backend.partials.language')
                  </div>
              </div>
          @endif
      @endcomponent
    <div class="container container--app">
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
                                        <option
                                            value="{{$parent->id}}">{{$parent->contents->where('language',session('language'))->where('keyword','title')->first()->value}}</option>
                                        @if(isset($parent->children))
                                            @foreach($parent->children as $child1)
                                                <option
                                                    value="{{$child1->id}}">{{$parent->contents->where('language',session('language'))->where('keyword','title')->first()->value}}
                                                    - {{$child1->contents->where('language',session('language'))->where('keyword','title')->first()->value}}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" {{xCloak('id')}} x-show="language == 'id'">
                                <label class="text-uppercase" for="title_id">Nama Kategori</label>
                                <input type="text" class="form-control" name="title_id" id="title_id"
                                       placeholder="Nama Kategori"
                                       value="{{old('title_id')}}" {{validate_language('id')}}>
                            </div>

                            <div class="form-group" {{xCloak('en')}} x-show="language == 'en'">
                                <label class="text-uppercase" for="title_en">Category Name</label>
                                <input type="text" class="form-control" name="title_en" id="title_en"
                                       placeholder="Category Name"
                                       value="{{old('title_en')}}" {{validate_language('en')}}>
                            </div>
                            @if(theme_config('category.use_description'))
                            <div class="form-group" {{xCloak('id')}} x-show="language == 'id'">
                                <label for="description_id">Deskripsi Kategori</label>
                                <textarea class="form-control" name="description_id" id="description_id"
                                          rows="6"
                                          placeholder="Tulis deskripsi disini">{{old('description_id')}}</textarea>
                            </div>
                            <div class="form-group lang-en" {{xCloak('en')}} x-show="language == 'en'">
                                <label class="text-uppercase" for="description_en">Category Description</label>
                                <textarea class="form-control" name="description_en" id="description_en"
                                          rows="6"
                                          placeholder="Write your description here">{{old('description_en')}}</textarea>
                            </div>
                            @else
                                <input type="hidden" name="description_id">
                                <input type="hidden" name="description_en">
                            @endif

                            @if(theme_config('category.use_featured'))
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class="text-uppercase" for="weight">{{Lang::get('general.ordering')}}</label>
                                    <input type="text" data-parsley-type="integer" min="1" class="form-control"
                                           name="order_weight" id="weight"
                                           placeholder="{{Lang::get('general.ordering')}}"
                                           value="{{(old('order_weight')) ? old('order_weight') : 1}}">
                                </div>

                                <div class="form-group col-md-4">
                                    <label class="text-uppercase" for="weight">{{Lang::get('general.active')}}</label>
                                    <select name="is_active" class="form-control">
                                        <option @if(old('is_active')==1) selected="selected"
                                                @endif value="1">{{Lang::get('general.yes')}}</option>
                                        <option @if(old('is_active')==0) selected="selected"
                                                @endif value="0">{{Lang::get('general.no')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label class="text-uppercase" for="is_featured">{{Lang::get('category.setasfeatured')}}</label>
                                    <select name="is_featured" class="form-control">
                                        <option @if(old('is_featured')==1) selected="selected"
                                                @endif value="1">{{Lang::get('general.yes')}}</option>
                                        <option @if(old('is_featured')==0) selected="selected"
                                                @endif value="0">{{Lang::get('general.no')}}</option>
                                    </select>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="text-uppercase" for="weight">{{Lang::get('general.ordering')}}</label>
                                    <input type="text" data-parsley-type="integer" min="1" class="form-control"
                                           name="order_weight" id="weight"
                                           placeholder="{{Lang::get('general.ordering')}}"
                                           value="{{(old('order_weight')) ? old('order_weight') : 1}}">
                                </div>

                                <div class="form-group">
                                    <label class="text-uppercase" for="weight">{{Lang::get('general.active')}}</label>
                                    <select name="is_active" class="form-control">
                                        <option @if(old('is_active')==1) selected="selected"
                                                @endif value="1">{{Lang::get('general.yes')}}</option>
                                        <option @if(old('is_active')==0) selected="selected"
                                                @endif value="0">{{Lang::get('general.no')}}</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            @if(theme_config('category.use_image'))
                            <div class="form-group">
                                <label class="text-uppercase" for="sku">{{Lang::get('general.image')}}</label>
                                <div class="btn-file form-control no-pad-right">
                                    <input name="file" type="file">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="seo" role="tabpanel">
                    @include($theme.'::backend.partials.addseo')
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
