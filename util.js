var log = console.log.bind(console)

function antiselect(el, on) {
	var props = ["webkitTouchCallout", "webkitUserSelect", "khtmlUserSelect", "MozUserSelect", "msUserSelect", "userSelect"]
	props.forEach(function(p) {
		el.style[p] = on ? "none" : ""
	})
}

function findOptionContaining(select, text) {
	text = text.toUpperCase()
	return Array.prototype.find.call(select.options, function(o) {
		return o.textContent.toUpperCase().indexOf(text) >= 0
	})
}

function chomp(input, offset) {
	return input.substring(0, input.length - offset)
}
function capital(string) {
	return string.charAt(0).toUpperCase() + string.slice(1)
}
function footer() {
	var footerEl = document.getElementById("footer")
	var y = document.documentElement.scrollHeight + (footerEl.offsetHeight - document.documentElement.scrollHeight)
	footerEl.style.paddingTop = (y / 2 - 20) + "px"
}
function rotate(a) {
	var headers = document.querySelectorAll("#consonants tr:first-child th, #vowels tr:first-child th")
	headers.forEach(function(th) {
		var div = th.querySelector("div")
		if (a === true) {
			th.style.webkitTransform = "rotate(270deg) translate(-25px,40px)"
			th.style.MozTransform = "rotate(270deg) translate(-25px,40px)"
			th.style.whiteSpace = "nowrap"
			if (div) {
				div.style.height = "100px"
				div.style.width = "25px"
			}
		}
		if (a === false) {
			th.style.webkitTransform = ""
			if (div) {
				div.style.height = ""
				div.style.width = ""
			}
		}
	})
}
