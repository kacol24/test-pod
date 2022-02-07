$(function(){
  $(document).on("change","select[name='billing_country']",function() {
    if($(this).val() == 'Indonesia') {
      $(".billing-address-area-container").html('<div class="col-md-8 reset-text"><div class="form-group"><label>'+labelcityorsubdistrict+'</label><select class="form-control" name="select_area_billing" required="true" data-parsley-error-message="'+labelvalidasisearch+'"></select><input type="hidden" name="billing_state"><input type="hidden" name="billing_city"><input type="hidden" name="billing_subdistrict"></div></div><div class="col-md-4 no-pad-right"><div class="form-group"><label>'+labelpostcode+'</label><select class="form-control" name="billing_postcode" required="true" data-parsley-error-message="'+validasipostcode+'"></select></div></div>');
      billing_setup_select2();
      billing_postcode_select2(data = []);
    }else {
      $(".billing-address-area-container").html('<div class="col-md-6 reset-text"><div class="form-group"><label for="state">'+labelstate+'</label><input class="form-control" name="billing_state" required placeholder="'+labelstate+'" data-parsley-error-message="Please fill your state"/></div></div><div class="col-md-6 no-pad-right"><div class="form-group"><label for="city">'+labelcity+'</label><input class="form-control" name="billing_city" required placeholder="'+labelcity+'" data-parsley-error-message="Please fill your city"/></div></div><div class="col-md-6 reset-text"><div class="form-group"><label for="subdistrict">'+labelsubdistrict+'</label><input class="form-control" name="billing_subdistrict" required placeholder="'+labelsubdistrict+'" data-parsley-error-message="Please fill your subdistrict"/></div></div><div class="col-md-6 no-pad-right"><div class="form-group"><label for="billing_postcode">'+labelpostcode+'</label><input class="form-control" id="billing_postcode" type="number" name="billing_postcode" placeholder="'+labelpostcode+'" data-length="5" data-parsley-error-message="Please fill your postcode" required></div></div>');
    }
  });

  $(document).on("change","select[name='shipping_country']",function() {
    if($(this).val() == 'Indonesia') {
      $(".shipping-address-area-container").html('<div class="col-md-8 reset-text"><div class="form-group"><label>'+labelcityorsubdistrict+'</label><select class="form-control" name="select_area_shipping" required="true" data-parsley-error-message="'+labelvalidasisearch+'"></select><input type="hidden" name="shipping_state"><input type="hidden" name="shipping_city"><input type="hidden" name="shipping_subdistrict"></div></div><div class="col-md-4 no-pad-right"><div class="form-group"><label>'+labelpostcode+'</label><select class="form-control" name="shipping_postcode" required="true"data-parsley-error-message="'+validasipostcode+'"></select></div></div>');
      shipping_setup_select2();
      shipping_postcode_select2(data = []);
    }else {
      $(".shipping-address-area-container").html('<div class="col-md-6 reset-text"><div class="form-group"><label for="state">'+labelstate+'</label><input class="form-control" name="shipping_state" required placeholder="'+labelstate+'" data-parsley-error-message="Please fill your state"/></div></div><div class="col-md-6 no-pad-right"><div class="form-group"><label for="city">'+labelcity+'</label><input class="form-control" name="shipping_city" required placeholder="'+labelcity+'" data-parsley-error-message="Please fill your city"/></div></div><div class="col-md-6 reset-text"><div class="form-group"><label for="subdistrict">'+labelsubdistrict+'</label><input class="form-control" name="shipping_subdistrict" required placeholder="'+labelsubdistrict+'" data-parsley-error-message="Please fill your subdistrict"/></div></div><div class="col-md-6 no-pad-right"><div class="form-group"><label for="shipping_postcode">'+labelpostcode+'</label><input class="form-control" id="shipping_postcode" type="number" name="shipping_postcode" placeholder="'+labelpostcode+'" data-length="5" data-parsley-error-message="Please fill your postcode" required></div></div>');
    }
  });
  if(indonesia_only) {
    billing_setup_select2();
    billing_postcode_select2(data = []);
    shipping_setup_select2();
    shipping_postcode_select2(data = []);
  }

  if($("select[name='billing_country']").val() == 'Indonesia') {
    $("select[name='billing_country']").change();
  }
  if($("select[name='shipping_country']").val() == 'Indonesia') {
    $("select[name='shipping_country']").change();
  }

  $(document).on("click", "input[name='same_as_billing']",function() {
    if($("input[name='same_as_billing']").is(":checked")) {
      $("input[name='shipping_name']").val($("input[name='billing_name']").val());
      $("input[name='shipping_email']").val($("input[name='billing_email']").val());
      $("input[name='shipping_phone']").val($("input[name='billing_phone']").val());
      $("textarea[name='shipping_address']").val($("textarea[name='billing_address']").val());

      $("select[name='shipping_country']").val($("select[name='billing_country']").val());
      selected_state = $("input[name='billing_state']").val();
      selected_city = $("input[name='billing_city']").val();
      selected_subdistrict = $("input[name='billing_subdistrict']").val();
      selected_subdistrict_id = $("select[name='select_area_billing']").val();
      if($("select[name='shipping_country']").val() == 'Indonesia' || indonesia_only) {
        $(".shipping-address-area-container").html('<div class="col-md-8 reset-text"><div class="form-group"><label>'+labelcityorsubdistrict+'</label><select class="form-control" name="select_area_shipping" required="true" data-parsley-error-message="'+labelvalidasisearch+'"><option value="'+selected_subdistrict_id+'" selected="true">'+selected_state+', '+selected_city+', '+selected_subdistrict+'</option></select><input type="hidden" name="shipping_state" value="'+selected_state+'"><input type="hidden" name="shipping_city" value="'+selected_city+'"><input type="hidden" name="shipping_subdistrict" value="'+selected_subdistrict+'"></div></div><div class="col-md-4 no-pad-right"><div class="form-group"><label>'+labelpostcode+'</label><select class="form-control" name="shipping_postcode" required="true"data-parsley-error-message="'+validasipostcode+'"></select></div></div>');
        $('select[name="shipping_postcode"]').html($('select[name="billing_postcode"]').html());
        $('select[name="shipping_postcode"]').val($('select[name="billing_postcode"]').val());
        shipping_setup_select2();
        shipping_postcode_select2(data = []);
      }else {
        $(".shipping-address-area-container").html('<div class="col-md-6 reset-text"><div class="form-group"><label for="state">'+labelstate+'</label><input class="form-control" name="shipping_state" value="'+selected_state+'" required placeholder="'+labelstate+'" data-parsley-error-message="Please fill your state"/></div></div><div class="col-md-6 no-pad-right"><div class="form-group"><label for="city">'+labelcity+'</label><input class="form-control" name="shipping_city" value="'+selected_city+'" required placeholder="'+labelcity+'" data-parsley-error-message="Please fill your city"/></div></div><div class="col-md-6 reset-text"><div class="form-group"><label for="subdistrict">'+labelsubdistrict+'</label><input class="form-control" name="shipping_subdistrict" value="'+selected_subdistrict+'" required placeholder="'+labelsubdistrict+'" data-parsley-error-message="Please fill your subdistrict"/></div></div><div class="col-md-6 no-pad-right"><div class="form-group"><label for="shipping_postcode">'+labelpostcode+'</label><input class="form-control" id="shipping_postcode" type="number" name="shipping_postcode" value="'+$("input[name='billing_postcode']").val()+'" placeholder="'+labelpostcode+'" data-length="5" data-parsley-error-message="Please fill your postcode" required></div></div>');        
      }
      getShippingMethod();
    }
      
  });
});

function billing_setup_select2() {
  $('select[name="select_area_billing"]').select2({
    minimumInputLength: 3,
    ajax: {
      dataType: 'json',
      url: getCitySubdistrictUrl,
      processResults: function (data) {
        return {
          results: data
        };
      }
    },
    placeholder: labelsearch,
    language: language,
    templateResult: formatRepo,
    templateSelection: formatBilling
  });
}

function shipping_setup_select2() {
  $('select[name="select_area_shipping"]').select2({
    minimumInputLength: 3,
    ajax: {
      dataType: 'json',
      url: getCitySubdistrictUrl,
      processResults: function (data) {
        return {
          results: data
        };
      }
    },
    placeholder: labelsearch,
    language: language,
    templateResult: formatRepo,
    templateSelection: formatShipping
  });
}

function shipping_postcode_select2(data = []) {
  $('select[name="shipping_postcode"]').select2({
    data: data,
    placeholder: labelpostcode,
  });
}

function billing_postcode_select2(data = []) {
  $('select[name="billing_postcode"]').select2({
    data: data,
    placeholder: labelpostcode,
  });
}

function formatRepo (item) {
  if (item.loading) {
    return item.text;
  }

  return item.state+', '+item.city+', '+item.subdistrict; 
}

function formatBilling(item) { 
  if(item.text) {
    return item.text;
  }
  $("input[name='billing_state']").val(item.state);
  $("input[name='billing_city']").val(item.city);
  $("input[name='billing_subdistrict']").val(item.subdistrict);
  $("input[name='billing_subdistrict_id']").val(item.id);
  $('select[name="billing_postcode"]').empty();
  billing_postcode_select2(item.postcodes);
  $('select[name="billing_postcode"]').select2("open");
  return item.state+', '+item.city+', '+item.subdistrict;
};

function formatShipping(item) { 
  if(item.text) {
    return item.text;
  }
  $("input[name='shipping_state']").val(item.state);
  $("input[name='shipping_city']").val(item.city);
  $("input[name='shipping_subdistrict']").val(item.subdistrict);
  $("input[name='shipping_subdistrict_id']").val(item.id);
  getShippingMethod();
  $('select[name="shipping_postcode"]').empty();
  shipping_postcode_select2(item.postcodes);
  $('select[name="shipping_postcode"]').select2("open");
  return item.state+', '+item.city+', '+item.subdistrict;
};