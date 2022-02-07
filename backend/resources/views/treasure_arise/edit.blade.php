@extends('layout')

@section('content')
    <main class="pb-3 pb-md-5" role="main" style=""
          x-data="{language: '{{session('language')}}', status: {{$entity->is_publish}}}">
        <div class="container container--crud">
            @if (session('status'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <img src="{{asset('backend/images/success-icon.png')}}" alt=""> {{session('status')}}
                </div>
            @endif
            <form class="form-validate" action="{{route('treasure_arise.update',['id'=> $entity->id])}}" method="post"
                  enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="product_overview" role="tabpanel">
                        <div class="card p-0 mt-3">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-file-alt"></i>
                                    <h5 class="card-title d-inline-block">
                                        User Info
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Name</label>
                                            <div>{{$entity->user->name}}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Gender</label>
                                            <div>{{$entity->user->gender}}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card p-0 mt-3">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-fw fa-file-alt"></i>
                                    <h5 class="card-title d-inline-block">
                                        Basic Info
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Logo</label>
                                            <div><img src="{{image_url('logo', $entity->logo)}}" width="75"
                                                      style="border-radius:50%;margin-right:15px;"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Business Name</label>
                                            <div>{{$entity->name}}</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Business Email</label>
                                            <div>{{$entity->email}}</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Category</label>
                                            <div>
                                                {{optional($entity->firstcategory())->title}}
                                                - {{optional($entity->secondcategory())->title}}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Location</label>
                                            <div>{{$entity->location}}</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Address</label>
                                            <div>{{$entity->address}}</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Area</label>
                                            <div>
                                                @foreach($entity->areas as $i => $area)
                                                    @if($i>0), @endif{{$area->area}}
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Description</label>
                                            <div>{{$entity->description}}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Phone</label>
                                            <div>{{$entity->phone}}</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Whatsapp</label>
                                            <div>{{$entity->whatsapp ? $entity->whatsapp : "-"}}</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Website</label>
                                            <div>{{$entity->website ? $entity->website : "-"}}</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Instagram</label>
                                            <div>{{$entity->instagram ? $entity->instagram : "-"}}</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-uppercase" for="item_id">Youtube</label>
                                            <div>{{$entity->youtube ? $entity->youtube : "-"}}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @if($entity->type == 'complete')
                            <div class="card p-0 mt-3">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fas fa-fw fa-file-alt"></i>
                                        <h5 class="card-title d-inline-block">
                                            Complete Info
                                        </h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="text-uppercase" for="item_id">Company Type</label>
                                                <div>{{$entity->company_type}}</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-uppercase" for="item_id">Company Size</label>
                                                <div>{{$entity->company_size}}</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-uppercase" for="item_id">Ownership</label>
                                                <div>{{$entity->ownership}}</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-uppercase" for="item_id">Establish Since</label>
                                                <div>{{$entity->establish_since}}</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-uppercase" for="item_id">Tags</label>
                                                <div>{{$entity->tags}}</div>
                                            </div>
                                        </div>
                                        <div class="col-6 drop-upload">
                                            <div class="form-group">
                                                <label class="text-uppercase" for="item_id">Images</label>
                                                <div class="drop-upload__container">
                                                    @foreach($entity->images as $image)
                                                        <div class="drop-upload__item">
                                                            <img src="{{image_url('image', $image->image)}}" width="100"
                                                                 class="img-fluid">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="text-uppercase" for="item_id">Links</label>
                                                @foreach($entity->links as $link)
                                                    <div>
                                                        <a href="{{$link->url}}" target="_blank">{{$link->title}}</a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if($entity->treasure_arise_status == 'pending')
                    <div class="row mt-4 justify-content-end align-items-center">
                        <div class="col-12 col-md-auto d-flex align-items-center mt-3 mt-md-0">
                            <button class="btn btn-default px-5 ml-3" type="submit" name="status"
                                    value="{{ \App\Models\Business\Business::STATUS_REJECTED }}">
                                Reject
                            </button>
                            <button class="btn btn-primary px-5 ml-3" type="submit" name="status"
                                    value="{{ \App\Models\Business\Business::STATUS_APPROVED }}">
                                Approve
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </main>
@endsection
