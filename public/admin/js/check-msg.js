$(function () {
    $.comet.init({
        type: 'POST',
        dataType: 'json',
        url: '/admin/admin/count-handle',
        success: function (data, textStatus, jqXHR) {
            if (data.flag == 0) {
                var allCount = data.data.ordercount + data.data.casecount;
                if(allCount != 0)
                    $('#msgHandle').html(allCount);
                if (data.data.casecount != 0) {
                    if ($("#caseNum").length > 0) {
                        $('#caseNum').html(data.data.casecount)
                    } else {
                        $('#user-menu-info').prepend('<li><a href="/admin/case/index"><i class="fa fa-bullhorn"></i>  受理：<span  id="caseNum">' + data.data.casecount + '</span></a></li>');
                    }

                }
                if (data.data.ordercount != 0) {
                    if ($("#orderNum").length > 0) {
                        $('#orderNum').html(data.data.ordercount)
                    } else {
                        $('#user-menu-info').prepend('<li><a href="/admin/order/index"><i class="fa fa-cart-plus"></i>  订单：<span  id="orderNum">' + data.data.ordercount + '</span></a></li>');
                    }
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }

    }, 60000);
});