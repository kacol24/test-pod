<div class="modal" tabindex="-1" id="locationModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{get_lang('general.pinlocation')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label style="color:red;">{{get_lang('general.pinmessage')}}</label>
        
        <div class="map-container">
          <div id="map">
          
          </div>
          <span class="pinpoint" id="pinpoint">{{get_lang('general.uselocation')}}<span class="arrow"></span></span>
        </div>
      </div>
    </div>
  </div>
</div>