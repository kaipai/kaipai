$(function() {
	$(".slideBoxx").slide({
		mainCell: ".bd ul",
		autoPlay: true
	});
	$(".slideBox").slide({
		mainCell: ".bd ul",
		autoPlay: true,
		effect: "left",
		delayTime: 700
	});
	//detail page bottom
	$(".luara-left").slide({
		titCell: ".adtit ol",
		mainCell: ".adcont ul",
		vis: 1,
		scroll: 1,
		effect: "leftLoop",
		interTime: 3500,
		delayTime: 375,
		autoPlay: true,
		autoPage: true
	});
	$(".qmenunav li").hover(function() {
		$(this).children(".sqm").css("width", Math.ceil($(this).find(".cc").val() / 11) * 120 + "px").animate({
			opacity: 'show'
		}, 400);
	}, function() {
		$(this).children(".sqm").css("width", Math.ceil($(this).find(".cc").val() / 11) * 120 + "px").animate({
			opacity: 'hide'
		}, 400);
	});
})