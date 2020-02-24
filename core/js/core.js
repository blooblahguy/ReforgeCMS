$("body").on("input change paste", "[data-bind]", function() {
	var variable = $(this).attr("name")
	var type = this.tagName.toLowerCase()

	var value = $(this).val() || $(this).html()

	$("[data-value='"+variable+"']").each(function(i, e) {
		var type = e.tagName.toLowerCase()
		if (! $(e).attr("data-default")) {
			$(e).attr("data-default", $(e).val() || $(e).html())
		}
		if (value == "") {
			value = $(e).attr("data-default")
		}
		if (type == "input") {
			$(e).value(value)
		} else {
			$(e).html(value)
		}
	})
	
})