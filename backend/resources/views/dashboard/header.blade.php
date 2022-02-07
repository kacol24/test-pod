<div class="widget-carousel__slide">
    <div class="widget text-center" style="position:relative;">
        <div class="widget__icon mx-auto">
            <img src="{{asset('images/icons/icon_gross_income.png')}}" alt="icon gross income"
                 class="img-fluid">
        </div>
        <h3 class="widget__title">
            TOTAL BUSINESSES
        </h3>
        <?php
        if (! $total_business['last_period']['sum'])
            $total_business['last_period']['sum'] = $total_business['sum'];
        $selisih = $total_business['sum'] - $total_business['last_period']['sum'];
        if ($selisih && $total_business['last_period']['sum']) {
            $diff = $selisih / $total_business['last_period']['sum'] * 100;
        } else {
            $diff = 0;
        }
        ?>
        <div class="widget__body">
            {{number_format($total_business['sum'],0,",",".")}}
        </div>
        @if($selisih>0)
            <div class="widget__meta text-color:green">
                +{{number_format($diff,2,",",".")}}%
            </div>
        @else
            <div class="widget__meta text-color:red">
                -{{number_format($diff,2,",",".")}}%
            </div>
        @endif
        <div x-show="loading" class="loading-dashboard" style="background-color: rgba(255, 255, 255, 1);"></div>
    </div>
</div>
<div class="widget-carousel__slide">
    <div class="widget text-center" style="position:relative;">
        <div class="widget__icon mx-auto">
            <img src="{{asset('images/icons/icon_transaction.png')}}" alt="icon transactions"
                 class="img-fluid">
        </div>
        <h3 class="widget__title">
            TOTAL BASIC BUSINESSES
        </h3>
        <div class="widget__body">
            {{number_format($total_basic_business['sum'],0,",",".")}}
        </div>
        <?php
        if (! $total_basic_business['last_period']['sum'])
            $total_basic_business['last_period']['sum'] = $total_basic_business['sum'];
        $selisih = $total_basic_business['sum'] - $total_basic_business['last_period']['sum'];
        if ($selisih && $total_basic_business['last_period']['sum']) {
            $diff = $selisih / $total_basic_business['last_period']['sum'] * 100;
        } else {
            $diff = 0;
        }
        ?>
        @if($selisih>0)
            <div class="widget__meta text-color:green">
                +{{number_format($diff,2,",",".")}}%
            </div>
        @else
            <div class="widget__meta text-color:red">
                -{{number_format($diff,2,",",".")}}%
            </div>
        @endif
        <div x-show="loading" class="loading-dashboard" style="background-color: rgba(255, 255, 255, 1);"></div>
    </div>
</div>
<div class="widget-carousel__slide">
    <div class="widget text-center" style="position:relative;">
        <div class="widget__icon mx-auto">
            <img src="{{asset('images/icons/icon_conversion.png')}}" alt="icon conversion rate"
                 class="img-fluid">
        </div>
        <h3 class="widget__title">
            TOTAL COMPLETE BUSINESSES
        </h3>
        <div class="widget__body">
            {{number_format($total_complete['sum'],0,",",".")}}
        </div>
        <?php
        $selisih = $total_complete['sum'] - $total_complete['last_period']['sum'];
        if ($selisih && $total_complete['last_period']['sum']) {
            $diff = $selisih / $total_complete['last_period']['sum'] * 100;
        } else {
            $diff = 0;
        }
        ?>
        @if($selisih>0)
            <div class="widget__meta text-color:green">
                +{{number_format($diff,2,",",".")}}%
            </div>
        @else
            <div class="widget__meta text-color:red">
                -{{number_format($diff,2,",",".")}}%
            </div>
        @endif
        <div x-show="loading" class="loading-dashboard" style="background-color: rgba(255, 255, 255, 1);"></div>
    </div>
</div>
<div class="widget-carousel__slide">
    <div class="widget text-center" style="position:relative;">
        <div class="widget__icon mx-auto">
            <img src="{{asset('images/icons/icon_viewed_purple.png')}}" alt="icon viewed products"
                 class="img-fluid">
        </div>
        <h3 class="widget__title">
            TOTAL MEMBER
        </h3>
        <div class="widget__body">
            {{number_format($total_member['sum'],0,",",".")}}
        </div>
        <?php
        if (! $total_member['last_period']['sum']) {
            $total_member['last_period']['sum'] = $total_member['sum'];
        }
        $selisih = $total_member['sum'] - $total_member['last_period']['sum'];
        if ($selisih && $total_member['last_period']['sum']) {
            $diff = $selisih / $total_member['last_period']['sum'] * 100;
        } else {
            $diff = 0;
        }
        ?>
        @if($selisih>0)
            <div class="widget__meta text-color:green">
                +{{number_format($diff,2,",",".")}}%
            </div>
        @else
            <div class="widget__meta text-color:red">
                -{{number_format($diff,2,",",".")}}%
            </div>
        @endif
        <div x-show="loading" class="loading-dashboard" style="background-color: rgba(255, 255, 255, 1);"></div>
    </div>
</div>
<div class="widget-carousel__slide">
    <div class="widget text-center" style="position:relative;">
        <div class="widget__icon mx-auto">
            <img src="{{asset('images/icons/icon_sold.png')}}" alt="icon sold products"
                 class="img-fluid">
        </div>
        <h3 class="widget__title">
            TOTAL TREASURE ARISE BUSINESSES
        </h3>
        <div class="widget__body">
            {{number_format($total_treasure_arise['sum'],0,",",".")}}
        </div>
        <?php
        if (! $total_treasure_arise['last_period']['sum'])
            $total_treasure_arise['last_period']['sum'] = $total_treasure_arise['sum'];
        $selisih = $total_treasure_arise['sum'] - $total_treasure_arise['last_period']['sum'];
        if ($selisih && $total_treasure_arise['last_period']['sum']) {
            $diff = $selisih / $total_treasure_arise['last_period']['sum'] * 100;
        } else {
            $diff = 0;
        }
        ?>
        @if($selisih>0)
            <div class="widget__meta text-color:green">
                +{{number_format($diff,2,",",".")}}%
            </div>
        @else
            <div class="widget__meta text-color:red">
                -{{number_format($diff,2,",",".")}}%
            </div>
        @endif
        <div x-show="loading" class="loading-dashboard" style="background-color: rgba(255, 255, 255, 1);"></div>
    </div>
</div>
