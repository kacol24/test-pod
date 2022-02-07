<div class="row">
    <div class="col-md-6">
        <a href="{{ route('promotion.add',['type' => 'discount']) }}">
            <div class="card p-0">
                <div
                    class="card-header px-3 {{ $active_promo == 'discount' ? 'bg-color:blue' : 'bg-color:light-gray' }} border-0">
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" id="type_discount" name="type"
                               type="radio" value="discount"
                               readonly {{ $active_promo == 'discount' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="type_discount"></label>
                    </div>
                </div>
                <div
                    class="card-body mt-n1 pt-0 d-flex justify-content-between align-items-end {{ $active_promo == 'discount' ? 'bg-color:blue' : 'bg-color:light-gray' }} border-0">
                    <h5 class="card-title font-size:36 font-weight-normal {{ $active_promo == 'discount' ? 'text-white' : 'text-color:gray' }}">
                        Discount
                    </h5>
                    <img src="{{ asset('backend/images/icon_promo.png') }}"
                         alt="icon promo" class="img-fluid" style="height: 60px;">
                </div>
                <div
                    class="card-footer font-size:12 border-0 pt-0 mt-n2 mt-md-n3 text-uppercase font-weight-medium {{ $active_promo == 'discount' ? 'bg-color:blue text-white' : 'bg-color:light-gray text-color:gray' }}">
                    <hr>
                    Cut the price of certain products or product categories
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-6 mt-3 mt-md-0">
        <a href="{{ route('promotion.add',['type' => 'coupon']) }}">
            <div class="card p-0">
                <div
                    class="card-header px-3 {{ $active_promo == 'coupon' ? 'bg-color:blue' : 'bg-color:light-gray' }} border-0">
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" id="type_coupon" name="type"
                               type="radio" value="coupon"
                               readonly {{ $active_promo == 'coupon' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="type_coupon"></label>
                    </div>
                </div>
                <div
                    class="card-body mt-n1 pt-0 d-flex justify-content-between align-items-end border-0 {{ $active_promo == 'coupon' ? 'bg-color:blue' : 'bg-color:light-gray' }}">
                    <h5 class="card-title font-size:36 font-weight-normal {{ $active_promo == 'coupon' ? 'text-white' : 'text-color:gray' }}">
                        Coupon
                    </h5>
                    <img src="{{ asset('backend/images/icon_coupon.png') }}"
                         alt="icon promo" class="img-fluid" style="height: 60px;">
                </div>
                <div
                    class="card-footer font-size:12 border-0 pt-0 mt-n2 mt-md-n3 text-uppercase font-weight-medium {{ $active_promo == 'coupon' ? 'bg-color:blue text-white' : 'bg-color:light-gray text-color:gray' }}">
                    <hr>
                    Create Promotion Code
                </div>
            </div>
        </a>
    </div>
</div>
