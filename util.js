var log = console.log.bind(console)
$.fn.antiselect = function(b) {
	if (b === true) {
		$(this).css({"-webkit-touch-callout": "none", "-webkit-user-select": "none", "-khtml-user-select": "none", "-moz-user-select": "none", "-ms-user-select": "none", "user-select": "none"})
	}
	if (b === false) {
		$(this).css({"-webkit-touch-callout": "", "-webkit-user-select": "", "-khtml-user-select": "", "-moz-user-select": "", "-ms-user-select": "", "user-select": ""})
	}
}
$.expr[':'].contains = function(a, i, m) {
	return $(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
};
Object.defineProperty(Object.prototype, "define", {
    configurable: true,
    enumerable: false,
    writable: false,
    value: function (name, value) {
        if (Object[name]) {
            delete Object[name];
        }
        Object.defineProperty(this, name, {
            configurable: true,
            enumerable: false,
            value: value
        });
        return this.name;
    }
});
String.prototype.define('chomp', function(offset) {
	return this.substring(0, this.length - offset)
})
function chomp(input, offset) {
	return input.substring(0, input.length - offset)
}
String.prototype.define('capital', function() {
	return this.charAt(0).toUpperCase() + this.slice(1)
})
function capital(string) {
	return string.charAt(0).toUpperCase() + string.slice(1)
}
function footer() {
	var y = $(document).height() + ($("#footer").height()-$(document).height())
	$("#footer").css("padding-top", y/2-20)
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