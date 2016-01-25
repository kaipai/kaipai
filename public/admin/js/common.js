    var appCom={
	_addressdata:function(){
		
	},
	//地址选择事件
	_addressCurIndex:0,//当前操作index
	_addressLen:3,//切换长度
	_addressDefaultVal:false,//默认时颜色
	_addressTab:function(index,id){
		
		$(id + " " + '.city-select-tab a').removeClass('current').eq(index).addClass('current');
		$(id + " " + '.city-select-content .city-select').hide().eq(index).show();
	},
	_addressBox:function(){
			
	},
	_address:function(id){
		var _AddressData="";
		var _curHtml1="";
		var _curHtml2="";
		var _curHtml3="";
		var _curHtml4="";
		var _cityHtml="";
		var _provinceID="";
		//默认值
		var _provinceCheckID=$(id + " " +"input.addressinput").eq(0).attr("addressid");
		var _cityCheckID=$(id + " " + "input.addressinput").eq(1).attr("addressid");
		var _districtCheckID=$(id + " " + "input.addressinput").eq(2).attr("addressid");
		//索引
		var _provinceChcekIndex="";
		var _cityChcekIndex="";
		
	$.getJSON("/admin/json/address.json",function(data){
	 var provinceId = 2;
	_AddressData=data;
	 var _cityProvince=$(id + " " + '.city-province');
		$.each(data,function(index,info){
			var _Phonetic=info["Phonetic"];
			if("g">=info["Phonetic"]){
				if(info['Id']==_provinceCheckID){
					_provinceChcekIndex=index;
					_curHtml1=_curHtml1+"<a class='current' title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";
					$(id + " " + "input.addressinput").eq(0).val(info['Name']);
				}else{
					_curHtml1=_curHtml1+"<a title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";	
				}
				
			}else if("k">=info["Phonetic"]){
				if(info['Id']==_provinceCheckID){
					_provinceChcekIndex=index;
					_curHtml2=_curHtml2+"<a class='current' title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";	
					$(id + " " + "input.addressinput").eq(0).val(info['Name']);
				}else{
					_curHtml2=_curHtml2+"<a title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";	
				}
			}else if("s">=info["Phonetic"]){
				if(info['Id']==_provinceCheckID){
					_provinceChcekIndex=index;
					_curHtml3=_curHtml3+"<a class='current' title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";	
					$(id + " " + "input.addressinput").eq(0).val(info['Name']);
				}else{
					_curHtml3=_curHtml3+"<a title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";	
				}
			}else{
				if(info['Id']==_provinceCheckID){
					_provinceChcekIndex=index;
					_curHtml4=_curHtml4+"<a class='current' title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";
					$(id + " " + "input.addressinput").eq(0).val(info['Name']);
				}else{
					_curHtml4=_curHtml4+"<a title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";	
				}
			}
		});
		//省级初始化
		_cityProvince.find('dl').eq(0).find('dd').html(_curHtml1);
		_cityProvince.find('dl').eq(1).find('dd').html(_curHtml2);
		_cityProvince.find('dl').eq(2).find('dd').html(_curHtml3);
		_cityProvince.find('dl').eq(3).find('dd').html(_curHtml4);
		//市级初始化
		if(_provinceCheckID){
			var _defaultVal="";
			var _cityCheckChild=data[_provinceChcekIndex].child;
					var _citySelectCityHtml="";
					$.each(_cityCheckChild,function(index2,info2){
							if(info2['Id']==_cityCheckID){
								//定位到默认
								_cityChcekIndex=index2;
								_defaultVal=info2['Name'];
								_citySelectCityHtml=_citySelectCityHtml+"<a class='current' title='"+info2['Name']+"' attr-id='"+index2+"'data-id='"+info2['Id']+"' href='javascript:;'>"+info2['Name']+"</a>";
							}else{
								_citySelectCityHtml=_citySelectCityHtml+"<a title='"+info2['Name']+"' attr-id='"+index2+"'data-id='"+info2['Id']+"' href='javascript:;'>"+info2['Name']+"</a>";		
							}
			});
					
			$(id + " " + '.city-select-city dd').html(_citySelectCityHtml);
			$(id + " " + "input.addressinput").eq(1).val(_defaultVal);
			
			$('#text_address').removeClass('default').html($(id + " " + "input.addressinput").eq(0).val());
			
		};
		//县级初始化
		if(_cityCheckID){
			var _defaultVal="";
			var _citySelectDistrictHtml="";
			var _cityCheckChild=data[_provinceChcekIndex].child[_cityChcekIndex].child;
			$.each(_cityCheckChild,function(index,info){
				if(info['Id']==_districtCheckID){
					_defaultVal=info['Name'];
					_citySelectDistrictHtml=_citySelectDistrictHtml+"<a class='current' title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";	
				}else{
					_citySelectDistrictHtml=_citySelectDistrictHtml+"<a title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";		
				}
			})	
			$(id + " " + '.city-select-district dd').html(_citySelectDistrictHtml);
			var _defaultHtml=$(id + " " + "input.addressinput").eq(0).val()+"<em>/</em>"+$(id + " " + "input.addressinput").eq(1).val();
			$(id + " " + '#text_address').removeClass('default').html(_defaultHtml);
			$(id + " " + "input.addressinput").eq(2).val(_defaultVal);
		};
		if(_districtCheckID){
			var _defaultHtml=$(id + " " + "input.addressinput").eq(0).val()+"<em>/</em>"+$(id + " " + "input.addressinput").eq(1).val()+"<em>/</em>"+$(id + " " + "input.addressinput").eq(2).val();
			$(id + " " + '#text_address').removeClass('default').html(_defaultHtml);	
		}
                
	}) 
	//显示框
		$(id + " " + '.select-address .select-tit').bind('click',function(e){
			e.stopPropagation();
			$(id + " " + '.city-select-warp').removeClass('hide');
		});
		//直接点击标题切换
		$(id + " " + '.city-select-tab a').bind('click',function(e){
			e.stopPropagation();
			appCom._addressTab($(this).index(),id)
		});
		//点击详情操作
		$(id + " " + '.city-select').delegate('a','click',function(e){
			e.stopPropagation();
			
			var _city_selcet=$(this).parents('.city-select');
			appCom._addressCurIndex=_city_selcet.index();
			
			
			var _curTitle=$(this).attr('title');
			var _curID=$(this).attr('attr-id');
			var _cityId=$(this).attr('data-id');
			
			appCom._addressVal(appCom._addressCurIndex,_curTitle,_cityId,id);
			//省级
			if(0==appCom._addressCurIndex){
				$(id + " " + '.city-province').find('a').removeClass("current");
				$(this).addClass("current");
				_provinceID=_curID;
				
				
				//数据加载市级
				var _citydata=_AddressData[_curID].child;
				_cityHtml="";
				$.each(_citydata,function(index,info){
					_cityHtml=_cityHtml+"<a title='"+info['Name']+"' attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";
				});
				$(id + " " + '.city-select-city').find('dd').html(_cityHtml);
				
			}else{
				$(this).addClass("current").siblings().removeClass("current");
				if(1==appCom._addressCurIndex){
					//数据加载县级
					_cityHtml="";
					var _districtData=_AddressData[_provinceID].child[_curID].child;
					
					$.each(_districtData,function(index,info){
						_cityHtml=_cityHtml+"<a title='"+info['Name']+"'attr-id='"+index+"'data-id='"+info['Id']+"' href='javascript:;'>"+info['Name']+"</a>";
					});
					
					$(id + " " + '.city-select-district').find('dd').html(_cityHtml);	
				}else{
						
				}
			}
		
			
			//头部切换
			if(appCom._addressCurIndex<appCom._addressLen-1){
				appCom._addressTab(appCom._addressCurIndex+1,id);
			}else{//切换到最后一个
				$(id + " " + '.city-select-warp').addClass("hide");
			};
			
			if(!appCom._addressDefaultVal){
				$(id + " " + '.select-tit span').removeClass('default');
				appCom._addressDefaultVal=true;	
			}
				
		});
		//box hide
		$(document).bind('click',function(){
			$(id + " " + '.city-select-warp').addClass('hide');
		});
	},
	_addressVal:function(index,title,addressid,id){
		$(id + " " + "input.addressinput").eq(index).attr('addressid',addressid).val(title);
		$(id + " " + "input.addressinput").eq(index).nextAll("input").removeAttr("modelid").removeAttr("value");
		var _showVal="";
		for(var i=0;i<=appCom._addressCurIndex;i++){
			if(i==appCom._addressCurIndex){
				_showVal=_showVal+$(id + " " + "input.addressinput").eq(i).val();
			}else{
				_showVal=_showVal+$(id + " " + "input.addressinput").eq(i).val()+"<em>/</em>";
			}
		}
		$(id + " " + '.select-address .select-tit span').html(_showVal);
	}
};