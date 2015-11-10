var table
$("#extras").find("li").toggle(function() {
	table = $(this).html()
	$(this).addClass("off")
	$("#"+table).remove()
	popup = $("#extras").append("<div class='popup' id='"+table+"'></div>")

	$("#"+table).css({"position":"fixed", "left":"150px", "top":"150px", "width":"320px", "height":"340px", "z-index":"5", "background-color":"white"})
	$("#"+table).append("<div class='popupsize'></div>")
	$("#"+table).find(".popupsize").css({"height":20, "width":20, "position":"absolute", "right":0, "bottom":0, "border":"1px solid black"})
	function adjustpopup() {
		$(".popup").each(function() {
			$(this).width($(this).children("table").width()+2)
			$(this).height($(this).children("table").height()+2)
		})

	}

	$.post("ipa.php", {"mode":4, "table":table}, function(data) {
		$("#"+table).append(data)
		adjustpopup()
		$(".clickah").click(function() {
			$("textarea").val($("textarea").val()+$(this).html())
		})
	},
	'text')



	popupsize = false
	$("#"+table).mousedown(function(e) {
		if (popupsize === true) {return}
		e.originalEvent.preventDefault()
		pressed = true
		popup = $(this)
		startX = $(this).left()
		startY = $(this).top()
		moveX = e.pageX
		moveY = e.pageY
		$(document).mousemove(function(e) {
			if (pressed === true) {
				popup.css({
					"left": (e.pageX-startX),
					"top":  (e.pageY-startY)
				})
			}
		})
		$(document).mouseup(function(e) {pressed = false; $(".popup").css("cursor", ""); $(document).unbind("mousemove").unbind("mouseup")})
	})

	$("#"+table).find(".popupsize").mousedown(function(e) {
		popupsize = true
		e.originalEvent.preventDefault()
		pressed = true
		popup = $(this).parent()
		startX = $(this).parent().width()
		startY = $(this).parent().height()
		moveX = e.pageX
		moveY = e.pageY
		$(document).mousemove(function(e) {
			if(pressed) {
				popup.width(e.pageX-moveX+startX)
				popup.height(e.pageY-moveY+startY)
				popup.children("table").width(e.pageX-moveX+startX)
				popup.children("table").height(e.pageY-moveY+startY)
			}
		})
		$(document).mouseup(function() {pressed = false; popupsize = false; $(document).unbind("mousemove").unbind("mouseup")})
	})
}, function() {
	$("#"+table).remove(); 
	$(this).removeClass("off")
})