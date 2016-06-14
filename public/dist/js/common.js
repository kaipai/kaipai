var flag=0;
$(document).on('click', '#rightArrow', function(){
    if(flag==1){
        $("#floatDivBoxs").animate({right: '-175px'},300);
        $(this).animate({right: '-5px'},300);
        $(this).css('background-position','-50px 0');
        flag=0;
    }else{
        $("#floatDivBoxs").animate({right: '0'},300);
        $(this).animate({right: '170px'},300);
        $(this).css('background-position','0px 0');
        flag=1;
    }
});

//快捷登录
function loginPOP() {
    layer.open({
        skin: 'loginBox',
        title:'快捷登录',
        type: 1,
        content: $('#loginPopBox')
    });
};

$(function() {
	$('img.lazy').lazyload({
		placeholder: "/plugins/jquery.lazyload/img/grey.gif",
		effect: "fadeIn",
		threshold: 300
	});
});
var current_url = window.location.href;
//倒计时start
(function($) {
    $.fn.yomi = function(st, page) {
        var data = "";
        var _DOM = null;
        var TIMER;
        var nol;
        var _serverTime = st;
        var _cupage = page;
        var createdom = function(dom) {
            _DOM = dom;
            data = $(dom).attr("data");
            data = data.replace(/-/g, "/");
            data = Math.round((new Date(data)).getTime() / 1000);
            //$.get("http://apiproxy.yishu.ts/gateway/servertime?format=json", {}, function (json) {
            //    _serverTime = json.ServerTime;
            //    initDom();
            //});
            initDom();
        };
        var initDom = function() {
            _serverTime = _serverTime.replace(/-/g, "/");
            var range = data - Math.round((new Date(_serverTime)).getTime() / 1000),
                secday = 86400,
                sechour = 3600,
                days = parseInt(range / secday),
                hours = parseInt((range % secday) / sechour),
                min = parseInt(((range % secday) % sechour) / 60),
                sec = ((range % secday) % sechour) % 60;
            $(_DOM).find(".yomiday").html(nol(days));
            $(_DOM).find(".yomihour").html(nol(hours));
            $(_DOM).find(".yomimin").html(nol(min));
            $(_DOM).find(".yomisec").html(nol(sec));
            if(page == 'member-auction'){
                if (range > 0) {
                    $(_DOM).append("<span class='gz_shi yomiday'></span><span class='gz_shi1'>天</span><span class='gz_shi yomihour'></span><span class='gz_shi1'>时</span><span class='gz_shi yomimin'></span><span class='gz_shi1'>分</span><span class='gz_shi yomisec'></span><span class='gz_shi1'>秒</span>");
                } else {
                    $(_DOM).append('<b>拍卖已结束</b>');
                }
            }else if(page == 'customization-detail'){
                if (range > 0) {
                    $(_DOM).append("<span class='ty_col yomiday'></span>天<span class='ty_col yomihour'></span>时<span class='ty_col yomimin'></span>分<span class='ty_col yomisec'></span>秒");
                } else {
                    $(_DOM).append('<b>拍卖已结束</b>');
                }
            }else if(page == 'customization-index'){
                if (range > 0) {
                    $(_DOM).append("<span class='dz_right02 dz_r yomiday'></span><span class='dz_right03'>天</span><span class='dz_right02 yomihour'></span><span class='split dz_right03'>时</span><span class='yomimin dz_right02'></span><span class='split dz_right03'>分</span><span class='yomisec dz_right02'></span><span class='split dz_right03'>秒</span>");
                } else {
                    $(_DOM).append('<b>拍卖已结束</b>');
                }
            }else{
                if (range > 0) {
                    $(_DOM).append("<ul class='yomi'><li class='yomiday'></li><li class='split'>天</li><li class='yomihour'></li><li class='split'>时</li><li class='yomimin'></li><li class='split'>分</li><li class='yomisec'></li><li class='split'>秒</li></ul>");
                } else {
                    $(_DOM).append('<b>拍卖已结束</b>');
                }
            }

        };
        var reflesh = function() {
            var range = data - Math.round((new Date()).getTime() / 1000),
                secday = 86400,
                sechour = 3600,
                days = parseInt(range / secday),
                hours = parseInt((range % secday) / sechour),
                min = parseInt(((range % secday) % sechour) / 60),
                sec = ((range % secday) % sechour) % 60;
            if (range < 0) {
                if (_cupage == "index") {
                    $(_DOM).prev().css("display", "none");
                    $(_DOM).html('<div style="font-size:20px"><b style="color:#999">拍卖已结束</b></div>');
                }
                if (_cupage == "list") {
                    $(_DOM).after('<div class="xg-over">已结束</div>');
                    //$(_DOM).html('<div style="margin-top:6px;font-size:20px"><b>拍卖已结束</b></div>');
                    $(_DOM).remove();
                }

                if (_cupage == "detail") {
                    $(".cpxx-c-c").html('<h3 style="text-align:center;line-height:140px;font-size:24px;">拍卖已结束</h3>');
                    $(".cpxx-date span").html("拍卖已结束");
                    $(".cpxx-date i").html("结束时间 " + $(_DOM).attr("data"));
                    $(".cpxx-date").next().remove(); //remove remind
                    $(_DOM).children().remove();
                }
                return;
            }
            $(_DOM).find(".yomiday").html(nol(days));
            $(_DOM).find(".yomihour").html(nol(hours));
            $(_DOM).find(".yomimin").html(nol(min));
            $(_DOM).find(".yomisec").html(nol(sec));
        };
        TIMER = setInterval(reflesh, 1000);
        nol = function(h) {
            return h > 9 ? h : '0' + h;
        };
        return this.each(function() {
            var $box = $(this);
            createdom($box);
        });
    };

})(jQuery);

(function($) {
    $.fn.yomi2 = function() {
        var data = "";
        var _DOM = null;
        var TIMER;
        createdom = function(dom) {
            _DOM = dom;
            data = $(dom).attr("data");
            data = data.replace(/-/g, "/");
            data = Math.round((new Date(data)).getTime());

            var range = data - Math.round((new Date()).getTime()),
                secday = 86400000,
                sechour = 3600000,
                days = parseInt(range / secday),
                hours = parseInt((range % secday) / sechour),
                min = parseInt(((range % secday) % sechour) / 60000),
                sec = parseInt((((range % secday) % sechour) % 60000) / 1000),
                haom = parseInt(((((range % secday) % sechour) % 60000) % 1000) / 100);


            $(_DOM).find(".yomiday").html(nol(days));
            $(_DOM).find(".yomihour").html(nol(hours));
            $(_DOM).find(".yomimin").html(nol(min));
            $(_DOM).find(".yomisec").html(nol(sec));
            $(_DOM).find(".yomihaom").html(nol(haom));


            if (range > 0) {
                setTimeout(function() {
                    $(_DOM).append('拍卖已结束')
                }, range)
                $(_DOM).append("<div class='yomi'><span class='yomitype'></span><span class='yomiday'></span><span class='split'>天</span><span class='yomihour'></span><span class='split'>时</span><span class='yomimin'></span><span class='split'>分</span><span class='yomisec'></span><span class='split'>秒</span></div>")
            } else {
                $(_DOM).append('')
            }

        };
        reflash = function() {
            var range = data - Math.round((new Date()).getTime()),
                secday = 86400000,
                sechour = 3600000,
                days = parseInt(range / secday),
                hours = parseInt((range % secday) / sechour),
                min = parseInt(((range % secday) % sechour) / 60000),
                sec = parseInt((((range % secday) % sechour) % 60000) / 1000),
                haom = parseInt(((((range % secday) % sechour) % 60000) % 1000) / 10);


            $(_DOM).find(".yomiday").html(nol(days));
            $(_DOM).find(".yomihour").html(nol(hours));
            $(_DOM).find(".yomimin").html(nol(min));
            $(_DOM).find(".yomisec").html(nol(sec));
            $(_DOM).find(".yomihaom").html(nol(haom));


        };
        TIMER = setInterval(reflash, 10);
        nol = function(h) {
            return h > 9 ? h : '0' + h;
        }
        return this.each(function() {
            var $box = $(this);
            createdom($box);
        });
    };

})(jQuery);
//倒计时start
var _CurrentServerTime;
var _PageTag; //根据不能的页面展示不同的排版
$.ajax({
    type: "get",
    async: false,
    url: "http://api.yishu.com/gateway/servertime?callback=initServerTime",
    success: function(json) {
        initServerTime.call(this, json);
    }
});

function initServerTime(data) {
    this._CurrentServerTime = data.ServerTime;
}

/*$(function() {
    if (_PageTag == undefined) {
        _PageTag = "";
    }
    $(".yomibox2").each(function() {

        $(this).yomi(_CurrentServerTime, _PageTag);
    });
});
*/


(function($) {
    $.extend({
        //定时隐藏
        clearTimeText: function(obj, msg) {
            console.log(msg);
            obj.text(msg);
            obj.show();
            setTimeout(function() {
                obj.text("");
                obj.hide();
            }, 3000);
        },
        //判断是数字
        isNumFun: function(num) {

            var re = /^[0-9]+.?[0-9]*$/;
            if (!re.test(num)) {
                return false;
            } else {
                return true;
            }

        },
        //判断是int
        isInt: function(num) {
            var re = /^[0-9]+[0-9]*$/;
            if (!re.test(num)) {
                return false;
            } else {
                return true;
            }

        },
        //手机验证
        isMobile: function(num) {
            var re = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
            if (!re.test(num)) {
                return false;
            } else {
                return true;
            }
        },
        //唯一性判断
        unique: function(arr) {
            var result = [],
                hash = {};
            for (var i = 0, elem;
                (elem = arr[i]) != null; i++) {
                if (!hash[elem]) {
                    result.push(elem);
                    hash[elem] = true;
                }
            }
            return result;
        },
        //除去重复参数
        randomFun: function(str) {
            return str + parseInt(Math.random() * 10000).toString() + new Date().getTime() + parseInt(Math.random() * 10000).toString();
        },
        //非空判断
        isNull: function(str) {
            if (str == null || str == undefined || str == '') {
                return true;
            }
        },

    })
})(jQuery);