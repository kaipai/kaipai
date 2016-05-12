function leftNav() {
	$("#leftBar dl").not("#leftBar dl.selected").click(function() {
		$(this).find("dt").hasClass("selected") ? ($(this).find("dt").removeClass("selected"), $(this).find("dd").hide()) : ($(this).find("dt").addClass("selected"), $(this).find("dd").show(), $(this).siblings().not("#leftBar dl.selected").find("dt").removeClass("selected"), $(this).siblings().not("#leftBar dl.selected").find("dd").hide())
	})
}
function setNav() {
	$("#nav li.twoNav").hover(function() {
		$(this).find(".hidden").stop(!0, !0).show();
		$(this).find("a.twoNav").css({
			color: "#ad0015",
			border: "1px solid #dcdcdc",
			"border-bottom": 0,
			background: "#fff"
		})
	}, function() {
		$(this).find(".hidden").stop(!0, !0).hide();
		$(this).find("a.twoNav").css({
			color: "#fff",
			border: "0",
			background: "#ad0015",
			height: "50px"
		})
	})
}
function addFavorite(n, t) {
	try {
		window.external.addFavorite(n, t)
	} catch (i) {
		try {
			window.sidebar.addPanel(t, n, "")
		} catch (i) {
			alert("不兼容此浏览器，请按 Ctrl+D 键添加到收藏夹!")
		}
	}
}
function SetHome(n, t) {
	try {
		n.style.behavior = "url(#default#homepage)";
		n.setHomePage(t)
	} catch (r) {
		if (window.netscape) {
			try {
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect")
			} catch (r) {
				alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将[signed.applets.codebase_principal_support]的值设置为'true',双击即可。")
			}
			var i = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefBranch);
			i.setCharPref("browser.startup.homepage", t)
		} else alert("您好,您的浏览器不支持自动设置页面为首页功能,请您手动在浏览器里设置该页面为首页!")
	}
}
$(function() {
	var f = document,
		i = $("input"),
		e = "placeholder" in f.createElement("input"),
		o = function(n) {
			var t = n.getAttribute("placeholder"),
				i = n.defaultValue;
			i == "" && (n.value = t);
			n.onfocus = function() {
				n.value === t && (this.value = "")
			};
			n.onblur = function() {
				n.value === "" && (this.value = t)
			}
		},
		n, r, t, u;
	if (!e) for (n = 0, r = i.length; n < r; n++) t = i[n], u = t.getAttribute("placeholder"), t.type === "text" && u && o(t)
}), function(n) {
	n.fn.showHidden = function() {
		var t = null;
		n(this).hover(function() {
			n(this).find(".hidden").show()
		}, function() {
			t = setTimeout(function() {
				n(this).find(".hidden").hide()
			}, 200)
		});
		n(this).hover(function() {
			clearTimeout(t)
		}, function() {
			n(this).find(".hidden").hide()
		})
	}
}(jQuery);
$(function() {
	var i;
	$(".bidOnline .bider #hidden").hide();
	$(".ViewLogistics").hide();
	var t = $(".bidOnline .bidTittle span"),
		n = !0,
		r = $(".bidOnline .bider dd.dd8 span");
	r.hover(function() {
		n ? ($(this).next().show(), n = !1) : ($(this).next().hide(), n = !0)
	});
	$(".OrderDetails_2_n p:first").css("background", "url(/Content/images/xg3.png) no-repeat left 5px");
	$("#list0 ul li input:first").parent().addClass("sec");
	$("#list0 ul li input:first").attr("checked", !0);
	$("#list0 ul li input").click(function() {
		$(this).parent().addClass("sec").siblings().removeClass("sec")
	});
	t.click(function() {
		$(this).addClass("selected").siblings().removeClass("selected");
		$(".bidOnline .bidcon >div").eq(t.index(this)).show().siblings().hide()
	});
	$(".xwdjf1_2_con li:even").css("background", "#f8fffa");
	$("#selectNav li").hover(function() {
		$(this).find("h5 i").css("background", "url(/Content/images/xjf11.png) no-repeat");
		$(this).find(".text").show()
	}, function() {
		$(this).find(".text").hide();
		$(this).find("h5 i").css("background", "url(/Content/images/xjf10.png) no-repeat")
	});
	i = $(".sqth6_tit0 li");
	$(".sqth6_tit0 li i").hide();
	$(".sqth6_tit0 li").click(function() {
		$(this).addClass("sec").siblings().removeClass();
		$(".sqth6_con> div").eq(i.index(this)).show().siblings().hide();
		$(this).children("i").show().end().siblings().children("i").hide()
	})
});
$(function() {
	var t, i, n;
	$(".tab-title1 span").hover(function() {
		$(this).addClass("selected").siblings().removeClass("selected");
		$(".tab-content1>div").eq($(".tab-title1 span").index(this)).show().siblings().hide()
	});
	leftNav();
	setNav();
	$("i.doubt").showHidden();
	$("#station .btn").click(function() {
		$(this).siblings("p.hidden").is(":hidden") ? ($(this).siblings("p.hidden").show(), $(this).css("background", "url(./images/icons/station2.jpg) no-repeat 0 0px")) : ($(this).siblings("p.hidden").hide(), $(this).css("background", "url(./images/icons/station2.jpg) no-repeat 0 -8px"))
	});
	$("#graybg").height($(window).height());
	$("#resolved").click(function() {
		$("#graybg").show();
		$(".compl-eject").show()
	});
	$("#affirm").click(function() {
		$("#graybg").hide();
		$(".compl-eject").hide()
	});
	$("#close").click(function() {
		$("#graybg").hide();
		$(".compl-eject").hide()
	});
	t = $(".enshrine .tabNav").find("p a:gt(7)");
	t.hide();
	$(".enshrine .tab .tabNav .btn").click(function() {
		$(this).siblings("p").find("a:gt(7)").is(":hidden") ? $(this).siblings("p").find("a:gt(7)").show() : $(this).siblings("p").find("a:gt(7)").hide()
	});
	$(".remainder .tab-content .tab ul:even").css("background", "#f9f9f9");
	$(".remainder .tab-content .tab ul li.li5").find("span").click(function() {
		$(this).parents("ul").remove()
	});
	$(".tradSwitch .tab-content .tab ul li.li7").find("span").click(function() {
		$(this).parents("ul").remove()
	});
	$(".tradSwitch .tab-content .tab").each(function(n, t) {
		$(t).find("ul:even").css("background", "#f9f9f9")
	});
	i = $(".recharge").outerHeight();
	$(".recharge").css("margin-top", -i / 2);
	$("#recharge1").click(function() {
		$("#graybg").show();
		$("#recharge").slideDown()
	});
	$(".recharge .charge-tit .close").click(function() {
		$("#graybg").fadeOut();
		$(this).parents(".recharge").slideUp()
	});
	n = $("#bankCard").find(".card");
	n.siblings(".card").hide();
	n.first().show();
	$("#bankCard .bankCard .card input.text").focus(function() {
		$(this).css({
			border: "1px solid #ccc",
			background: "#fff",
			top: "10px",
			left: "34px"
		})
	});
	$("#bankCard .bankCard .card input.text").blur(function() {
		$(this).css({
			border: 0,
			background: "none",
			top: "11px",
			left: "35px"
		})
	});
	$("#bankCard").find("h4").click(function() {
		$("#bankCard").find(".card").slideDown()
	});
	n.find("label").click(function() {
		$(this).parent().addClass("greybg").siblings().removeClass("greybg");
		$(this).parent().siblings().slideUp()
	})
});
$.format = function(n, t) {
	return arguments.length == 1 ?
	function() {
		var t = $.makeArray(arguments);
		return t.unshift(n), $.format.apply(this, t)
	} : (arguments.length > 2 && t.constructor != Array && (t = $.makeArray(arguments).slice(1)), t.constructor != Array && (t = [t]), $.each(t, function(t, i) {
		n = n.replace(new RegExp("\\{" + t + "\\}", "g"), i)
	}), n)
};
var ajax_Category = function(n, t, i) {
		var r = "";
		$.ajax({
			async: !1,
			url: "/Account/GetCategoryList/",
			datatype: "json",
			cache: !1,
			data: {
				pid: t
			},
			type: "post",
			success: function(t) {
				if (t != null) {
					r = "<option value='-100'>请选择分类<\/option>";
					for (var u = 0; u < t.length; u++) r = r + "<option value='" + t[u].id + "'>" + t[u].text + "<\/option>";
					n.html(r);
					i && i()
				}
			},
			error: function() {}
		})
	};
$(function() {
	$("input:not([autocomplete]),textarea:not([autocomplete]),select:not([autocomplete])").attr("autocomplete", "off")
}), function(n) {
	window.yishu = window.yishu || {};
	window.yishu.confirm = function(t, i, r) {
		function et() {
			ot();
			st()
		}
		function ot() {
			h.append(tt.append(a).append(k)).append(it.append(d).append(b)).append(rt.append(lt(g))).append(ut);
			s.attr("id", e).append(nt).append(h);
			n("body").append(s)
		}
		function st() {
			c.click(v);
			n(window).bind("keydown", function(t) {
				t.keyCode == 13 && n("#" + e).length == 1 && v()
			});
			l.click(ht);
			a.click(ct)
		}
		function v() {
			var t = n(this);
			f.onOk();
			n("#" + e).remove();
			f.onClose(o.ok)
		}
		function ht() {
			var t = n(this);
			f.onCancel();
			n("#" + e).remove();
			f.onClose(o.cancel)
		}
		function ct() {
			n("#" + e).remove();
			f.onClose(o.close);
			n(window).unbind("keydown")
		}
		function lt(t) {
			var i = n("<div>").addClass("btnGroup");
			return n.each(ft, function(n, r) {
				u[n] == (t & u[n]) && i.append(r)
			}), i
		}
		function y() {
			var t = "pop_" + (new Date).getTime() + parseInt(Math.random() * 1e5);
			return n("#" + t).length > 0 ? y() : t
		}
		var u = window.yishu.confirm.btnEnum,
			o = window.yishu.confirm.eventEnum,
			p = {
				info: {
					title: "信息",
					icon: "jbzl_7.png",
					btn: u.ok
				},
				success: {
					title: "成功",
					icon: "jbzl_6.png",
					btn: u.ok
				},
				error: {
					title: "错误",
					icon: "jbzl_9.png",
					btn: u.ok
				},
				confirm: {
					title: "提示",
					icon: "jbzl_8.png",
					btn: u.okcancel
				},
				warning: {
					title: "警告",
					icon: "jbzl_5.png",
					btn: u.ok
				},
				payment: {
					title: "支付",
					icon: "jbzl_5.png",
					btn: u.okcancel
				}
			},
			w = i ? i instanceof Object ? i : p[i] || {} : {},
			f = n.extend(!0, {
				title: "",
				icon: "",
				btn: u.ok,
				onOk: n.noop,
				onCancel: n.noop,
				onClose: n.noop
			}, w, r),
			b = n("<p>").html(t),
			k = n("<span>").text(f.title),
			d = n("<img>").attr("src", "/Content/images/" + f.icon),
			g = f.btn,
			e = y(),
			s = n("<div>").addClass("ysConfirm"),
			nt = n("<div>").addClass("ys_layer"),
			h = n("<div>").addClass("popBox"),
			tt = n("<div>").addClass("ttBox"),
			it = n("<div>").addClass("txtBox"),
			rt = n("<div>").addClass("btnArea"),
			ut = n("<div>").addClass("sgBtn-bottom").append("<p>艺术网拍卖会_中国古董古玩玉器钱币书画瓷器艺术品在线拍卖网站，感谢您的使用！<\/p>"),
			c = n("<a>").addClass("sgBtn").addClass("ok").text(i === "payment" ? "已完成支付" : "确定"),
			l = n("<a>").addClass("sgBtn").addClass("cancel").text(i === "payment" ? "遇到问题" : "取消"),
			a = n("<a>").addClass("clsBtn"),
			ft = {
				ok: c,
				cancel: l
			};
		et()
	};
	window.yishu.confirm.btnEnum = {
		ok: parseInt("0001", 2),
		cancel: parseInt("0010", 2),
		okcancel: parseInt("0011", 2)
	};
	window.yishu.confirm.eventEnum = {
		ok: 1,
		cancel: 2,
		close: 3
	};
	window.yishu.confirm.typeEnum = {
		info: "info",
		oprompt: "oprompt",
		success: "success",
		error: "error",
		confirm: "confirm",
		warning: "warning",
		payment: "payment"
	}
}(jQuery)