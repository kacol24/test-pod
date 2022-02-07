@extends('layout')

@section('content')
<main class="pb-3 pb-md-5" role="main">
    <div class="container container--crud">
        @if (session('status'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <img src="{{asset('backend/images/success-icon.png')}}" alt=""> {{session('status')}}
            </div>
        @endif
        <form class="form-validate" action="{{route('business.update',['id'=> $entity->id])}}" method="post"
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
                                        <div><img src="{{image_url('logo', $entity->logo)}}" width="75" style="border-radius:50%;margin-right:15px;"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-uppercase" for="item_id">Business Name</label>
                                        <div>{{$entity->name}}</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-uppercase" for="item_id">Business Email</label>
                                        <div>{{$entity->email}}</div>
                                    </div>
                                    <div class="form-group"
                                         x-data="{
                                            edit: false
                                         }">
                                        <label class="text-uppercase" for="item_id">
                                            Category
                                        </label>
                                        <template x-if="!edit">
                                            <div>
                                                {{optional($entity->firstcategory())->title}}
                                                - {{optional($entity->secondcategory())->title}}
                                                <br>
                                                <a href="#" @click="edit = true;$nextTick(function() {set_select_category()})">
                                                    Edit
                                                </a>
                                            </div>
                                        </template>
                                        <template x-if="edit">
                                            <div>
                                                <div class="dropdown">
                                                    <div class="input-group" id="dropdownCategory"
                                                         data-toggle="dropdown"
                                                         aria-haspopup="true" aria-expanded="false">
                                                        <input type="text"
                                                               class="form-control selectcategory dropdown-toggle"
                                                               placeholder="Category">
                                                        <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon1"><img
                                                            src="{{asset('images/selectcat-image.png')}}"
                                                            alt=""></span>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-menu w-100" aria-labelledby="dropdownCategory"
                                                         style="max-height: 500px; overflow-y: scroll;">
                                                        @foreach($categories as $category)
                                                            <a class="dropdown-item" href="javascript:void(0);"
                                                               tabIndex="-1">
                                                                <label><input class="category"
                                                                              @if(in_array($category->id,$selected_category)) checked="checked"
                                                                              @endif data-value="{{$category->title}}"
                                                                              id="checkbox{{$category->id}}"
                                                                              name="category_id[]"
                                                                              parent="{{$category->parent_id}}"
                                                                              type="checkbox"
                                                                              value="{{$category->id}}"/>&nbsp;{{$category->title}}
                                                                </label>
                                                            </a>
                                                            @if(isset($category->children))
                                                                @foreach($category->children as $child1)
                                                                    <a class="dropdown-item pl-5"
                                                                       href="javascript:void(0);"
                                                                       tabIndex="-1">
                                                                        <label><input class="category"
                                                                                      @if(in_array($child1->id,$selected_category)) checked="checked"
                                                                                      @endif data-value="{{$child1->title}}"
                                                                                      id="checkbox{{$child1->id}}"
                                                                                      name="category_id[]"
                                                                                      parent="{{$child1->parent_id}}"
                                                                                      type="checkbox"
                                                                                      value="{{$child1->id}}"/>&nbsp;{{$child1->title}}
                                                                        </label>
                                                                    </a>
                                                                    @if(isset($child1->children))
                                                                        @foreach($child1->children as $child2)
                                                                            <a class="dropdown-item ml-5 pl-5"
                                                                               href="javascript:void(0);">
                                                                                <label><input class="category"
                                                                                              @if(in_array($child2->id,$selected_category)) checked="checked"
                                                                                              @endif data-value="{{$child2->title}}"
                                                                                              id="checkbox{{$child2->id}}"
                                                                                              name="category_id[]"
                                                                                              parent="{{$child2->parent_id}}"
                                                                                              type="checkbox"
                                                                                              value="{{$child2->id}}"/>&nbsp;{{$child2->title}}
                                                                                </label>
                                                                            </a>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm mt-2">
                                                    Save
                                                </button>
                                            </div>
                                        </template>
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
                                                <img src="{{image_url('image', $image->image)}}" width="100" class="img-fluid">
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
            @if($entity->status == 'pending')
            <div class="card mt-3">
                <div class="form-group">
                    <label class="text-uppercase" for="reason">
                        Reason
                    </label>
                    <textarea name="reason" id="reason" class="form-control">{{ old('reason') }}</textarea>
                </div>
                <div class="row mt-4 justify-content-end align-items-center">
                    <div class="col-12 col-md-auto d-flex align-items-center mt-3 mt-md-0">
                        <button class="btn btn-default px-5 ml-3" type="submit" name="status" value="{{ \App\Models\Business\Business::STATUS_REJECTED }}">
                            Reject
                        </button>
                        <button class="btn btn-primary px-5 ml-3" type="submit" name="status" value="{{ \App\Models\Business\Business::STATUS_APPROVED }}">
                            Approve
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </form>
    </div>
</main>
@endsection

@push('scripts')
    <script>
        function set_select_category() {
            var checked = '';
            var idx = 0;
            $(".category:checked").each(function() {
                if (idx == 0)
                    checked += ($(this).attr('data-value'));
                else
                    checked += ', ' + ($(this).attr('data-value'));
                idx++;
            });

            $(".selectcategory").val(checked);
        }

        set_select_category();

        $(document).on("change", ".category", function() {
            var parent = $(this).attr("parent");
            while (parent != 0) {
                $("#checkbox" + parent).prop('checked', true);
                var parent = $("#checkbox" + parent).attr("parent");
            }
            set_select_category();
        });
    </script>
@endpush
