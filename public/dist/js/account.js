var J_mobile = $('#mobileNum');
var J_picVerify= $('#picVerifyCode');
var J_btn_getcode = $('#btn_getcode');
var reSend = true;
var re = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
J_btn_getcode.bind('click', function() {
	var mobile = J_mobile.val();
    var picVerifyCode = J_picVerify.val();
	if (!re.test(mobile)) {
		layer.open({
			type: 0,
			icon: 2,

			content: '请输入正确的手机号码',
			shadeClose: true,
			time: 2000
		});
		return;
	};
	if (picVerifyCode == "") {
		layer.open({
			type: 0,
			icon: 2,
			content: '验证码不能为空',
			shadeClose: true,
			time: 2000
		});
		return
	};
	if (reSend) {
        var flag = 1;
        $.ajax({
            url : '/login/get-verify-code',
            type : 'post',
            async : false,
            data : {mobile : mobile, picVerifyCode : picVerifyCode},
            dataType : 'json',
            success : function(data) {
                if(data.flag < 0){
                    flag = 0;
                    layer.open({
                        type: 0,
                        icon: 2,
                        content: data.msg,
                        shadeClose: true,
                        time: 2000
                    });
                }
            }
        });
        if(!flag) return;
		reSend = false; //禁止反复发送
		var html = '<b>5</b>秒重新获取';
		J_btn_getcode.addClass('btn_code_yz');
		J_btn_getcode.html(html);
		regCodeInterval = setInterval(function() {
			regCodeTimeinterval();
		}, 1000);

	}
});

$('#register').on('click', function(){
    var nickName = $('#nickName').val();
    var mobile = J_mobile.val();
    var verifyCode = $('#verifyCode').val();
    var password = $('#password').val();
    var serviceTerm = $('service-term').attr('checked');
    if(!nickName){
        layer.open({
            type : 0,
            icon : 2,
            content : '用户昵称不能为空',
            shadeClose: true,
            time: 2000
        });
        return;
    }
    if (!re.test(mobile)) {
        layer.open({
            type: 0,
            icon: 2,

            content: '请输入正确的手机号码',
            shadeClose: true,
            time: 2000
        });
        return;
    }
    if (!verifyCode) {
        layer.open({
            type: 0,
            icon: 2,
            content: '短信验证码不能为空',
            shadeClose: true,
            time: 2000
        });
        return
    }
    if (!password) {
        layer.open({
            type: 0,
            icon: 2,
            content: '短信验证码不能为空',
            shadeClose: true,
            time: 2000
        });
        return
    }
    if(!serviceTerm){
        layer.open({
            type : 0,
            icon : 2,
            content : '请阅读用户服务条款',
            shadeClose: true,
            time: 2000
        });
    }

    $.ajax({
        url : '/login/reg',
        type : 'post',
        dataType : 'json',
        data : {
            nickName : nickName,
            mobile : mobile,
            verifyCode : verifyCode,
            password : password
        },
        success : function(data){
            if(data.flag < 0){

            }else{

            }
        }

    });
});

//倒计时
function regCodeTimeinterval() {
	var currTime = J_btn_getcode.find('b').html();
	var newTime = parseInt(currTime) - 1;
	if (newTime) {
		J_btn_getcode.find('b').html(newTime);
	} else {
		clearInterval(regCodeInterval);
		J_btn_getcode.removeClass('btn_code_yz');
		J_btn_getcode.html('重新发送');
		reSend = true;
	}
};