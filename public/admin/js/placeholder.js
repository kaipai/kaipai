function placeholderFn() {
    placeholderSupport() || $("[placeholder]").focus(function() {
        var a = $(this);
        a.val() == a.attr("placeholder") && (a.val(""), a.removeClass("placeholder"))
    }).blur(function() {
        var a = $(this); ("" == a.val() || a.val() == a.attr("placeholder")) && (a.addClass("placeholder"), a.val(a.attr("placeholder")))
    }).blur(),
    "" === $("[placeholder]").value && ($("[placeholder]").value = $("[placeholder]").attr("placeholder"))
};
function placeholderSupport() {
    return "placeholder" in document.createElement("input")
};

$(function(){
	 placeholderFn();
	
	var b, c, d, f, g, h, a = !0;//定義全局
	$("#projectExperience").on("click", ".select_projectYearStart", function(a) {
		a.stopPropagation(), 
		$(".profile_select_normal").removeClass("select_focus"), 
		$(".boxUpDown").hide(), $(this).addClass("select_focus"),
		 $(this).siblings(".box_projectYearStart").show()
	}),
	$("#projectExperience").on("click", ".box_projectYearStart li", function(a) {
		var b, c;
		a.stopPropagation(), b = $(this).text(), 
		$(this).parents(".box_projectYearStart").siblings(".select_projectYearStart").val(b).css("color", "#333").removeClass("select_focus"), 
		$(this).parents(".box_projectYearStart").siblings(".projectYearStart").val(b), 
		$(this).parents(".box_projectYearStart").hide(), 
		c = $(this).parents(".projectForm"), 
		$(this).parents(".box_projectYearStart").siblings(".projectYearStart").hasClass("error") && $(this).parents(".projectForm").validate().element(c.find(".projectYearStart")), 
		c.find(".projectYearEnd").hasClass("error") ? $(this).parents(".projectForm").validate().element(c.find(".projectYearEnd")) : c.find(".projectMonthEnd").hasClass("error") && $(this).parents(".projectForm").validate().element(c.find(".projectMonthEnd"))
	}),
	d = {
		obj: $("#projectExperience"),
		resetForm: function() {
			this.obj.children(".projectEdit").find(".projectName").val(""), this.obj.children(".projectEdit").find(".thePost").val(""), this.obj.children(".projectEdit").find(".projectYearStart").val(""), this.obj.children(".projectEdit").find(".select_projectYearStart").css("color", "#999").val("开始年份"), this.obj.children(".projectEdit").find(".projectMonthStart").val(""), this.obj.children(".projectEdit").find(".select_projectMonthStart").css("color", "#999").val("开始月份"), this.obj.children(".projectEdit").find(".projectYearEnd").val(""), this.obj.children(".projectEdit").find(".select_projectYearEnd").css("color", "#999").val("结束年份"), this.obj.children(".projectEdit").find(".projectMonthEnd").val(""), this.obj.children(".projectEdit").find(".select_projectMonthEnd").css("color", "#999").val("结束月份"), this.obj.children(".projectEdit").find(".projectDescription").val(""), this.obj.children(".projectEdit").find(".word_count").children("span").text(500), this.obj.children(".projectEdit").find(".projectId").val("")
		},
		Add: function() {
			this.resetForm(), placeholderFn(), this.obj.children(".projectAdd").addClass("dn"), this.obj.children(".projectShow").addClass("dn"), this.obj.children(".projectEdit").removeClass("dn"), j(this.obj)
		},
		AddMore: function() {
			this.resetForm(), placeholderFn(), this.obj.children(".projectShow").prepend(this.obj.children(".projectEdit").children("form").clone()), this.obj.children(".projectShow").children("form").css("borderBottom", "1px solid #e5e5e5").show(), this.obj.children(".projectEdit").addClass("dn"), e(), j(this.obj)
		},
		Edit: function(a) {
			var m, b = a.parents("li"),
				c = b.attr("data-id"),
				d = b.find("div.f16").attr("data-proName"),
				f = b.find("div.f16").attr("data-posName"),
				g = b.find("div.f16").attr("data-startY"),
				h = b.find("div.f16").attr("data-startM"),
				i = b.find("div.f16").attr("data-endY"),
				k = b.find("div.f16").attr("data-endM"),
				l = b.find(".dl1").html();
			l && (l = $.trim(l.replace(/<br>/gi, ""))), this.resetForm(), m = this.obj.children(".projectEdit").children("form").clone(), m.find(".projectName").val(d), m.find(".thePost").val(f), "" != g && (m.find(".projectYearStart").val(g), m.find(".select_projectYearStart").css("color", "#333").val(g)), "" != h && (m.find(".projectMonthStart").val(h), m.find(".select_projectMonthStart").css("color", "#333").val(h)), "" != i && (m.find(".projectYearEnd").val(i), m.find(".select_projectYearEnd").css("color", "#333").val(i)), "" != k && (m.find(".projectMonthEnd").val(k), m.find(".select_projectMonthEnd").css("color", "#333").val(k)), "至今" == k && m.find(".select_projectMonthEnd").unbind("click"), m.find(".projectDescription").val(l), m.find(".word_count").children("span").text(500 - m.find(".projectDescription").val().length), m.find(".projectId").val(c), a.parents("li").prepend(m), placeholderFn(), a.parents("li").children(".projectList").addClass("dn"), e(), j(this.obj)
		},
		Del: function(a) {
			var b = a.parents("li").attr("data-id");
			$.ajax({
				url: ctx + "/projectExperience/delProject.json",
				type: "POST",
				data: {
					id: b
				}
			}).done(function(b) {
				b.success ? (a.parents("li").remove(), $("ul.plist li:last-child").addClass("noborder"), ("" == b.content.projectExperiences || null == b.content.projectExperiences) && (d.obj.children(".projectShow").addClass("dn"), d.obj.children(".projectEdit").addClass("dn"), d.obj.children(".projectAdd").removeClass("dn"), d.obj.children(".c_add").addClass("dn")), $("#lastChangedTime span").text(b.content.refreshTime), $("#resumeScore .which div").text(b.content.infoCompleteStatus.msg), $("#resumeScore .which span").attr("rel", b.content.infoCompleteStatus.nextStage), scoreChange(b.content.infoCompleteStatus.score), k(d.obj), b.content.isNew && changeAllIds(b.content.jsonIds)) : console.log(b.msg)
			})
		},
		AddCancel: function() {
			this.obj.find(".projectAdd").removeClass("dn"), this.obj.find(".projectShow").addClass("dn"), this.obj.find(".projectEdit").addClass("dn"), k(this.obj)
		},
		AddMoreCancel: function(a) {
			a.parents(".projectForm").siblings(".projectList").removeClass("dn"), a.parents(".projectForm").remove(), k(this.obj)
		}
	}, 
	d.obj.find(".plist li:last-child").addClass("noborder"), 
	d.obj.find(".projectAdd").bind("click", function() {
		d.Add()
	})
})