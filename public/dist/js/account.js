var J_btn_getcode = $('#btn_getcode');
var J_mobile = $('#mobileNum');
var J_picVerify= $('#picVerifyCode');
var J_btn_getcode = $('#btn_getcode');
var reSend = true;
var re = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
J_btn_getcode.bind('click', function() {
	
	if (!re.test(J_mobile.val())) {
		layer.open({
			type: 0,
			icon: 2,

			content: '请输入正确的手机号码',
			shadeClose: true,
			time: 2000,
		});
		return;
	};
	if (J_picVerify.val()== "") {
		layer.open({
			type: 0,
			icon: 2,
			content: '验证码不能为空',
			shadeClose: true,
			time: 2000,
		});
		return
	};
	if (reSend) {
		console.log('0000');

		reSend = false; //禁止反复发送
		var html = '<b>5</b>秒重新获取';
		J_btn_getcode.addClass('btn_code_yz');
		J_btn_getcode.html(html);
		regCodeInterval = setInterval(function() {
			regCodeTimeinterval();
		}, 1000);

	}
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