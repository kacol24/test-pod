<div class="form-group" id="pin-container">
    <label class="text-uppercase">{{get_lang('general.pinlocation')}} ({{get_lang('general.optional')}})</label>
    <div class="form-input">
        <div class="map-trigger-conatiner clearfix"
             style="background:url({{asset('frontend/images/bg-map.png')}})">
            <div class="map-trigger-pin">
                <img src="{{asset('frontend/images/icon-pin.png')}}">
            </div>
            <div class="map-trigger-text">
                <p class="pin-point-note">
                    {{get_lang('general.pinnote')}}
                </p>
            </div>
            <div class="map-trigger-button">
                <button type="button" data-toggle="modal" data-target="#locationModal">
                    {{get_lang('general.tandailokasi')}}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @include($theme.'::frontend.components.modallocation')
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API')}}&libraries=places&language=id"
            type="text/javascript"></script>
    <script src="{{asset('frontend/js/location.js')}}"></script>
    <link href="{{asset('frontend/css/location.css')}}" rel="stylesheet"/>
    @if(isset($entity) && $entity->geolabel)
        <script>
            $('.map-trigger-text .pin-point-note').html("{{$entity->geolabel}}");
            $('.map-trigger-button button').html("{{get_lang('general.ubahlokasi')}}");
        </script>
    @endif
@endpush
