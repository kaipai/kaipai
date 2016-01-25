$(function(){
    /**todo
     * 循环处理，出现闭包终值的问题
     */
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $("#brand").select2();
    $("#slist").select2();
    $("#tlist").select2();
    $("#flist").select2();
    $("#syear").select2();
    $("#smonth").select2();
    var _list = ['brand', 'slist', 'tlist', 'flist', 'syear', 'smonth'];

    var data = {
        pid: 0
    };    

    var delId = 0;
    var idx = 0;
    var count = 0;

    $.get('/admin/member/get-car', data, function(data){        
        $("#brand").select2("destroy");
        $("#brand").html('');
        $("#brand").select2({
            data:data
        });        
    }, "json");

    $.get('/admin/json/date.json', function(data){        
        var data = data.year;
        //console.log('datapid', data);
        $("#syear").select2("destroy");
        $("#syear").html('');
        $("#syear").select2({
            data:data
        });
    }, "json");

    $.get('/admin/json/date.json', function(data){        
        var data = data.month;
        //console.log('datapid', data);
        $("#smonth").select2("destroy");
        $("#smonth").html('');
        $("#smonth").select2({
            data:data
        });
    }, "json");


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

    $("#add_modal").delegate('#flist', 'change', function(){
        if($('#flist').val() != 0){
            $('#J_carId').val(this.value);
            
        }
    });

    $("#add_modal").delegate('#syear', 'change', function(){        
        if($('#syear').val() != 0){
            $('#J_year').val(this.value);
            
        }else{
            $("#smonth").select2("destroy");
            $("#smonth").html('');
            $("#smonth").select2();
        }
    });
    
    $("#add_modal").delegate('#smonth', 'change', function(){
        if($('#smonth').val() != 0){
            $('#J_month').val(this.value);            
        }
    });

    //确认车型数据
    $('#J_verify').on('click', function(e){
        var elem = e.target;
        var uri = "/admin/count/get-car-detail";
        var carId = $('#J_carId').val();
        var data = {
            CarID: carId
        };
        //renderCarInfo();
        ajaxFn(uri, data, function(res){
           if(res.flag !== 0){
            alertError(res.msg);
            return false;
           }           

           $('#J_carInfo').html('');
            renderCarInfo(res.data);
        }, function(res){
            alertError(res.msg);
        });
    });

    //添加保障商品
    
    $('#J_addGoodSelect').on('click', function(e){
        var elem = e.target;
        var uri = '/admin/count/count-price-strong';
        var year = $('#J_year').val();
        var month = $('#J_month').val();                    
        var carid = $('#J_carId').val();
        var mile = $('#J_mile').val();
        var pid = $('#J_carPid').val();
        var data = {
            CarID: carid,
            PID: pid,            
            CarVehicleYear: year,
            CarVehicleMonth: month,
            CarVehicleRun: mile
        };
        ajaxFn(uri, data, function(res){
            var data = res.data;
            var flag = res.flag;
            if(flag !== 0){
                alertError(res.msg);
                return;
            }
            renderGood(data);
        }, function(res){
            alertError(res.msg);
        });

    });

    $('#j_goodSelect').change(function(e) {
        var elem = e.target;
        var val = elem.value;        
        $('#J_carPid').val(val);
    });

    $('#j_goodInfo').delegate('.J_del', 'click', function(e){
        var elem = e.target;
        delId = $(elem).attr('data-id');
        confirm();                   
    });

    $('#windowSize').delegate('#modal_confirm_btn', 'click', function(e){
        var c = $('#'+'J_carInfo'+delId+'').children('.price').text();                    
        var b = c.replace(',', '');
        var d = parseInt(b);
        count = count - d;                    
        $('#'+'J_carInfo'+delId+'').remove();
        $('#J_totalprice').html(count);                    
    });

    

    function confirm(){
        //$("body").on("click",".confirm-btn",function(e){
            var modal = $("#confirmModal");
            var btn = $('.J_del').closest('a');
            var url = btn.attr("url");
            var method = btn.attr("method");
            var callback = btn.attr("callback");
            var title = btn.attr("ctitle");
            var body = btn.attr("cbody");
            var params = btn.attr("params");
            var boxSize=btn.attr("boxsize");//设置弹窗大小
            var confirm_btn = $("#modal_confirm_btn");
            if(typeof(callback) != "undefined"){
                confirm_btn.attr('callback',callback);
            }
            confirm_btn.attr("url",url);
            confirm_btn.attr("method",method);
            confirm_btn.attr("params",params);
            confirm_btn.addClass("ajax-btn");
            $("#confirmModalLabel").text(title);
            $("#confirmModalBody").text(body);
            $("#windowSize").addClass(boxSize);
            modal.modal("show");
        //});

    }


    function renderGood(data){
        var j_goodInfo = $('#j_goodInfo');
        idx = idx + 1; 
        //$(data).each(function(index, item){
            var tpl = '<div class="carInfo-tr" id="J_carInfo'+idx+'">'+
                    '<div class="serial">'+idx+'</div>'+
                    '<div class="name">'+data.property+'</div>'+
                    '<div class="desc">'+data.desc+'</div>'+
                    '<div class="price J_price">'+data.price+'</div>'+                
                    '<div class="oprate"><a class="J_del confirm-btn" data-id="'+idx+'" href="#" ctitle="温馨提示"  cbody= "确认删除">删除</a></div>'+
                '</div>';
            $(j_goodInfo).append(tpl);  
        //})
        totalCount(data)            
    }
    
    function totalCount(data){
            var c = data.price;                                            
            var d = c.replace(',', '');
            var b = parseInt(d);                     
        count = count + b
        $('#J_totalprice').html(count);
    }
    function renderCarInfo(data){
        var J_carInfo = $('#J_carInfo');        
        $(data).each(function(index, item){                        
            var tpl =   //'<span class="car-intro" style="margin-top:18px;">'+
                        //'别克 GL8 2013 商务车 2.4 手自一体 舒适版'+
                    //'</span>'+
                    '<div class="car-param">'+
                    '<span class="refer"><label>参考车价：</label><input class="w100" type="text" disabled value="'+item.Price+'" />元</span>'+
                    '<span class="seat"><label>座位：</label><input class="w60" type="text" disabled value="'+item.SeatNum+'" /></span>'+
                    '<span class="swept"><label>排量：</label><input class="w60" type="text" disabled value="'+item.CarDispatchment+'" /></span>'+
                    '<span class="car-age"><label>养护机油升数：</label><input class="w60" type="text" disabled value="'+item.OilLiter+'" /></span>'+
                    '<a href="javascript:void(0)" class="J_setParam btn btn-primary active" rid="' + item.CarID +'" data-toggle="modal" data-target="#edit_car_info" title="修改车辆错误信息">设置车辆参数</a><span style="margin-left: 12px;">(若车辆数据有误，请点击这里修改)</span>'
            $(J_carInfo).append(tpl);
        });
         
         
        
        
    }



    function ajaxFn(uri, data, success, error){
        $.ajax({
            url: uri,
            type: 'POST',
            dataType: 'json',
            data: data,
        })
        .done(success)
        .fail(error)
        .always(function() {
            //console.log("complete");
        });                    
    }

    $('#edit_car_info').on("show.bs.modal",function(e){
                var btn = $(e.relatedTarget);
                var id = $('#J_carId').val();
                $.post("/admin/count/get-car-detail", {CarID:id},function(data){
                    
                    if(data.flag !== 0){
                        alertError(data.msg);
                        return;
                    }else{
                        $('#Price').val(data.data.Price);
                        $('#SeatNum').val(data.data.SeatNum);
                        $('#CarDispatchment').val(data.data.CarDispatchment);
                        $('#OilLiter').val(data.data.OilLiter);
                        $('#CarID').val(data.data.CarID);
                        $('#CarBuyTime').val(data.data.CarBuyTime);
                    }
                },"json");
            });


            $("body").on("click","#changeCarInfo",function(){
                $(this).attr('disabled', true);
                var modalPar = $(this).parentsUntil("div.form-modal").parent();
                var form = modalPar.find("form");
                var method = form.attr("method");
                var url = form.attr("action");
                var callback = $(this).attr("callback");
                $.ajax({
                    url : url,
                    type : method,
                    data : form.serialize(),
                    dataType : 'json',
                    success : function(data){
                        //操作后提示
                        var modal=$("#tipModal");
                        var tipbox=$("#tip-dialog-box");
                        var tipBody=$("#tip-body");
                        $("#changeCarInfo").removeAttr('disabled');
                        if(data.flag == 0){
                            tipbox.removeClass("modal-dialog-error");
                            tipbox.addClass("modal-dialog-suc");//成功提示
                            tipBody.text("操作成功！");
                            modal.modal("show");
                            $("#J_verify").trigger('click');
                            modal.one('shown.bs.modal', function (e) {
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
                        }else{
                            tipbox.removeClass("modal-dialog-suc");
                            tipbox.addClass("modal-dialog-error");//成功提示
                            tipBody.text(data.msg);
                            modal.modal("show");
                            modal.one('shown.bs.modal', function (e) {
                                setTimeout(function(){
                                    modal.modal("hide");
                                },1000);
                            });
                        }
                    },                    
                    error : function(XMLHttpRequest, textStatus, errorThrown){
                        $("#changeCarInfo").removeAttr('disabled');

                        //console.log(errorThrown);
                    }
                });
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
