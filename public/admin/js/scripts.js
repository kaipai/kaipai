
(function() {
    "use strict";

    // custom scrollbar

    $("html").niceScroll({styler:"fb",cursorcolor:"#65cea7", cursorwidth: '6', cursorborderradius: '0px', background: '#424f63', spacebarenabled:false, cursorborder: '0',  zindex: '1000'});

    $(".left-side").niceScroll({styler:"fb",cursorcolor:"#65cea7", cursorwidth: '3', cursorborderradius: '0px', background: '#424f63', spacebarenabled:false, cursorborder: '0'});


    $(".left-side").getNiceScroll();
    if ($('body').hasClass('left-side-collapsed')) {
        $(".left-side").getNiceScroll().hide();
    }



    // Toggle Left Menu
   jQuery('.menu-list > a').click(function() {
      var parent = jQuery(this).parent();
      var sub = parent.find('> ul');
      
      if(!jQuery('body').hasClass('left-side-collapsed')) {
         if(sub.is(':visible')) {
            sub.slideUp(200, function(){
               parent.removeClass('nav-active');
               jQuery('.main-content').css({height: ''});
               mainContentHeightAdjust();
            });
         } else {
            visibleSubMenuClose();
            parent.addClass('nav-active');
            sub.slideDown(200, function(){
                mainContentHeightAdjust();
            });
         }
      }
      return false;
   });

   function visibleSubMenuClose() {
      jQuery('.menu-list').each(function() {
         var t = jQuery(this);
         if(t.hasClass('nav-active')) {
            t.find('> ul').slideUp(200, function(){
               t.removeClass('nav-active');
            });
         }
      });
   }

   function mainContentHeightAdjust() {
      // Adjust main content height
      var docHeight = jQuery(document).height();
      if(docHeight > jQuery('.main-content').height())
         jQuery('.main-content').height(docHeight);
   }

   //  class add mouse hover
   jQuery('.custom-nav > li').hover(function(){
      jQuery(this).addClass('nav-hover');
   }, function(){
      jQuery(this).removeClass('nav-hover');
   });


   // Menu Toggle
   jQuery('.toggle-btn').click(function(){
       $(".left-side").getNiceScroll().hide();
       
       if ($('body').hasClass('left-side-collapsed')) {
           $(".left-side").getNiceScroll().hide();
       }
      var body = jQuery('body');
      var bodyposition = body.css('position');

      if(bodyposition != 'relative') {

         if(!body.hasClass('left-side-collapsed')) {
            body.addClass('left-side-collapsed');
            jQuery('.custom-nav ul').attr('style','');

            jQuery(this).addClass('menu-collapsed');

         } else {
            body.removeClass('left-side-collapsed chat-view');
            jQuery('.custom-nav li.active ul').css({display: 'block'});

            jQuery(this).removeClass('menu-collapsed');

         }
      } else {

         if(body.hasClass('left-side-show'))
            body.removeClass('left-side-show');
         else
            body.addClass('left-side-show');

         mainContentHeightAdjust();
      }

   });
   

   searchform_reposition();

   jQuery(window).resize(function(){

      if(jQuery('body').css('position') == 'relative') {

         jQuery('body').removeClass('left-side-collapsed');

      } else {

         jQuery('body').css({left: '', marginRight: ''});
      }

      searchform_reposition();

   });

   function searchform_reposition() {
      if(jQuery('.searchform').css('position') == 'relative') {
         jQuery('.searchform').insertBefore('.left-side-inner .logged-user');
      } else {
         jQuery('.searchform').insertBefore('.menu-right');
      }
   }

    // panel collapsible
    $('.panel .tools .fa').click(function () {
        var el = $(this).parents(".panel").children(".panel-body");
        if ($(this).hasClass("fa-chevron-down")) {
            $(this).removeClass("fa-chevron-down").addClass("fa-chevron-up");
            el.slideUp(200);
        } else {
            $(this).removeClass("fa-chevron-up").addClass("fa-chevron-down");
            el.slideDown(200); }
    });

    $('.todo-check label').click(function () {
        $(this).parents('li').children('.todo-title').toggleClass('line-through');
    });

    $(document).on('click', '.todo-remove', function () {
        $(this).closest("li").remove();
        return false;
    });

    //$("#sortable-todo").sortable();


    // panel close
    $('.panel .tools .fa-times').click(function () {
        $(this).parents(".panel").parent().remove();
    });



    // tool tips

    $('.tooltips').tooltip();

    // popovers

    $('.popovers').popover();



    $("body").on("click","#search_button",function(){
        $('#table-pagination').bootstrapTable('refresh', {query : {offset : 0}});
    });

    $("body").on("click","#cleanSearch",function(){
        window.location.reload();
    });

//通用事件操作
    /* ajax-btn类为ajax操作按钮，触发事件 */
    $(document).on("click",".ajax-btn",function(e){
    	$("#confirmModal").modal("hide");
        var btn = $(e.currentTarget);
        var url = btn.attr("url");
        var method = btn.attr("method");
        var params = btn.attr("params");
        var callback = btn.attr("callback");
        $.ajax({
            url : url,
            type : method,
            data : params,
            dataType : 'json',
            success:function(data){
            	//操作后提示
            	var modal=$("#tipModal");
            	var tipbox=$("#tip-dialog-box");
            	var tipBody=$("#tip-body");
            	if(data.flag == 0){
                	tipbox.addClass("modal-dialog-suc");//成功提示
                	tipBody.text("操作成功！");
                	modal.modal("show");
                	modal.one('shown.bs.modal', function (e) {
              		  setTimeout(function(){
                            modal.modal("hide");
                            $(".bootstrap_table").bootstrapTable('refresh');//刷新table
                        },1000);
                	})
            	}else{
                	tipbox.addClass("modal-dialog-error");//错误提示
					if(data.msg){
						tipBody.text(data.msg);
					}else{
						tipBody.text("操作失败，请联系管理员！");
					}
                	modal.modal("show");
                	modal.one('shown.bs.modal', function (e) {
              		  setTimeout(function(){
              			modal.modal("hide");			 
              		  },2000);
                	})
            	}
            },
            error : function(XMLHttpRequest, textStatus, errorThrown){
                console.log(errorThrown);
            }
        });
    });
  
 
    /* ajax-confirm-btn类为需要一个确认框的ajax操作按钮类，展示确认框 */
    $("body").on("click",".ajax-confirm-btn",function(e){
        var modal = $("#confirmModal");
        var btn = $(e.currentTarget);
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
    });

    /* ajax-confirm-btn类为需要一个确认框的ajax操作按钮类，展示确认框 */
    $("body").on("click",".confirm-btn",function(e){
        var modal = $("#confirmModal");
        var btn = $(e.currentTarget).closest('a');
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
		
		if(confirm_btn){
			 modal.modal("show");
		}
       
		
    });

    /*
     * form-modal类中的updateModel类按钮事件，用来ajax更新form-modal表单使用，成功后隐藏modal，并ajax重新加载数据。
     **/
     //$(".form-modal").on("click",".editModal",function(){
     $("body").on("click",".editModal",function(){
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
                 $(".editModal").removeAttr('disabled');
                 if(data.flag == 0){
                     tipbox.removeClass("modal-dialog-error");
                	tipbox.addClass("modal-dialog-suc");//成功提示
                 	tipBody.text("操作成功！");
                 	modal.modal("show");
                 	
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
                     
                     //form.find("input [type='text']").val("");
                     //form.find("textarea").val("");
//                     form.find("select").val("0");
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
                 $(".editModal").removeAttr('disabled');

                 console.log(errorThrown);
             }
         });
     });




})(jQuery);

    function toDate(data){
        var cur = new Date(data*1000);
        var month = cur.getMonth()+1;
        if(month < 10){
            month = "0" + month;
        }
        var day = cur.getDate();
        if(day < 10){
            day = "0" + day;
        }
        var hour = cur.getHours();
        if(hour < 10){
            hour = "0" + hour;
        }
        var min = cur.getMinutes();
        if(min < 10){
            min = "0" + min;
        }
        var sec = cur.getSeconds();
        if(sec < 10){
            sec = "0" + sec;
        }
        return cur.getFullYear() + "-" + month + "-"+ day + " " + hour + ":" + min + ":" + sec;
    }
    
    /*
     * @description 添加在指定标签前添加指定内容
     * @param {type} obj-标签对象 content添加内容
     * @returns {undefined}
     */    
    function addSpan(obj,content){
        $(obj).before(content);
    }
    
     /*
     * @description 删除指定标签
     * @param {type} obj-标签对象
     * @returns {undefined}
     */     
    function delSpan(obj){
        $(obj).remove();
    }

    function unescapeHTML(a){
        a = "" + a;
        return a.replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&nbsp;/g, " ");
    };

    function escapeHTML(a){
        a = "" + a;
        return a.replace(/\r\s/g,"&nbsp;").replace(/  /g,"&nbsp;")
    };
//成功提示
function alertSuccess(str,refresh){
	//操作后提示
	var modal=$("#tipModal");
	var tipbox=$("#tip-dialog-box");
	var tipBody=$("#tip-body");
	tipbox.removeClass("modal-dialog-error");
	tipbox.addClass("modal-dialog-suc");//成功提示
	tipBody.text(str);
	modal.modal("show");
	modal.one('shown.bs.modal', function (e) {
	  setTimeout(function(){
		modal.modal("hide");
                if(!refresh){
                    $(".bootstrap_table").bootstrapTable('refresh');//刷新table
                }
	  },1000);
	});	
}
//警告提示
function alertError(str){
	//操作后提示
	var modal=$("#tipModal");
	var tipbox=$("#tip-dialog-box");
	var tipBody=$("#tip-body");
	tipbox.removeClass("modal-dialog-suc");
	tipbox.addClass("modal-dialog-error");//成功提示
	tipBody.text(str);
	modal.modal("show");
	modal.one('shown.bs.modal', function (e) {
	 setTimeout(function(){
		 modal.modal("hide");
	 },1000);
	});
}