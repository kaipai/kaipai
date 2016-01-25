var addPro={
	i:0,
	j:0,
	J_addProAttrBox:$('#addProAttrBox'),
	J_addProAttr:$('#addProAttr'),
	delProAttr:function(){
		addPro.J_addProAttrBox.delegate('.delProItem', 'click', function(e) {
			$(this).parent().remove();

		});
	},
	delProProject:function(){
		addPro.J_addProAttrBox.delegate('.delAttrTit', 'click', function(e) {
			$(this).parent().parent().remove();
			
		});
	},
	addAttributes:function(){
		addPro.J_addProAttr.bind('click',function(){
			var J_initNum=$('#initNum').val();
			if(J_initNum!=''){
				addPro.i=parseInt(J_initNum);
				
			};
			var J_attrName=$('#attrName');
			var J_attrPrice=$('#attrPrice');
                        if($('#sku_div').attr("sku") == "yes"){
                            var J_attrSku = $('#ThirdSku');
                        }
                        
			if (J_attrName.val() == '') {
                alertError('请填写正确的属性!');
                return false;
            };
            if (isNaN(J_attrPrice.val()) || J_attrPrice.val() == '' || J_attrPrice.val() == 0) {
                alertError('请填写正确的价格!');
                return false;
            };
            addPro.i=addPro.i+1;
			$('#initNum').val(addPro.i+1);
     
			var J_array = [];
			J_array.push("<dl class='col-sm-10 ml20 pro-attrbox'><dt><div class='pro-li-1 pull-left'>属性：<input type='text' class='w120' name='property["+addPro.i+"][PropertyName]' value='"+J_attrName.val()+"'> ");
                        if($('#sku_div').attr("sku") == "yes")
                            J_array.push("</div><div class='pro-li-1 pull-left'>SKU：<input type='text' class='w120' name='property["+addPro.i+"][ThirdSku]' value='"+J_attrSku.val()+"'></div>");
			J_array.push("</div><div class='pro-li-1 pull-left'>价格：<input type='text' class='w120' name='property["+addPro.i+"][Price]' value='"+J_attrPrice.val()+"'></div>");
			J_array.push("<div class='pro-li-1 pull-left'>排序：<input type='text' placeholder='倒序,不可大于255' name='property["+addPro.i+"][Sort]'  value='' class='w120'> </div>");
			J_array.push("<div class='pro-li-3 pull-left addproject' data-attr='"+addPro.i+"' >增加方案</div><div class='delServiceItem delAttrTit '>-</div></dt>");
			J_array.push("<dd>");
			J_array.push("<div class='pro-selected-tit'><div class='pro-li-4 pull-left'>已选方案:</div><div class='pro-li-1 pull-left'>方案分类</div>");


            J_array.push("<div class='pro-li-1 pull-left'>方案名称</div><div class='pro-li-1 pull-left'>方案数量</div><div class='pro-li-5 pull-left'>操作</div></div>");

			J_array.push("</dd>")

			J_array.push("</dl>");

			if(addPro.J_addProAttrBox.find('dl').length>0){
				addPro.J_addProAttrBox.find('dl').eq(0).before(J_array.join(''));

			}else{

				addPro.J_addProAttrBox.append(J_array.join(''));
			};
			
		});

		var _pophtml="<div class='modal fade' id='orderlistPOP' tabindex='-1' role='dialog'    aria-labelledby='myModalLabel' aria-hidden='true'>"
			_pophtml=_pophtml+"<div class='modal-dialog order-pop'><div class='modal-content'></div></div></div>";
		//方案新增
		addPro.J_addProAttrBox.delegate('.addproject','click',function(){
			//当前对应属性的标识
			var _curAttr=$(this).data('attr');

			$(document.body).append(_pophtml);
			var _orderPOP=$('#orderlistPOP');
			//显示弹窗
			_orderPOP.modal({
				show:true,	
				backdrop:'true',
				remote:"/admin/product/addpro",
			});
			//当前dl下的pro-selected-tit 及方案title
			var J_curDl=$(this).parent().parent().find('.pro-selected-tit');
			var J_addProject=$('#add-project');
			var J_pjClass=$('#pjClass');

			//方案选择完并关闭弹窗
			  _orderPOP.delegate('#add-project', 'click',function(){
			  	var J_pjName=_orderPOP.find('#pjName');
			  	var J_pjClass=_orderPOP.find('#pjClass');
			  	var J_pjNum=_orderPOP.find('#pjNum');
			  	var J_tipErr=_orderPOP.find('#tipErr');
				
				


			  	if(isNaN(J_pjNum.val())|| J_pjNum.val() == '' || J_pjNum.val() == 0){
			  		J_tipErr.html("请补全信息！");
			  		return false;

			  	}else{
			  		//当前属性下新增对应的方案
				  	var J_arrayProject=[];
				  	addPro.j=addPro.j+1;
				  	J_arrayProject.push("<div class='pro-selected-li'>");
				  	J_arrayProject.push("<input type='hidden' name='attrpjClass"+_curAttr+"[]"+"' value='"+J_pjClass.val()+"' /> <input type='hidden' name='attrpjName"+_curAttr+"[]"+"' value='"+J_pjName.val()+"' /> <input type='hidden' name='attrpjNum"+_curAttr+"[]"+"' value='"+J_pjNum.val()+"' />")
				  	J_arrayProject.push("<div class='pro-li-4 pull-left'>&nbsp;</div><div class='pro-li-1 pull-left'>"+J_pjClass.find("option:selected").text()+"</div>");
				  	J_arrayProject.push("<div class='pro-li-1 pull-left'>"+J_pjName.find("option:selected").text()+"</div><div class='pro-li-1 pull-left'>"+J_pjNum.val()+"</div><div class='delServiceItem delProItem'>-</div></div>");
					J_curDl.after(J_arrayProject.join(''));
					J_curDl.show();
					//关闭弹窗
					_orderPOP.modal('hide');
			  	};
			});
			//

			//关闭时触发并清空
			_orderPOP.on('hidden.bs.modal', function (e) {
				_orderPOP.remove();
			 })
			



		});


	},
	init:function(){
		addPro.addAttributes();
		addPro.delProAttr();
		addPro.delProProject();
	}
};

$(function () {
     addPro.init();
});