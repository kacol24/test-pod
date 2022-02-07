@foreach($most_views as $idx => $most_view)
<?php 
  $image = '';
  if($most_view['product']->images->first()) {
    $image = $most_view['product']->images->first()->image;
  }
?>
<a href="javascript:void(0);" class="list-group-item list-group-item-action product-list-item">
    <div class="product-list-item__content">
        <div class="product-list-item__image">
            <img src="{{url(image_url('100x100',$image))}}" alt="{{$most_view['product']->title()}}" class="img-fluid">
        </div>
        <div class="product-list-item__info">
            <div class="product-list-item__title">
                {{$most_view['product']->title()}}
            </div>
            <div class="product-list-item__meta">
                IDR {{number_format($most_view['product']->default_sku->price,0,",",".")}}
            </div>
        </div>
    </div>
    <div class="product-list-item__counter">
        @if($most_view['view'])
        {{$most_view['view']}}
        @else
        0
        @endif
    </div>
</a>
@endforeach