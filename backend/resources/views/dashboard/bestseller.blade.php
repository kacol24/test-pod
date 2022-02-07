@foreach($best_sellers as $idx => $best_seller)
<?php 
  $image = '';
  if($best_seller->images->first()) {
    $image = $best_seller->images->first()->image;
  }
?>
<a href="javascript:void(0);" class="list-group-item list-group-item-action product-list-item">
    <div class="product-list-item__content">
        <div class="product-list-item__image">
            <img src="{{url(image_url('100x100',$image))}}" alt="{{$best_seller->title()}}" class="img-fluid">
        </div>
        <div class="product-list-item__info">
            <div class="product-list-item__title">
                {{$best_seller->title()}}
            </div>
            <div class="product-list-item__meta">
                IDR {{number_format($best_seller->default_sku->price,0,",",".")}}
            </div>
        </div>
    </div>
    <div class="product-list-item__counter">
        @if($best_seller->quantity)
        {{$best_seller->quantity}}
        @else
        0
        @endif
    </div>
</a>
@endforeach