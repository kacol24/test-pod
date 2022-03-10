@extends('layouts.layout')

@section('content')

    <div class="container">
        <div class="row justify-content-between">
            <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
                <h1 class="page-title m-0">
                    {{ __('Edit Product') }}
                </h1>
            </div>
            <div class="col-12 col-md">
                <div class="row justify-content-end">
                    <div class="col-auto mt-3 mt-md-0">
                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-default w-100 dropdown-toggle"
                                    data-bs-toggle="dropdown" id="dropdownMenuButton" type="button">
                                Active
                            </button>
                            <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);">Active</a>
                                <a class="dropdown-item" href="javascript:void(0);">Inactive</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-auto">
                        <a class="btn btn-default btn-block"
                           href="{{route('design.destroy', 1)}}">
                            <i class="fas fa-fw fa-trash"></i>
                            {{ __('Delete') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 sticky-top sticky-top--header">
                <div class="card p-0 mt-3" style="background: #F6F7F9;">
                    <div class="card-header border-0 d-flex align-items-center justify-content-between"
                         style="background: #F6F7F9;">
                        <div class="text-nowrap mr-3">
                            <i class="fas fa-fw fa-image"></i>
                            <h5 class="card-title d-inline-block">
                                Product Detail
                            </h5>
                        </div>
                    </div>
                    <div class="card-body pb-0" style="max-height: 400px;overflow-y: scroll;">
                        <div class="list-group">
                            @foreach(range(1, 5) as $list)
                                <div
                                    class="list-group-item p-3 mb-4 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('images/product-thumbnail.png') }}" alt=""
                                             class="img-fluid me-3"
                                             width="42">
                                        T-Shirt
                                    </div>
                                    <div class="d-flex font-size:12">
                                        <a href="" class="text-color:blue text-decoration-none">
                                            Remove
                                        </a>
                                        <div class="mx-2 text-color:icon">|</div>
                                        <a href="" class="text-color:blue text-decoration-none">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer border-0 pt-0" style="background: #F6F7F9;">
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <a href="" class="text-color:blue text-decoration-none">
                                Back
                            </a>
                            <div>
                                <strong>5 Products</strong> selected
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card p-0 mt-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="text-nowrap mr-3">
                            <i class="fas fa-fw fa-image"></i>
                            <h5 class="card-title d-inline-block">
                                Product Detail
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="" class="text-color:black text-uppercase">
                                        Title
                                    </label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="mb-4">
                                    <label for="" class="text-color:black text-uppercase">
                                        Product Code (SKU)
                                    </label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="mb-4 mb-md-0">
                                    <label for="" class="text-color:black text-uppercase">
                                        Visibility
                                    </label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected>Public</option>
                                        <option value="1">Draft</option>
                                        <option value="2">Private</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="">
                                    <label for="" class="text-color:black text-uppercase">
                                        Description
                                    </label>
                                    <textarea class="form-control h-100" id="exampleFormControlTextarea1"
                                              rows="9"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-0 mt-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-fw fa-cubes"></i>
                            <h5 class="card-title d-inline-block">
                                Stores
                            </h5>
                        </div>
                        <div class="font-size:14 text-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value=""
                                       id="select_all">
                                <label class="form-check-label" for="select_all">
                                    Select All
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div
                                    class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                               id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Tokopedia
                                        </label>
                                    </div>
                                </div>
                                <div
                                    class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                               id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Tokopedia
                                        </label>
                                    </div>
                                </div>
                                <div
                                    class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                               id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Tokopedia
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
