$(function(){

    /**todo
     * 循环处理，出现闭包终值的问题
     */
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $("#brand").select2();
    $("#slist").select2();
    $("#tlist").select2();
    $("#flist").select2();
    var _list = ['brand', 'slist', 'tlist', 'flist'];
    $("#add_modal").delegate('#brand', 'change',  function(){
        if($('#brand').val() != 0){
            $.get("/admin/member/get-car", {pid: $('#brand').val()}, function(data){
                $("#slist").select2("destroy");
                $("#slist").html('');
                $("#tlist").select2("destroy");
                $("#tlist").html('');
                $("#tlist").select2();
                $("#flist").select2("destroy");
                $("#flist").html('');
                $("#flist").select2();
                $("#slist").select2({
                    data:data
                });
            }, "json");
        }else{
            $("#slist").select2("destroy");
            $("#slist").html('');
            $("#slist").select2();
        }
    });
    $("#add_modal").delegate('#slist', 'change', function(){
        if($('#slist').val() != 0){
            $.get("/admin/member/get-car", {pid: $('#slist').val()}, function(data){
                $("#tlist").select2("destroy");
                $("#tlist").html('');
                $("#tlist").select2({
                    data:data
                });
                $("#flist").select2("destroy");
                $("#flist").html('');
                $("#flist").select2();
            }, "json");
        }else{
            $("#tlist").select2("destroy");
            $("#tlist").html('');
            $("#tlist").select2();
        }

    });
    $("#add_modal").delegate('#tlist', 'change', function(){
        if($('#tlist').val() != 0){
            $.get("/admin/member/get-car", {pid: $('#tlist').val()}, function(data){
                $("#flist").select2("destroy");
                $("#flist").html('');
                $("#flist").select2({
                    data:data
                });
            }, "json");
        }else{
            $("#flist").select2("destroy");
            $("#flist").html('');
            $("#flist").select2();
        }

    });

    $("body").on("click","#add-member",function(){
        var modalPar = $(this).parentsUntil("div.form-modal").parent();
        var form = modalPar.find("form");
        var method = form.attr("method");
        var url = form.attr("action");
        var callback = $(this).attr("callback");
        $(this).attr('disabled', true);
        $.ajax({
            url : url,
            type : method,
            data : {Mobile:$("#Mobile").val(), Repo:$("#flist").val(), PlateNumber:$('#PlateNumber').val()},
            dataType : 'json',
            success : function(data){
                //操作后提示
                var modal=$("#tipModal");
                var tipbox=$("#tip-dialog-box");
                var tipBody=$("#tip-body");

                if(data.flag == 0){
                    tipbox.removeClass("modal-dialog-error");
                    tipbox.addClass("modal-dialog-suc");//成功提示
                    tipBody.text("操作成功！");
                    modal.modal("show");

                    modal.on('shown.bs.modal', function (e) {
                        setTimeout(function(){
                            modal.modal("hide");
                            //table.ajax.reload(null,false);
                            $(".bootstrap_table").bootstrapTable('refresh');//刷新table
                            if($("input[name='location-url']").val()){
                                location.href = $("input[name='location-url']").val();
                            }
                        },1000);
                    });

                    modalPar.modal('hide');

                    form.find("input [type='text']").val("");
                    form.find("textarea").val("");
//                     form.find("select").val("0");
                }else{
                    tipbox.removeClass("modal-dialog-suc");
                    tipbox.addClass("modal-dialog-error");//成功提示
                    tipBody.text(data.msg);
                    modal.modal("show");
                    modal.on('shown.bs.modal', function (e) {
                        setTimeout(function(){
                            modal.modal("hide");
                        },1000);
                    });
                }
                $("#add-member").removeAttr('disabled');
            },
            error : function(XMLHttpRequest, textStatus, errorThrown){
                $("#add-member").removeAttr('disabled');
                console.log(errorThrown);
            }
        });
    });

    $("body").on("click","#change-carmodel-btn",function(){
        var modalPar = $(this).parentsUntil("div.form-modal").parent();
        var form = modalPar.find("form");
        var method = form.attr("method");
        var url = form.attr("action");
        var callback = $(this).attr("callback");
        $(this).attr('disabled', true);
        $.ajax({
            url : url,
            type : method,
            data : {MemberID:$("#change-member").val(), RepoID:$("#change-carmodel").val(), CarModel:$("#flist").val()},
            dataType : 'json',
            success : function(data){
                //操作后提示
                var modal=$("#tipModal");
                var tipbox=$("#tip-dialog-box");
                var tipBody=$("#tip-body");

                if(data.flag == 0){
                    tipbox.removeClass("modal-dialog-error");
                    tipbox.addClass("modal-dialog-suc");//成功提示
                    tipBody.text("操作成功！");
                    modal.modal("show");

                    modal.on('shown.bs.modal', function (e) {
                        setTimeout(function(){
                            modal.modal("hide");
                            //table.ajax.reload(null,false);
                            $(".bootstrap_table").bootstrapTable('refresh');//刷新table
                            if($("input[name='location-url']").val()){
                                location.href = $("input[name='location-url']").val();
                            }
                        },1000);
                    });

                    modalPar.modal('hide');

                    form.find("input [type='text']").val("");
                    form.find("textarea").val("");
//                     form.find("select").val("0");
                }else{
                    tipbox.removeClass("modal-dialog-suc");
                    tipbox.addClass("modal-dialog-error");//成功提示
                    tipBody.text(data.msg);
                    modal.modal("show");
                    modal.on('shown.bs.modal', function (e) {
                        setTimeout(function(){
                            modal.modal("hide");
                        },1000);
                    });
                }
                $("#change-carmodel-btn").removeAttr('disabled');
            },
            error : function(XMLHttpRequest, textStatus, errorThrown){
                $("#change-carmodel-btn").removeAttr('disabled');
                console.log(errorThrown);
            }
        });
    });
})
