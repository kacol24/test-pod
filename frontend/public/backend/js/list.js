var url = '';
var ids = [];
var status = '';
var customerGroup = 0;
var tablePage = 1;
$(function() {
    $table = $('#datatable, .datatable').bootstrapTable({
        dataField: 'data',
        onLoadSuccess: function() {
            $('.loading').hide();
            $('.customer-group').unbind('change').change(function() {
                var group_id = $(this).val();
                var id = $(this).attr('id');
                url = $(this).attr('url');
                $('.loading').show();
                $.ajax({
                    url: url, // point to server-side PHP script
                    data: {
                        id: id,
                        group_id: group_id,
                        _token: $('input[name=\'_token\']').val()
                    },
                    type: 'post',
                    success: function(data) {
                        $table.bootstrapTable('refresh');
                        $('.loading').hide();
                    }
                });
            });

            if (typeof lightbox != 'undefined') {
                $('.lightbox').remove();
                lightbox.init();
            }

            $('.customer-status').unbind('change').change(function() {
                var status = $(this).val();
                var id = $(this).attr('id');
                url = $(this).attr('url');

                $('.loading').show();
                $.ajax({
                    url: url, // point to server-side PHP script
                    data: {
                        id: id,
                        status: status,
                        _token: $('input[name=\'_token\']').val()
                    },
                    type: 'post',
                    success: function(data) {
                        $table.bootstrapTable('refresh');
                        $('.loading').hide();
                    }
                });
            });

            $('.delete').unbind('click').click(function() {
                var id = $(this).attr('id');
                url = $(this).attr('url');
                ids = [];
                ids.push(id);
                $('#modalconfirm .modal-body .sub-head').html(
                    'Are you sure you want to delete this record? This action cannot be undo');
                $('#modalconfirm').modal('show');
            });

            $('.reject-payment').unbind('click').click(function() {
                var id = $(this).attr('id');
                url = $(this).attr('url');
                ids = [];
                ids.push(id);
                $('#modalconfirm .modal-body .sub-head').html(
                    'Are you sure you want to reject this payment? This action cannot be undo');
                $('#modalconfirm').modal('show');
            });

            $('.approve-payment').unbind('click').click(function() {
                var id = $(this).attr('id');
                url = $(this).attr('url');
                ids = [];
                ids.push(id);
                $('#modalconfirm .modal-body .sub-head').html(
                    'Are you sure you want to approve this payment? This action will automatically mark related order status to "paid" and this action cannot be undo');
                $('#modalconfirm').modal('show');
            });

            $('.bulkDelete').unbind('click').click(function() {
                var checked = $table.bootstrapTable('getSelections');
                url = $(this).attr('url');
                ids = [];
                $.each(checked, function(index, value) {
                    ids.push(value.id);
                });
                $('#modalconfirm .modal-body .sub-head').html(
                    'Are you sure you want to delete this data? This action cannot be undo');
                $('#modalconfirm').modal('show');
            });

            $('.bulkPublish').unbind('click').click(function() {
                var checked = $table.bootstrapTable('getSelections');
                url = $(this).attr('url');
                ids = [];
                $.each(checked, function(index, value) {
                    ids.push(value.id);
                });
                $('#modalconfirm .modal-body .sub-head')
                    .html('Are you sure you want to publish this product?');
                $('#modalconfirm').modal('show');
            });

            $('.bulkUnpublish').unbind('click').click(function() {
                var checked = $table.bootstrapTable('getSelections');
                url = $(this).attr('url');
                ids = [];
                $.each(checked, function(index, value) {
                    ids.push(value.id);
                });
                $('#modalconfirm .modal-body .sub-head')
                    .html('Are you sure you want to unpublish this product?');
                $('#modalconfirm').modal('show');
            });

            $('.save-currency').unbind('click').click(function() {
                $('.loading').show();
                $.ajax({
                    url: $(this).attr('url'),
                    data: {
                        rate: $('input[id="currency' + $(this).attr('data-id') +
                            '"]').val(),
                        _token: $('input[name=\'_token\']').val()
                    },
                    type: 'post',
                    success: function(data) {
                        $table.bootstrapTable('refresh');
                        $('.loading').hide();
                    }
                });
            });

            $('.toogle-active').unbind('change').change(function() {
                if ($(this).is(':checked')) {
                    status = 'enable';
                } else {
                    status = 'disable';
                }
                $('.loading').show();
                $.ajax({
                    url: $(this).attr('url'),
                    data: {
                        status: status,
                        _token: $('input[name=\'_token\']').val()
                    },
                    type: 'post',
                    success: function(data) {
                        $('.loading').hide();
                    }
                });
            });
        }
    });

    $table.on('reorder-row.bs.table',
        function(newTableData, droppedRow, oldPosition) {
            ids = [];
            $('input[type=\'checkbox\']').each(function() {
                ids.push($(this).val());
            });

            $.ajax({
                data: {
                    product_ids: ids,
                    page: tablePage,
                    _token: $('input[name=\'_token\']').val()
                },
                type: 'post',
                url: sorting_url
            });
        });

    $('input[name=\'search\']').keyup(function() {
        $table.bootstrapTable('refresh');
    });
});

// function confirm() {
//     $('.loading').show();
//     $.ajax({
//         url: url, // point to server-side PHP script
//         data: {
//             ids: ids,
//             _token: $('input[name=\'_token\']').val()
//         },
//         type: 'post',
//         success: function(data) {
//             $('#modalconfirm').modal('hide');
//             $table.bootstrapTable('refresh');
//             $('.loading').hide();
//         }
//     });
// }

function queryParams(params) {
    params.search = $('input[name=\'search\']').val();
    params.page = (params.offset + params.limit) / params.limit;
    return params;
}

function queryUsers(params) {
    params.search = $('input[name=\'search\']').val();
    params.page = (params.offset + params.limit) / params.limit;
    params.group = customerGroup;
    return params;
}

function queryProduct(params) {
    params.search = $('input[name=\'search\']').val();
    params.page = (params.offset + params.limit) / params.limit;
    tablePage = params.page;
    params.status = status;
    // params.category = category;
    return params;
}

function queryOrder(params) {
    params.search = $('input[name=\'search\']').val();
    params.start_date = $('input[name=\'start_date\']').val();
    params.end_date = $('input[name=\'end_date\']').val();
    params.page = (params.offset + params.limit) / params.limit;
    params.outlet_id = outlet_id;
    params.status = status;
    return params;
}

function filterPublish(val) {
    status = val;
    $('#filter-publish .nav-item a')
        .removeClass('text-color:blue font-weight-bold');
    if (val == 'all') {
        status = '';
        $('#filter-publish .nav-item .all')
            .addClass('text-color:blue font-weight-bold');
    } else if (val == 1) {
        $('#filter-publish .nav-item .active')
            .addClass('text-color:blue font-weight-bold');
    } else if (val == 0) {
        $('#filter-publish .nav-item .inactive')
            .addClass('text-color:blue font-weight-bold');
    }
    $table.bootstrapTable('refresh');
}

function filterCategory(val, text) {
    category = val;
    $('#text-category').html(text);
    $table.bootstrapTable('refresh');
}

function filterCustomerGroup(val, text) {
    customerGroup = val;
    $('input[name="group"]').val(val);
    $('#text-customer-group').html(text);
    $table.bootstrapTable('refresh');
}

function filterStatus(val) {
    status = val;
    $table.bootstrapTable('refresh');
}


