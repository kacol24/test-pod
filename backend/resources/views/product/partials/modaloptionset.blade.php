<div class="modal fade" id="modaloptionset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Option Set</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body clearfix">
        <div class="sub-head" id="optionset-container">
          <select class="form-control mtop15" id="select-option-set">
            <option value="0">Select option set</option>
            @foreach($option_sets as $option_set)
            <option value="{{$option_set->id}}">{{$option_set->title}}</option>
            @endforeach
          </select>
        </div>
        <div class="attribute-wrapper clearfix option-container"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="generate-sku-set">Save changes</button>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.bootstrap3.min.css">
<script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>
<script type="text/javascript">
  $(function(){
    var select_options = <?php echo json_encode($options); ?>;

    $(document).on("change","#select-option-set", function(){
      $("#loading").show();
      $.ajax({
        url: '{{route("product.getoption")}}', // point to server-side PHP script 
        dataType: 'json',
        data: {
          set_id : $(this).val()
        },                         
        type: 'get',
        success: function(data){
          if(data['status'] == 'error')
          {
            alert(data['message']);
          }
          
          $(".option-container").html(data['value']);
          checkParent();
          $("#loading").hide();
        }
      });
    });

    $(document).on("click","#generate-sku-set", function(){
      options = [];
      $("input[name='options[]']:checked").each(function(){
        var option = {title_id: $(this).attr('title-id'), title_en: $(this).attr('title-en'), title: $(this).attr('title')};
        var details = [];
        $("input[parent='"+$(this).attr('id')+"']:checked").each(function(){
          var detail = {title_id: $(this).attr('title-id'), title_en: $(this).attr('title-en'), title: $(this).attr('title'), key: $(this).attr('key'), image: $(this).attr('image')};
          details.push(detail);
        });
        option.details = details;
        options.push(option);
      });
        
      $('#modaloptionset').modal('hide');
      $("input[name='product_options']").val(JSON.stringify(options));
      generate_option();
    });
  });

  function checkParent()
  {
    $(".option-detail").unbind('click').click(function(){
      var parent = $(this).attr('parent');
      if($("input[parent='"+parent+"']:checked").length >0)
      {
        $("#"+parent).prop('checked',true);
      }else
      {
        $("#"+parent).prop('checked',false);
      }
      
    });
  }
</script>

<style type="text/css">
  .form-control.selectize-control {
    width: 25%;
    margin-right: 15px;
  }
</style>