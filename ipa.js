var log = console.log.bind(console)
var typeswitch = true
var soundswitch = true
var linkswitch = true
$.fn.antiselect = function(b) {
	if (b === true) {
		$(this).css({"-webkit-touch-callout": "none", "-webkit-user-select": "none", "-khtml-user-select": "none", "-moz-user-select": "none", "-ms-user-select": "none", "user-select": "none"})
	}
	if (b === false) {
		$(this).css({"-webkit-touch-callout": "", "-webkit-user-select": "", "-khtml-user-select": "", "-moz-user-select": "", "-ms-user-select": "", "user-select": ""})
	}
}
function reduce(chart) {
	var rows = $('#'+chart)[0].rows.length
	for (var i = rows - 1; i >= 2; i--) {
		var cols = $('#'+chart)[0].rows[i].cells.length-1
		var blankcount = 0
		for (var j = 0; j <= cols; j++) {if ($('#'+chart)[0].rows[i].cells[j].innerHTML === '') {blankcount++}}
		if (blankcount === cols) {$('#'+chart)[0].deleteRow(i)}
	}
	var rows = $('#'+chart)[0].rows.length
	var cols = $('#'+chart)[0].rows[rows-1].childNodes.length
	var buffer = false
	for (var i = 1; i < cols; i += 2) {
		if (buffer) {i -= 4; cols -= 2}
		var blankcount = 0
		var cellcount = 0
		for (var j=1; j<rows; j+=1) {
			if ($('#'+chart)[0].rows[j].cells[i].innerHTML === '') 	{blankcount++}
			if ($('#'+chart)[0].rows[j].cells[i+1].innerHTML === '')  {blankcount++}
			cellcount+=2
		}
		if (blankcount === cellcount) {
			clearcell(chart, 0, ((i + 1) / 2), false)
			for (var m = 1; m < rows; m += 1) {
				clearcell(chart, m, i, false)
				clearcell(chart, m, i, false)
			}
			buffer = true
		}
		else {buffer = false}
	}
}
function clearcell(chart, x, y, bool) {
	$('#'+chart)[0].rows[x].deleteCell(y);
	if(bool) {
		$('#'+chart)[0].rows[x].insertCell(y)
	}
}
function chomp(input, offset) {
	return input.substring(0, input.length-offset)
}
function capital(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}
function footer() {
	// $("textarea").width($(".half").eq(0).width() + $(".half").eq(1).width())
	var y = $(document).height() + ($("#footer").height()-$(document).height())
	$("#footer").css("padding-top", y-20)
}
function rotate(a) {
	if (a === true) {
		$("#consonants tr:eq(0) th, #vowels tr:eq(0) th").each(function() {
			$(this).css({"-webkit-transform": "rotate(270deg) translate(-25px,40px)", "-moz-transform": "rotate(270deg) translate(-25px,40px)", "white-space": "nowrap"})
			$(this).children("div").css({"height": "100", "width": "25"})
		})
	}
	if (a === false) {
		$("#consonants tr:eq(0) th, #vowels tr:eq(0) th").each(function() {
			$(this).css({"-webkit-transform": ""})
			$(this).children("div").css({"height": "", "width": ""})
		})
	}
}
function ipa() {
	reduce("consonants")
	reduce("vowels")
	$("a.sound").click(function(e) {
		if (linkswitch === true)	{return}
		e.preventDefault()
		if (soundswitch === true) {$(this).next('audio').get(0).play()}
		if (typeswitch === true) {$("textarea").val($("textarea").val()+$(this).html())}
	})

	var halves = ($(".half").eq(0).width() + $(".half").eq(1).width())
	$(".half").eq(0).css("margin-left", ($("body").width() - halves) / 2)
	footer()
	rotate(true)

	$(".rotate").toggle(
		function() {
			var ri = $(this)[0].cellIndex
			$(this).find("a").addClass("off")
			$(this).parent().parent().parent().find("tr:not(:first)").each(function() {
				$(this).children().eq(ri*2).find("a").css({"display": "none"})
				$(this).children().eq(ri*2-1).find("a").css({"display": "none"})
			})
		},
		function() {
			var ri = $(this)[0].cellIndex
			$(this).find("a").removeClass("off")
			$(this).parent().parent().parent().find("tr:not(:first)").each(function() {
				$(this).children().eq(ri*2).find("a").css({"display": "block"})
				$(this).children().eq(ri*2-1).find("a").css({"display": "block"})
			})
		}
	)
	$(".third").toggle(
		function() {
			$(this).find("a").addClass("off")
			var ri = $(this)[0].cellIndex+1
			var table = $(this).parents(".sub").parent().children("table").attr("id")

			$("#"+table).find("tr:not(:first)").each(function() {
				$(this).find("td").each(function(i) {
					if (i % 2 + 1 === ri) {
						$(this).find("a").css({"display": "none"})
					}
				})
			})
		},
		function() {
			$(this).find("a").removeClass("off")
			var ri = $(this)[0].cellIndex + 1
			var table = $(this).parents(".sub").parent().children("table").attr("id")

			$("#"+table).find("tr:not(:first)").each(function() {
				$(this).find("td").each(function(i) {
					if (i%2+1 === ri) {
						$(this).find("a").css({"display": "block"})
					}
				})
			})
		}
	)
	$(".side").toggle(
		function() {
			$(this).find("a").addClass("off"); 
			$(this).parent().find("td a").each(function() {
				$(this).css({"display": "none"})
			})
		},
		function() {
			$(this).find("a").removeClass("off"); 
			$(this).parent().find("td a").each(function() {
				$(this).css({"display": "block"})
			})
		}
	)

	$("#select_switch").toggle(
		function() {$(this).addClass("off"); $("th, td").antiselect(true)},
		function() {$(this).removeClass("off"); $("th, td").antiselect(false)}
	)
	$("#type_switch").toggle(
		function() {$(this).addClass("off"); typeswitch = false},
		function() {$(this).removeClass("off"); typeswitch = true}
	)
	$("#sound_switch").toggle(
		function() {$(this).addClass("off"); soundswitch = false},
		function() {$(this).removeClass("off"); soundswitch = true}
	)
	$("#link_switch").toggle(
		function() {$(this).addClass("off"); linkswitch = false},
		function() {$(this).removeClass("off"); linkswitch = true}
	)
	$("#rotate_switch").toggle(
		function() {$(this).addClass("off"); rotate(false)},
		function() {$(this).removeClass("off"); rotate(true)}
	)
	$("#clear_switch").click(function() {$("textarea").val("")})
}
$(function() {
	$.post("ipa.php", {"mode": "1"},
	function(data) {
		$("nav").html(data)
		$("#language").change(function() {
			filename = $(this).val()
			$.post("ipa.php",{"mode": "2", "filename":filename},
			function(data) {
				filename.indexOf(".") > -1 ? displayname = capital(chomp(filename, 4)) : displayname = filename
				$("title").html(displayname + " | An Interactive IPA Chart")
				$("#cv").html(data)
				ipa()
				$("#select_switch").click()
				$("#link_switch").click()
			})
		})
		$("#language").trigger("change")
	})
})