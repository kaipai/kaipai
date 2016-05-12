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
		},
	})
	
})(jQuery);