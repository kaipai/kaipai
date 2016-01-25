$(function(){
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $("#MemberID").select2({
        ajax: {
            dataType:'json',
            delay: 500,
            url: '/admin/member/search-member',
            data: function (params) {
                var queryParameters = {
                    Mobile: params.term
                };

                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    });
    $("#memberids").select2({
        ajax: {
            dataType:'json',
            delay: 500,
            url: '/admin/member/search-member',
            data: function (params) {
                var queryParameters = {
                    Mobile: params.term
                };

                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
        }
    });
});
