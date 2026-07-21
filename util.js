var log = console.log.bind(console)

function checkOk(r) {
	if (!r.ok) throw new Error("Request failed: " + r.status + " " + r.url)
	return r
}

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

// --- client-side chart rendering (replaces the old ipa.php endpoints) ---

function parseTableRows(text) {
	var lines = text.split(/\r\n|\n/)
	if (lines.length && lines[lines.length - 1] === "") lines.pop()
	return lines.map(function(line) {
		return line.split(",")
	})
}

function parseLetterSet(text) {
	var body = text.split(/\r\n|\n/)
	var notesAt = body.findIndex(function(line) { return line.trim() === "notes:" })
	if (notesAt !== -1) body = body.slice(0, notesAt)
	var set = new Set()
	body.forEach(function(line) {
		line = line.trim()
		if (!line) return
		var symbol = line.split(", ")[0]
		if (symbol) set.add(symbol)
	})
	return set
}

function titleCase(str) {
	// Mirrors PHP's ucwords(): uppercase the character after each whitespace
	// (or at the start of the string) and leave every other character as-is.
	return str.replace(/(^|\s)\S/g, function(c) {
		return c.toUpperCase()
	})
}

function wikiCaption(url) {
	var caption = (url || "").replace("http://en.wikipedia.org/wiki/", "").replace(/_/g, " ")
	return titleCase(caption)
}

function buildChartHalf(reference, rows, letterSet) {
	var x = parseInt(rows[0][0], 10)
	var y = parseInt(rows[0][1], 10)
	var subId = rows[1][0], leftLabel = rows[1][1], rightLabel = rows[1][2]
	var k = 3

	var html = "<div class='half'><table id=\"" + reference + "\"><tr><th></th>"
	for (var i = 0; i < x / 2; i++) {
		var row = rows[k]
		var label = row[1], url = row[2]
		var caption = wikiCaption(url)
		html += '<th colspan="2" class="rotate" id="' + label + '"><input type="checkbox" checked="checked" style="display: none;"><div><a target="_blank" href="' + url + '" title="' + caption + '">' + label + "</a></div></input></th>"
		k++
	}
	html += "</tr>"

	for (var j = 0; j < y; j++) {
		html += "<tr>"
		var side = rows[k]
		var sideLabel = side[1], sideUrl = side[2]
		var sideCaption = wikiCaption(sideUrl)
		html += "<th class='side' id='" + sideLabel + "'><input type='checkbox' checked='checked' style='display: none;'><div><a target='_blank' href='" + sideUrl + "' title='" + sideCaption + "'>" + sideLabel + "</a></div></input></th>"
		k++
		for (var i2 = 1; i2 < x + 1; i2++) {
			var cell = rows[k]
			var symbol = cell[1], url2 = cell[2], sound = cell[3], number = cell[4]
			if (symbol !== "" && letterSet.has(symbol)) {
				var caption2 = wikiCaption(url2)
				if (number !== "") caption2 += " (" + number + ")"
				html += "<td number='" + number + "' title='" + caption2 + "' id='" + symbol + "'><a href='" + url2 + "' title='" + caption2 + "' class='sound' target='_blank'>" + symbol + "</a>"
				if (sound !== "") {
					html += '<audio id="' + sound + '" src="' + sound + '" preload=none></audio>'
				}
				html += "</td>"
			} else {
				html += "<td></td>"
			}
			k++
		}
		html += "</tr>"
	}
	html += "</table>"
	html += "<table id='" + subId + "' class=sub><tr><th class='third'><input type='checkbox' checked='checked' style='display: none;'><a title='" + leftLabel + "'>" + leftLabel + "</a></input></th><th class='third'><input type='checkbox' checked='checked' style='display: none;'><a title='" + rightLabel + "'>" + rightLabel + "</a></input></th></tr></table>"
	html += "</div>"
	return html
}

function buildOtherTable(name, rows) {
	var html = "<table><tr><th style='text-align: center;' colspan='" + rows[0].length + "'>" + name + "</th></tr>"
	rows.forEach(function(row) {
		html += "<tr>"
		row.forEach(function(part, key) {
			html += key % 2 === 0 ? '<td class="clickah">' + part + "</td>" : "<td>" + part + "</td>"
		})
		html += "</tr>"
	})
	html += "</table>"
	return html
}
