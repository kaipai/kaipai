/**
author : wanrengang &&  365700955@qq.com
desc : jquery for mobile
**/


if (typeof jQuery === "undefined") { throw new Error("jqmobi requires jQuery") }
(function($){
	$.Popup = function(opts){
		var defaultOpts = {
			width : 260,
			titleShow : true,
			title : '提示框',
			content : '',
			mask : true,
			zIndex : 1000,
			closeShow : true,
			closeCallback : function(){
				$.PopupClose();
			},
			btn : [
				['取消','default',function(){ $.PopupClose(); }],
				['确定','orange',function(){ $.PopupClose(); }]
			]
		}
		opts = $.extend({},defaultOpts,opts);
		var str = '';
		if( opts.mask === true ){
			str += '<div id="Mask" style="z-index:'+opts.zIndex+'"></div>';
		}
		str += 	'<div id="Popup" style="z-index:'+(opts.zIndex+1)+';">';
		if( opts.closeShow ){
			str +=	'<span class="pop-close">&times;</span>';
		}
		if( opts.titleShow ){
			str +=	'<div class="pop-tit">'+
						'<h6>'+opts.title+'</h6>'+
					'</div>';
		}
		str +=	'<div class="pop-con">'+opts.content+'</div>';
		if( opts.btn && opts.btn.length ){
			str +=	'<div class="pop-btn">';
			for(var i = 0; i < opts.btn.length; i++){
				if( opts.btn[i][0] && opts.btn[i][1] ){
					str += '<span class="btn btn-'+opts.btn[i][1]+' btn-'+i+'">'+opts.btn[i][0]+'</span>';
				}else{
					throw new Error("btn value not full");
				}
			}	
			str += '</div>';
		}
		str +=	'</div>';
		

		
		$(str).appendTo('body');
		if( !opts.titleShow && opts.closeShow ){
			$('#Popup .pop-close').css({'color':'#428bca'});
		}
		var pop_h = $('#Popup').height();
		$('#Popup').css({
			'width' : opts.width+'px',
			'margin-left' : -(opts.width/2)+'px',
			'margin-top' : -(pop_h/2)+'px'
		});
		$('#Popup .pop-close').on('click',function(){
			if( typeof opts.closeCallback === 'function' ){
				opts.closeCallback();
			}
		});
		$('#Popup .pop-btn .btn').on('click',function(){
			var ind = $(this).index();
			if( typeof opts.btn[ind][2] === 'function' ){
				opts.btn[ind][2]();
			}
		});
	}
	$.PopupClose = function(){
		$('#Mask,#Popup').remove();
	}
	$.PopupHide = function(){
		$('#Mask,#Popup').hide();
	}
	$.PopupShow = function(){
		$('#Mask,#Popup').show();
	}
	$.PopupTip = function(msg,closeShow,pos){
		$.Popup({
			width : 200,
			content : 	'<style>'+
							'#Popup{background:rgba(0,0,0,0.7); box-shadow:none;}'+
							'#Mask{background:rgba(255,255,255,0.1)}'+
						'</style>'+
						'<div style=" color:#fff; text-align:'+( pos ? pos : 'left')+';">'+
							msg+
						'</div>',
			titleShow : false,
			closeShow : closeShow,
			btn : []
		})
		$('#Popup .pop-close').css({
			'color':'#fff',
			'font-size':'18px',
			'line-height':'38px'
		});
		var t = setTimeout(function(){
			$('#Popup').fadeOut(function(){
				$.PopupClose();
				clearTimeout(t);
			})
		},3000);
	}
	$.ErrMsg = function(msg){
		$.Popup({
			content : '<p style="padding:20px 0;text-align:center; font-size: 16px; color:#999;">'+msg+'</p>',
			titleShow : false,
			btn : []
		})
	}

	$.mobileFormat = function(phoneNum){
		phoneNum = phoneNum.substring(0, 3) + "****" + phoneNum.substring(7);
		return phoneNum;
	}
	$.getSysYear = function(){
		var date = new Date(),
			year = parseInt(date.getFullYear());
		return year;
	}
	$.getSysMonth = function(){
		var date = new Date(),
			month = parseInt(date.getMonth() + 1);
		return month;
	}
	$.getSysDay = function(){
		var date = new Date(),
			day = parseInt(date.getDate());
		return day;
	}
	$.getSysHour = function(){
		var date = new Date(),
			hour = parseInt(date.getHours());
		return hour;
	}
	$.getSysMinutes = function(){
		var date = new Date(),
			minutes = parseInt(date.getMinutes());
		return minutes;
	}
})(jQuery);

;(function($){
	$.extend({
		Mask : function(rgba,type){
			if( !rgba ){
				rgba = '255,255,255,0.1';
			}
			if( type == 'loading' ){
				var str = '<div id="Mask" class="MaskLoading" style="background-color:rgba('+ rgba +')"></div>';
			}else{
				var str = '<div id="Mask" style="background-color:rgba('+ rgba +')"></div>';
			}
			$('body').append(str);
		},
		MaskRemove : function(type){
			if( type == 'loading' ){
				$('#Mask.MaskLoading').remove();
			}else{
				$('#Mask').remove();
			}
		},
		Loading : function(){
			var str = 	'<div id="Loading">'+
							'<div class="bounce1"></div>'+
							'<div class="bounce2"></div>'+
							'<div class="bounce3"></div>'+
						'</div>';
			$.Mask('255,255,255,0.1','loading');
			$('body').append(str);
		},
		RemoveLoading : function(){
			$('#Loading').remove();
			$.MaskRemove('loading');
		},
		//屏幕大小调整后调用
		screenResize : function(fn){
			fn();
			$(window).resize(function(){ fn(); });
		},
		getUserAgent : function(){
			var browser = window.navigator.userAgent.toLowerCase();
			return browser;
		},
		getUrlPara : function(key){
			var para = window.location.search;
			if( para ){
				var arr = para.split('?')[1].split('&'),
					len = arr.length,
					obj = {};
				for(var i = 0; i < len; i++){
					var v = arr[i].split('='),
						k = v[0],
						value = v[1] ? v[1] : '';
					obj[k] = value;
				}
				if( !key ){	//无key值，取全部
					return obj;
				}else{	//有key值，取key值对应值
					return obj[key];
				}
			}else{
				return '';
			}
		},
		getUrlPath : function(ind){
			var path = window.location.pathname;
			console.log(path)
			if( path ){
				var arr = path.split('/'),

					arr_val = [];
				for(var i = 0; i < arr.length; i++){
					if( arr[i] ){
						arr_val.push(arr[i]);
					}
				}
				console.log(arr_val);
				if( typeof ind == 'undefined' ){
					return arr_val[arr_val.length-1];
				}else{
					return arr_val[ind];
				}
			}else{
				return '';
			}
		},
		//注册验证
		RegCheck : {
			Default : function(reg,str){
				return reg.test(str);
			},
			Username : function(str){
				var reg = /^[_A-Za-z0-9]{6,16}$/;
				return reg.test(str);
			},
			Password : function(str){
				var reg = /^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/A-Za-z0-9]{6,16}$/;
				return reg.test(str);
			},
			Email : function(str){
				var reg = /^([a-zA-Z0-9]|[._])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
				return reg.test(str);
			},
			Tell : function(str){
				var reg = /^((0\d{2,3}-\d{7,8})|(1[35784]\d{9}))$/;
				return reg.test(str);
			},
			CN : function(str){
				var reg = /^[\u4E00-\u9FA5]+$/;
				return reg.test(str);
			}
		},
		Ajax : function(url,type,data,callback,dataType){
			if( !dataType ){
				dataType = 'json';
			}
			$.ajax({
				url : url,
				type : type,
				dataType : dataType,
				data : data
			}).done(function(res){
				if( typeof callback === 'function' ){
					callback(res);
				}
			}).fail(function(jqXHR, textStatus, errorThrown){
				$.ErrMsg('连接服务器失败!');
			})
		},
		JsonP : function(url,data,callback){
			$.Ajax(url,'get',data,function(res){
				if( typeof callback === 'function' ){
					callback(res);
				}
			},'jsonP');
		},
		Pop : function(opts){
			var def = {
				type : 'confirm',	//confirm, tip
				title : '',
				content : '',
				mask : true,
				btn : [
					{
						text : '取消',
						color : '#fb624a',
						callback : function(){}
					},
					{
						text : '确定',
						color : '#fb624a',
						callback : function(){}
					}
				],
				close : function(){}
			}
			opts = $.extend(def,opts);
			if( $('#Pop').size() ){
				$.PopClose();
			}
			if( opts.mask ){
				$.Mask('0,0,0,0.5');
			}
			var html = 	'<div id="Pop">'+
							'<div class="Pop-close">&times;</div>';
			if( opts.title ){
				html += '<div class="Pop-tit">'+ opts.title  +'</div>';
			}
			html +=	'<div class="Pop-con">'+ opts.content +'</div>';
			if( opts.type != 'tip' ){
				html +=	'<div class="Pop-btn">';
				if( opts.type == 'alert' ){
					html += '<span style="color:'+ opts.btn[0].color +'">'+ opts.btn[0].text +'</span>';
				}
				if( opts.type == 'confirm' ){
					html += '<span style="color:'+ opts.btn[0].color +'">'+ opts.btn[0].text +'</span>'+
							'<span style="color:'+ opts.btn[1].color +'">'+ opts.btn[1].text +'</span>';
				}
				html += '</div>';
			}	
			html +=	'</div>';
			$('body').append(html);
			var $Pop = $('#Pop');
			var h = $Pop.height() + 20;
			$Pop.css({
				'top' : '40%',
				'margin-top' : - h/2
			})
			$Pop.find('.Pop-btn span').on('click',function(){
				var ind = $(this).index();
				opts.btn[ind].callback();
				//$.PopClose(opts.mask);
			})
			$Pop.find('.Pop-close').on('click',function(){
				opts.close();
				$.PopClose(opts.mask);
			})
		},
		PopClose : function(mask){
			$('#Pop').remove();
			if( mask ){
				$.MaskRemove();
			}
		},
		Alert : function(opts){
			var def = {
				title : '提示框',
				content : '我是提示框',
				btnText : '确定',
				mask : true,
				callback : function(){
					$.PopClose(true);
				}
			}
			opts = $.extend(def,opts);
			console.log(opts);
			$.Pop({
				type : 'alert',
				title : opts.title,
				content : opts.content,
				mask : opts.mask,
				btn : [
					{
						text : opts.btnText,
						callback : function(){
							opts.callback();
						}
					}
				]
			})
		},
		Confirm : function(opts){
			var def = {
				mask : true,
				title : '确认框',
				content : '我是确认框',
				btnText_1 : '取消',
				callback_1 : function(){
					$.PopClose(true);
				},
				btnText_2 : '确定',
				callback_2 : function(){}
			}
			opts = $.extend(def,opts);
			$.Pop({
				type : 'confirm',
				mask : opts.mask,
				title : opts.title,
				content : opts.content,
				btn : [
					{
						text : opts.btnText_1,
						callback : function(){
							opts.callback_1();
						}
					},
					{
						text : opts.btnText_2,
						callback : function(){
							opts.callback_2();
						}
					}
				]
			})
		},
		Tip : function(content,mask){
			if( typeof mask == 'undefined' ){
				mask = true;
			}
			$.Pop({
				type : 'tip',
				mask : mask,
				content : '<div style="text-align:center; font-size: 14px;">'+ content +'</div>'
			})
			$('#Pop').css({
				'background-color' : 'rgba(0,0,0,0.9)',
				'color' : '#fff',
				'width' : '200px',
				'margin-left' : '-110px'
			}).find('.Pop-con').css({
				'padding' : '5px 0'
			}).parent().find('.Pop-close').remove();
			var t = setTimeout(function(){
				$('#Pop').fadeOut('fast',function(){
					$.PopClose(mask);
				});
				clearTimeout(t);
			},2000);
		}
	})
	$.fn.extend({
		lazyLoad : function(){
			var here = this;
			var winHeight = parseFloat($(window).height());
			console.log(here);
			function showImg(){
				$(here).each(function(){
					var imgH = $(this).height(),
						topVal = $(this).get(0).getBoundingClientRect().top;
					if( (topVal < (winHeight-(imgH/3))) && $(this).hasClass('lazy') ){
						var _here = this;
						var _img = new Image();
						_img.src = $(_here).data('original');
						$(_here).attr('data-original',0);
						$(_here).removeClass('lazy');
						$(_here).hide().attr('src',_img.src).fadeIn();
					}
				})
			}
			showImg();
			$(window).scroll(function(){
				showImg();
			})
		},
		TranslateVal : function(){
			if( $(this).size() > 1 ) return false;
			var matrix = $(this).css('-webkit-transform').split(',');
			if( matrix[0].indexOf('matrix3d') > -1 ){
				var val = {
					x : parseFloat(matrix[12]),
					y : parseFloat(matrix[13]),
					z : parseFloat(matrix[14])
				}
			}else{
				var val = {
					x : parseFloat(matrix[4]),
					y : parseFloat(matrix[5]),
					z : 0
				}
			}
			return val;
		},
		/*
		3d转换
		callback：动画后的回调
		*/
		TranslateMove : function(x,y,z,time,callback){
			return $(this).each(function(){
				var here = this;
				$(this).css({
					'-webkit-transform' : 'translate3d('+ x +'px, '+ y +'px, '+ z +'px)',
					'-webkit-transition-duration' : time + 's'
				})
				setTimeout(function(){
					typeof callback === 'function' ? callback.call(here) : null;
				},time*1000);
			});
		},
		Touch : function(opts){
			return $(this).each(function(){
				var def = {
					start : function(){},
					move : function(){},
					end : function(){}
				}
				var opt = $.extend(def,opts);

				var startX = startY = absX = absY = 0;
				$(this).on({
					'touchstart MSPointerDown pointerdown' : function(e){
						var E = e.originalEvent;
						absX = absY = 0;
						startX = E.touches[0].pageX,
						startY = E.touches[0].pageY;
						var re = {
							e : e,
							x : absX,
							y : absY
						}
						opt.start.call(this,re);
					},
					'touchmove MSPointerMove pointermove' : function(e){
						var E = e.originalEvent;
						var nowX = E.touches[0].pageX,
							nowY = E.touches[0].pageY;
						absX += (nowX - startX),
						absY += (nowY - startY);
						startX = nowX;
						startY = nowY;
						var re = {
							e : e,
							x : absX,
							y : absY
						}
						opt.move.call(this,re);
					},
					'touchend MSPointerUp pointerup' : function(){
						var re = {
							e : null,
							x : absX,
							y : absY
						}
						opt.end.call(this,re);
					}
				})
			})
		},
		BannerSwipe : function(){
			return $(this).each(function(){
				var w = $(this).width(),
					$wrap = $(this).find('.swipe-banner-wrap'),
					$list = $wrap.find('.swipe-list'),
					$ul = $list.find('ul'),
					$li = $ul.find('li'),
					len = $li.length,
					ul_w = w * len,
					max = w * (len + 1),
					$ind = $wrap.find('.swipe-ind');
				$li.width( w );
				var first = $li.eq(0).clone(),
					last = $li.eq(len-1).clone();
				$ul.width( (len + 2)*w );
				$ul.TranslateMove(-w,0,0,0).prepend(last).append(first);
				$li = $ul.find('li');

				var str = '';
				for(var i = 0; i < len; i++){
					str +='<div class="swipe-ind-item"></div>';
				}
				$ind.html(str);
				var $ind_item = $ind.find('.swipe-ind-item');
			
				function setInd(ind){
					$li.eq(ind).addClass('active').siblings().removeClass('active');
					$ind_item.eq(ind-1).addClass('active').siblings().removeClass('active');
					$ul.removeClass('moving');
				}

				var ind = 1;
				setInd(ind);

				var t = null;
				//自动切换
				function auto(){
					var move = -w;
					console.log(move);
					t = setInterval(function(){
						move = move - w;
						$ul.addClass('moving');
						$ul.TranslateMove(move,0,0,0.3,function(){
							if( move == -max ){
								move = -w;
								$(this).TranslateMove(move,0,0,0);
							}
							ind++;
							if( ind == (len + 1) ){
								ind = 1;
							}
							setInd(ind);
						})
					},3000);
				}
				auto();

				var s = 0;
				//绑定滑动事件
				$ul.Touch({
					start : function(re){
							if( !$ul.hasClass('moving') ){
								console.log('dd');
							s = $(this).TranslateVal().x;
						}
					},
					move : function(re){
						if( !$ul.hasClass('moving') ){
							if( Math.abs(re.x) > 5 ){
								re.e.preventDefault();
								if( t ){
									clearInterval(t);
								}
							}
							//滑动效果
							$(this).TranslateMove(s+re.x,0,0,0);
						}
					},
					//滑动开始
					end : function(re){
						console.log(re);
						console.log($ul.hasClass('moving'));
						if( !$ul.hasClass('moving') ){
							var move = s;
							if( Math.abs(re.x) < 50 ){
								$(this).TranslateMove(move,0,0,0.3,function(){
									$ul.removeClass('moving');
								});
							}else{
								$ul.addClass('moving');
								if( re.x < -50 ){
									move = move - w;
									ind++;
								}
								if( re.x > 50 ){
									move = move + w;
									ind--;
								}
								$(this).TranslateMove(move,0,0,0.3,function(){
									if( move == -max ){
										$(this).TranslateMove(-w,0,0,0);
									}
									if( move == 0 ){
										$(this).TranslateMove(-(max-w),0,0,0);
									}
									if( ind == (len + 1) ){
										ind = 1;
									}
									if( ind == 0 ){
										ind = len;
									}
									setInd(ind);
								});
							}
						}
					}
				})
			})
		}
	});

})(jQuery);