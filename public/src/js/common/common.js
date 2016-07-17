(function($) {
	$.extend({
		clearTimeText: function(obj, msg) {

			obj.text(msg);
			console.log(msg);
			obj.show();
			setTimeout(function() {
				obj.text("");
				obj.hide();
			}, 3000);
		}
	})
	
})(jQuery);

/*
 图片上传全局方法
 inputEle：上传图片对象，callBack:回调方法
 */
function uploadFile(inputEle,callBack){
    inputEle.change(function() {
        var that = this;
        var J_uploadPar = $(that).parent();
        lrz(that.files[0], {
        }).then(function (rst) {
            console.log(rst);
            var img = new Image();
            img.src = rst.base64;
            J_uploadPar.find('img').remove();
            J_uploadPar.append(img);
            $.ajax({
                url: 'http://115.236.69.110:8453/qt360/file/uploadResourceFile',
                data: rst.formData,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function(data) {
                    var data = eval('(' + data + ')');

                    callBack(data);
                }
            });


            return rst;
        });
    })
};