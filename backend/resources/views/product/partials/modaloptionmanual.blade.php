<div class="modal fade" id="modaloptionmanual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Option Manual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body clearfix">
                <div id="custom-option-container" class="sub-head">
                    <div class="form-group value-container">
                    </div>
                    <div class="form-group">
                        <button id="add-option" type="button" class="btn btn-secondary">Add Option</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="generate-sku-manual">Save changes</button>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.bootstrap3.min.css">
<script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>
<script type="text/javascript">
    $(function() {
        var select_options = <?php echo json_encode($options); ?>;

        $('#add-option').click(function() {
            if ($('select[name=\'option_name[]\']').length < 2) {
                var view = '<div class="d-flex"><select class="form-control pull-left" name="option_name[]" data-idx="' +
                    $('select[name=\'option_name[]\']').length + '" placeholder="ex: Warna">';
                @foreach($options as $option)
                    view += '<option>{{$option->title}}</option>';
                @endforeach
                $('.value-container').append(
                    view + '</select><input type="text" data-idx="' + $('select[name=\'option_name[]\']').length +
                    '" class="form-control pull-left" style="width:60%;" name="option_value[]" placeholder="ex: Merah, Kuning" value=""><button type="button" class="btn btn-danger pull-left remove-option" style="width:40px;margin-left:10px;margin-bottom:5px;">X</button></div>');

                $('select[name=\'option_name[]\'][data-idx=\'' + ($('select[name=\'option_name[]\']').length - 1) + '\']')
                    .selectize({
                        create: true,
                        sortField: 'text',
                        persist: false,
                        addPrecedence: true,
                        selectOnTab: true
                    }).on('change', function() {
                    var data_options = [];
                    for (var i = 0; i < select_options.length; i++) {
                        if (select_options[i].title == $(this).val()) {
                            for (var j = 0; j < select_options[i].details.length; j++) {
                                data_options.push({value: select_options[i].details[j].title});
                            }
                        }
                    }

                    var selector = $('input[name=\'option_value[]\'][data-idx=\'' + $(this).attr('data-idx') + '\']',
                        $(this).parent()).selectize({
                        create: true,
                        sortField: 'text',
                        persist: false,
                        addPrecedence: true,
                        selectOnTab: true,
                        valueField: 'value',
                        labelField: 'value'
                    }).data('selectize');
                    selector.clear();
                    selector.clearOptions();
                    selector.addOption(data_options);
                });

                $('select[name=\'option_name[]\'][data-idx=\'' + ($('select[name=\'option_name[]\']').length - 1) + '\']')
                    .change();
            } else {
                $('#modalmessage .sub-head').html('You can only add maximum two option.');
                $('#modalmessage').modal('show');
                return false;
            }
        });

        $('.value-container').on('click', '.remove-option', function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
        });

        $('#generate-sku-manual').click(function() {
            options = [];
            $('select[name=\'option_name[]\']').each(function() {
                var option = {title_id: $(this).val(), title_en: $(this).val(), title: $(this).val()};
                var details = [];
                for (var i = 0; i <
                $("input[name='option_value[]']", $(this).parent()).data('selectize').items.length; i++) {
                    var title = $('input[name=\'option_value[]\']', $(this).parent()).data('selectize').items[i];
                    var detail = {
                        title_id: title,
                        title_en: title,
                        title: title,
                        key: $(this).val().toLowerCase().replace(/\s/g, '') + title.toLowerCase().replace(/\s/g, ''),
                        image: null
                    };
                    details.push(detail);
                }
                option.details = details;
                options.push(option);
            });
            $('#modaloptionmanual').modal('hide');
            $('input[name=\'product_options\']').val(JSON.stringify(options));
            generate_option();
        });
    });
</script>

<style type="text/css">
    .form-control.selectize-control {
        width: 25%;
        margin-right: 15px;
    }
</style>
