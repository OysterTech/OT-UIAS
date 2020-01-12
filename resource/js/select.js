!
function(e) {
	function n(t) {
		if (l[t]) return l[t].exports;
		var o = l[t] = {
			i: t,
			l: !1,
			exports: {}
		};
		return e[t].call(o.exports, o, o.exports, n),
		o.l = !0,
		o.exports
	}
	var l = {};
	n.m = e,
	n.c = l,
	n.i = function(e) {
		return e
	},
	n.d = function(e, l, t) {
		n.o(e, l) || Object.defineProperty(e, l, {
			configurable: !1,
			enumerable: !0,
			get: t
		})
	},
	n.n = function(e) {
		var l = e && e.__esModule ?
		function() {
			return e.
			default
		}:
		function() {
			return e
		};
		return n.d(l, "a", l),
		l
	},
	n.o = function(e, n) {
		return Object.prototype.hasOwnProperty.call(e, n)
	},
	n.p = "",
	n(n.s = 0)
} ([function(e, n) { !
	function(e) {
		var n = "",
		l = [],
		t = {
			initialize: function(e) {
				var n = [],
				l = '<div id="selectContent" style="width: ' + e.width + ";height:" + e.height + '">' + e.content + "</div>";
				l += '<input type="hidden" id="selectValue" value="">'
				l += '<div id="maincontent">';
				for (var o = 0; o < e.data.length; o++) {
					l += '<div id="ofenModule">',
					l += '<span class="main-content">',
					l += '<span class="main-content-input">',
					l += '<input class="ofenInput" type="checkbox">' + e.data[o].name,
					l += "</span>";
					for (var a = 0; a < e.data[o].list.length; a++){
						1 == e.data[o].list[a].selected ? (n.push(e.data[o].list[a].optionName), l += '<span class="normal ofen-normal"><input type="checkbox" checked optionId=' + e.data[o].list[a].optionId + ">" + e.data[o].list[a].optionName + "</span>") : l += '<span class="normal ofen-normal"><input type="checkbox" optionId=' + e.data[o].list[a].optionId + ">" + e.data[o].list[a].optionName + "</span>";
					}
				}
				$("#" + e.el).append(l),
				$("body").on("click", "#" + e.el + " #selectContent",
					function(n) {
						$("#" + e.el + " #maincontent").slideToggle()
					}),
				$("#" + e.el + " .main-content-input").attr("arrM", n),
				"true" == e.show ? $("#" + e.el + " #maincontent").show() : $("#" + e.el + " #maincontent").hide();
				var c = e;
				t.defaltSelect(c);
				this.bindclick(e);
			},
			bindclick: function(e) {
				$(".mainSelect").on("click", "#" + e.el + " .ofenInput",
					function(n) {
						n.stopPropagation();
						var l = n.target;
						if (1 == $(this).prop("checked")) {
							$(n.target).parent().nextAll("span").find("input").prop("checked", !0),
							$(this).parent().css({
								background: e.selectBackground,
								color: e.selectColor
							}),
							$(n.target).parent().nextAll("span").css({
								background: e.selectBackground,
								color: e.selectColor
							});
							for (var o = 0; o < $("#" + e.el + " #ofenModule .normal").length; o++) for (var a = 0; a < $("#" + e.el + " #allModule .normal").length; a++) $($("#" + e.el + " #ofenModule .normal")[o]).text() == $($("#" + e.el + " #allModule .normal")[a]).text() && ($($("#" + e.el + " #allModule .normal")[a]).find("input").prop("checked", !0), $($("#" + e.el + " #allModule .normal")[a]).css({
								background: e.selectBackground,
								color: e.selectColor
							}), $("#" + e.el + " #allModule .normal").length == $("#" + e.el + " #allModule .normal :checked").length ? ($("#" + e.el + " #allModule .allInput").prop({
								checked: !0,
								indeterminate: !1
							}), $("#" + e.el + " #allModule .allInput").parent().css({
								background: e.selectBackground,
								color: e.selectColor
							})) : ($("#" + e.el + " #allModule .allInput").prop({
								checked: !1,
								indeterminate: !0
							}), $("#" + e.el + " #allModule .allInput").parent().css({
								background: e.selectBackground,
								color: e.selectColor
							})));
								$(n.target).prop({
									checked: !0,
									indeterminate: !1
								})
							} else {
								$(n.target).parent().nextAll("span").find("input").prop({
									checked: !1,
									indeterminate: !1
								}),
								$("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !1
								}),
								$(this).parent().css({
									background: e.background,
									color: e.color
								}),
								$(n.target).parent().nextAll("span").css({
									background: e.background,
									color: e.color
								});
								for (var o = 0; o < $("#" + e.el + " #ofenModule .normal").length; o++) for (var a = 0; a < $("#" + e.el + " #allModule .normal").length; a++) $($("#" + e.el + " #ofenModule .normal")[o]).text() == $($("#" + e.el + " #allModule .normal")[a]).text() && ($($("#" + e.el + " #allModule .normal")[a]).find("input").prop("checked", !1), $($("#" + e.el + " #allModule .normal")[a]).css({
									background: e.background,
									color: e.color
								}), 0 == $("#" + e.el + " #allModule .normal :checked").length ? ($("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !1
								}), $("#" + e.el + " #allModule .allInput").parent().css({
									background: e.background,
									color: e.color
								})) : ($("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !0
								}), $("#" + e.el + " #allModule .allInput").parent().css({
									background: e.selectBackground,
									color: e.selectColor
								})))
							}
							t.selectValue(e, l)
						}),
				$(".mainSelect").on("click", "#" + e.el + " .ofen-normal input",
					function(n) {
						n.stopPropagation();
						var l = n.target;
						if (1 == $(this).prop("checked")) {
							$(n.target).parent().css({
								background: e.selectBackground,
								color: e.selectColor
							}),
							$("#" + e.el + " #allModule .allInput").parent().css({
								background: e.selectBackground,
								color: e.selectColor
							}),
							$(n.target).parent().parent().children("span:first-child").css({
								background: e.selectBackground,
								color: e.selectColor
							});
							for (var o = 0; o < $("#" + e.el + " #allModule .normal").length; o++) $($("#" + e.el + " #allModule .normal")[o]).text() == $(this).parent().text() && ($($("#" + e.el + " #allModule .normal")[o]).find("input").prop("checked", !0), $($("#" + e.el + " #allModule .normal")[o]).css({
								background: e.selectBackground,
								color: e.selectColor
							}));
								$(n.target).parent().parent().children().nextAll("span").length == $(n.target).parent().parent().children().nextAll("span").find("input:checked").length ? ($(n.target).parent().parent().find("span:first").find("input").prop({
									checked: !0,
									indeterminate: !1
								}), $("#" + e.el + " #allModule .allInput").prop({
									checked: !0,
									indeterminate: !0
								})) : ($(n.target).parent().parent().find("span:first").find("input").prop({
									checked: !1,
									indeterminate: !0
								}), $("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !0
								})),
								$("#" + e.el + " #allModule .normal").length == $("#" + e.el + " #allModule .normal :checked").length ? $("#" + e.el + " #allModule .allInput").prop({
									checked: !0,
									indeterminate: !1
								}) : 0 == $("#" + e.el + " #allModule .normal :checked").length ? $("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !1
								}) : $("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !0
								})
							} else {
								0 == $(n.target).parent().parent().children().nextAll("span").find("input:checked").length ? ($(n.target).parent().parent().find("span:first").find("input").prop({
									checked: !1,
									indeterminate: !1
								}), $("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !1
								}), $(n.target).parent().parent().find("span:first").find("input").parent().css({
									background: e.background,
									color: e.color
								})) : ($(n.target).parent().parent().find("span:first").find("input").prop({
									checked: !1,
									indeterminate: !0
								}), $("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !0
								})),
								$(n.target).parent().css({
									background: e.background,
									color: e.color
								});
								for (var o = 0; o < $("#" + e.el + " #allModule .normal").length; o++) $($("#" + e.el + " #allModule .normal")[o]).text() == $(this).parent().text() && ($($("#" + e.el + " #allModule .normal")[o]).find("input").prop("checked", !1), $($("#" + e.el + " #allModule .normal")[o]).css({
									background: e.background,
									color: e.color
								}), 0 == $("#" + e.el + " #allModule .normal :checked").length ? ($("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !1
								}), $("#" + e.el + " #allModule .allInput").parent().css({
									background: e.background,
									color: e.color
								})) : $("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !0
								}))
							}
							t.selectValue(e, l)
						}),
				$(".mainSelect").on("click", "#" + e.el + " .allInput",
					function(n) {
						n.stopPropagation();
						var l = n.target;
						1 == $(this).prop("checked") ? ($("#" + e.el + " #ofenModule .normal input").prop("checked", !0), $("#" + e.el + " #allModule .normal input").prop("checked", !0), $("#" + e.el + " .ofenInput").prop({
							checked: !0,
							indeterminate: !1
						}), $("#" + e.el + " input").parent().css({
							background: e.selectBackground,
							color: e.selectColor
						})) : ($("#" + e.el + " #ofenModule .normal input").prop("checked", !1), $("#" + e.el + " #allModule .normal input").prop("checked", !1), $("#" + e.el + " .ofenInput").prop({
							checked: !1,
							indeterminate: !1
						}), $("#" + e.el + " input").parent().css({
							background: e.background,
							color: e.color
						})),
						t.selectValue(e, l)
					}),
				$(".mainSelect").on("click", "#" + e.el + " .all-normal input",
					function(n) {
						n.stopPropagation();
						var l = n.target;
						if (1 == $(this).prop("checked")) {
							$(n.target).parent().css({
								background: e.selectBackground,
								color: e.selectColor
							});
							for (var o = 0; o < $("#" + e.el + " #ofenModule .normal").length; o++) $($("#" + e.el + " #ofenModule .normal")[o]).text() == $(this).parent().text() && ($($("#" + e.el + " #ofenModule .normal")[o]).find("input").prop("checked", !0), $($("#" + e.el + " #ofenModule .normal")[o]).css({
								background: e.selectBackground,
								color: e.selectColor
							}));
								$("#" + e.el + " #ofenModule .normal").length == $("#" + e.el + " #ofenModule .normal :checked").length ? ($("#" + e.el + " .ofenInput").prop({
									checked: !0,
									indeterminate: !1
								}), $("#" + e.el + " .ofenInput").parent().css({
									background: e.selectBackground,
									color: e.selectColor
								})) : 0 == $("#" + e.el + " #ofenModule .normal :checked").length ? ($("#" + e.el + " .ofenInput").prop({
									checked: !1,
									indeterminate: !1
								}), $("#" + e.el + " .ofenInput").parent().css({
									background: e.background,
									color: e.color
								})) : ($("#" + e.el + " .ofenInput").prop({
									checked: !1,
									indeterminate: !0
								}), $("#" + e.el + " .ofenInput").parent().css({
									background: e.selectBackground,
									color: e.selectColor
								})),
								$("#" + e.el + " #allModule .normal").length == $("#" + e.el + " #allModule .normal :checked").length ? ($("#" + e.el + " #allModule .allInput").prop({
									checked: !0,
									indeterminate: !1
								}), $("#" + e.el + " .allInput").parent().css({
									background: e.selectBackground,
									color: e.selectColor
								})) : ($("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !0
								}), $("#" + e.el + " .allInput").parent().css({
									background: e.selectBackground,
									color: e.selectColor
								}))
							} else {
								0 == $("#" + e.el + " #allModule .normal :checked").length ? ($("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !1
								}), $("#" + e.el + " #allModule .allInput").parent().css({
									background: e.background,
									color: e.color
								})) : $("#" + e.el + " #allModule .allInput").prop({
									checked: !1,
									indeterminate: !0
								}),
								$(n.target).parent().css({
									background: e.background,
									color: e.color
								});
								for (var o = 0; o < $("#" + e.el + " #ofenModule .normal").length; o++) $($("#" + e.el + " #ofenModule .normal")[o]).text() == $(this).parent().text() && ($($("#" + e.el + " #ofenModule .normal")[o]).find("input").prop("checked", !1), $($("#" + e.el + " #ofenModule .normal")[o]).css({
									background: e.background,
									color: e.color
								}), 0 == $("#" + e.el + " #ofenModule .normal :checked").length ? ($("#" + e.el + " #ofenModule .ofenInput").prop({
									checked: !1,
									indeterminate: !1
								}), $("#" + e.el + " #ofenModule .ofenInput").parent().css({
									background: e.background,
									color: e.color
								})) : ($("#" + e.el + " #ofenModule .ofenInput").prop({
									checked: !1,
									indeterminate: !0
								}), $("#" + e.el + " #ofenModule .ofenInput").parent().css({
									background: e.selectBackground,
									color: e.selectColor
								})))
							}
							t.selectValue(e, l)
						})
			},
			selectValue: function(e, n) {
				l = [];
				var t = $("#" + e.el + " .main-content-input").nextAll().find("input:checked");
				$(t).each(function() {
					$(this).parent().hasClass("main-content-input") || l.push($(this).attr('optionId'))
				}),
				$.unique(l),
				$("#"+e.el+" #selectValue").val(l.join(','));
				//0 === l.length ? $("#" + e.el + " #selectContent").html(e.content) : $("#" + e.el + " #selectContent").html(l.join(","))
				0 === l.length ? $("#" + e.el + " #selectContent").html(e.content) : $("#" + e.el + " #selectContent").html("已选("+l.length+")");
		},
		defaltSelect: function(e) {
			if (l = $("#" + e.el + " .main-content-input").attr("arrm"), $("#" + e.el + " .main-content-input").each(function() {
				$(this).nextAll("span").find("input:checked").parent().css({
					background: e.selectBackground,
					color: e.selectColor
				});
				var n = $(this).nextAll("span").length,
				l = $(this).nextAll("span").find("input:checked").length;
				0 == l ? ($(this).find("input").prop({
					checked: !1,
					indeterminate: !1
				}), $(this).css({
					background: e.background,
					color: e.color
				})) : n == l ? ($(this).find("input").prop({
					checked: !0,
					indeterminate: !1
				}), $(this).css({
					background: e.selectBackground,
					color: e.selectColor
				})) : ($(this).find("input").prop({
					checked: !1,
					indeterminate: !0
				}), $(this).css({
					background: e.selectBackground,
					color: e.selectColor
				}))
			}), "double" == e.type) {
				var n = $("#" + e.el + " .main-content-input,.double").nextAll(".ofen-normal").find("input:checked"),
				t = $("#" + e.el + " .main-content-input,.double").nextAll(".ofen-normal").find("input:checked").length,
				o = $("#" + e.el + " .main-content-input,.double").nextAll(".all-normal").find("input"),
				a = $("#" + e.el + " .main-content-input,.double").nextAll(".all-normal").find("input").length;
				$(n).each(function() {
					var n = $(this);
					$(o).each(function() {
						$(n).parent().text() == $(this).parent().text() && ($(this).prop("checked", "true"), $(this).parent().css({
							background: e.selectBackground,
							color: e.selectColor
						})),
						t == a ? ($("#" + e.el + " .main-content-input,.double").find(".allInput").prop({
							checked: !0,
							indeterminate: !1
						}), $("#" + e.el + " .main-content-input,.double").find(".allInput").parent().css({
							background: e.selectBackground,
							color: e.selectColor
						})) : 0 === t ? ($("#" + e.el + " .main-content-input,.double").find(".allInput").prop({
							checked: !1,
							indeterminate: !1
						}), $("#" + e.el + " .main-content-input,.double").find(".allInput").parent().css({
							background: e.background,
							color: e.color
						})) : ($("#" + e.el + " .main-content-input,.double").find(".allInput").prop({
							checked: !1,
							indeterminate: !0
						}), $("#" + e.el + " .main-content-input,.double").find(".allInput").parent().css({
							background: e.selectBackground,
							color: e.selectColor
						}))
					})
				})
			}
			//null === l || "" === l || void 0 === l ? $("#" + e.el + " #selectContent").html(e.content) : $("#" + e.el + " #selectContent").html(l)
			null === l || "" === l || void 0 === l ? $("#" + e.el + " #selectContent").html(e.content) : $("#" + e.el + " #selectContent").html("已选("+l.split(',').length+")")
		}
	};
	window.selectTool = t
} ($)
}]);