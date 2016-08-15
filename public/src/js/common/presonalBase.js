function leftNav() {
	$("#leftBar dl").not("#leftBar dl.selected").click(function() {
		$(this).find("dt").hasClass("selected") ? ($(this).find("dt").removeClass("selected"), $(this).find("dd").hide()) : ($(this).find("dt").addClass("selected"), $(this).find("dd").show(), $(this).siblings().not("#leftBar dl.selected").find("dt").removeClass("selected"), $(this).siblings().not("#leftBar dl.selected").find("dd").hide())
	})
};
$(function() {
	leftNav();
})