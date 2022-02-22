// Replace the <textarea id="editor"> with a CKEditor
// instance, using default configuration.
var tableVariant = $('#table-variant').bootstrapTable();
var tableInventory = $('#table-inventory').bootstrapTable();
$(function(){
  $("#save-publish").click(function(){
    $("input[name='is_publish']").val(1);
    $("#form").submit();
  });

  $(document).on("change",".category",function() {
    var parent = $(this).attr("parent");
    while(parent!=0)
    {
      $("#checkbox"+parent).prop('checked', true);
      var parent = $("#checkbox"+parent).attr("parent");
    }
    set_select_category();
  });

  $(document).on("click",".delete-image",function() {
    $(this).parent().parent().remove();
  });

  $("#sortable").sortable({
    opacity: 0.7,
  });
  $("#sortable").disableSelection();

  $('.price').priceFormat({
    prefix: '',
    centsLimit: 0,
  });

  $("form").submit(function() {
    $(".price").each(function(){
      $(this).val($(this).unmask());
    });
  });

  $(document).on("change",".sku_input",function() {
    $("#sku"+$(this).attr("data-target")).val($(this).val());
  });
});

function set_select_category()
{
  var checked = '';
  var idx = 0;
  $(".category:checked").each(function(){
    if(idx==0)
      checked += ($(this).attr('data-value'));
    else
      checked += ', '+($(this).attr('data-value'));
    idx++;
  });

  $(".selectcategory").val(checked);
}

function generate_option() {
  option_rows = [];
  if(typeof options[0] != 'undefined')
  {
    for(var i=0;i<options[0].details.length;i++)
    {
      var detail = options[0].details[i];
      if(typeof options[1] != 'undefined' )
      {
        for(var j=0;j<options[1].details.length;j++)
        {
          var detail2 = options[1].details[j];
          var temp = {key1: detail.key, key2: detail2.key, label:[{title: options[0].title, value:detail.title}, {title: options[1].title, value:detail2.title}], sku: $("input[name='default_sku']").val(),width: $("input[name='default_width']").val(),length: $("input[name='default_length']").val(),height: $("input[name='default_height']").val(), weight: $("input[name='default_weight']").val(),selling_price : $("input[name='default_selling_price']").val(),fulfillment_cost : $("input[name='default_fulfillment_cost']").val(),
              production_cost: $("input[name='default_production_cost']").val(),stock : $("input[name='default_stock']").val(), image_no : 0}
          option_rows.push(temp);
        }
      }else
      {
        var temp = {key1: detail.key, label:[{title: options[0].title, value:detail.title}], sku: $("input[name='default_sku']").val(),width: $("input[name='default_width']").val(),length: $("input[name='default_length']").val(),height: $("input[name='default_height']").val(), weight: $("input[name='default_weight']").val(),selling_price : $("input[name='default_selling_price']").val(),fulfillment_cost : $("input[name='default_fulfillment_cost']").val(),
            production_cost: $("input[name='default_production_cost']").val(),stock : $("input[name='default_stock']").val(), image_no : 0}
        option_rows.push(temp);
      }
    }
    $(".default").addClass("disabled").attr("readonly","readonly");
    $('.disabled[data-toggle="tooltip"]').unbind("tooltip").tooltip();
  }else
  {
    $(".default").removeClass("disabled").removeAttr("readonly");
  }
  $("input[name='product_skus']").val(JSON.stringify(option_rows));
  bind_sku_to_option();
  generate_option_view();
}

function bind_sku_to_option() {
  for(var i in old_sku)
  {
    for(var j in option_rows)
    {
      if(typeof option_rows[j].key2 != 'undefined')
      {
        if(option_rows[j].key1 == old_sku[i].option_detail_key1 && option_rows[j].key2 == old_sku[i].option_detail_key2)
        {
          option_rows[j].id = old_sku[i].id;
          option_rows[j].sku = old_sku[i].sku_code;
          option_rows[j].width = old_sku[i].width;
          option_rows[j].length = old_sku[i].length;
          option_rows[j].height = old_sku[i].height;
          option_rows[j].weight = old_sku[i].weight;
          option_rows[j].production_cost = old_sku[i].production_cost;
            option_rows[j].fulfillment_cost = old_sku[i].fulfillment_cost;
          option_rows[j].selling_price = old_sku[i].selling_price;
          option_rows[j].stock = old_sku[i].stock;
          option_rows[j].image_no = old_sku[i].image_no;
        }
      }else
      {
        if(option_rows[j].key1 == old_sku[i].option_detail_key1)
        {
          option_rows[j].id = old_sku[i].id;
          option_rows[j].sku = old_sku[i].sku_code;
          option_rows[j].width = old_sku[i].width;
          option_rows[j].length = old_sku[i].length;
          option_rows[j].height = old_sku[i].height;
          option_rows[j].weight = old_sku[i].weight;
          option_rows[j].production_cost = old_sku[i].production_cost;
            option_rows[j].fulfillment_cost = old_sku[i].fulfillment_cost;
          option_rows[j].selling_price = old_sku[i].selling_price;
          option_rows[j].stock = old_sku[i].stock;
          option_rows[j].image_no = old_sku[i].image_no;
        }
      }
    }
  }
}

function generate_option_view()
{
  tableVariant.bootstrapTable('removeAll');
  tableInventory.bootstrapTable('removeAll');
  var row = '';
  var variantName = ''
  var stock = 0;
  var finding = null;
  for(var i=0;i<option_rows.length;i++) {
    variantName = '';
    stock = 0;
    for(var j=0;j<option_rows[i].label.length;j++) {
      if(j>0) {
        variantName += "<br/>";
      }
      variantName += option_rows[i].label[j].title+" : "+option_rows[i].label[j].value;
    }

      row = {
          variant: variantName,
          dimensi: '<div class="d-flex"><div class="form-group mr-3 mb-md-0"><input class="form-control" type="text" name="width' +
              i + '" style="max-width: 70px" value="' + option_rows[i].width +
              '"></div><div class="form-group mr-3 mb-md-0"><input class="form-control" type="text" name="length' +
              i + '" style="max-width: 70px" value="' + option_rows[i].length +
              '"></div><div class="form-group mb-md-0"><input class="form-control" type="text" name="height' +
              i + '" style="max-width: 70px" value="' + option_rows[i].height +
              '"></div></div>',
          weight: '<div class="form-group mb-md-0"><input class="form-control" type="text" name="weight' +
              i + '" value="' + option_rows[i].weight + '"></div>',
          stock: '<div class="form-group mb-md-0"><input class="form-control" type="number" name="stock' +
              i + '" value="' + option_rows[i].stock + '"></div>',
          production_cost: '<div class="form-group mb-md-0"><input class="form-control price" type="text" name="production_cost' +
              i + '" value="' + option_rows[i].production_cost + '"></div>',
          fulfillment_cost: '<div class="form-group mb-md-0"><input class="form-control price" type="text" name="fulfillment_cost' +
              i + '" value="' + option_rows[i].fulfillment_cost + '"></div>',
          selling_price: '<div class="form-group mb-md-0"><input class="form-control price" type="text" name="selling_price' +
              i + '" value="' + option_rows[i].selling_price + '"></div>',
          sku: '<div class="form-group mb-md-0"><input class="form-control sku_input" type="text" data-target=' +
              option_rows[i].key1 + option_rows[i].key2 + ' name="sku' + i +
              '" value="' + option_rows[i].sku + '" placeholder="SKU"></div>'
      };

    if(bind_image_to_sku) {
      var image_option = '<div class="form-group mb-md-0"><select class="form-control" name="image_no'+i+'">';
      image_option += '<option value="0">No Image</option>';
      for(var j=1;j <= $('input[name="images[]"]').length;j++) {
        if(option_rows[i].image_no == j) {
          image_option += '<option selected="selected" value="'+j+'">Image '+j+'</option>';
        }else {
          image_option += '<option value="'+j+'">Image '+j+'</option>';
        }
      }
      image_option += '</select></div>';
      row['image'] = image_option;
    }
    tableVariant.bootstrapTable('append', row);
    $('.price').priceFormat({
      prefix: '',
      centsLimit: 0,
    });
  }

  for(var i=0;i<option_rows.length;i++) {
    variantName = '';
    for(var j=0;j<option_rows[i].label.length;j++) {
      if(j>0) {
        variantName += "<br/>";
      }
      variantName += option_rows[i].label[j].title+" : "+option_rows[i].label[j].value;
    }
    row = {variant: variantName, sku: '<div class="form-group mb-md-0"><input class="form-control" disabled id="sku'+option_rows[i].key1+option_rows[i].key2+'" type="text" value="'+option_rows[i].sku+'" disabled placeholder="SKU"></div>'};

    tableInventory.bootstrapTable('append', row);
  }
}
