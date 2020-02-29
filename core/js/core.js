function update_binded_values(e) {
	var variable = $(e).attr("name")
	var type = e.tagName.toLowerCase()

	var value = $(e).val() || $(e).html()

	$("[data-value='"+variable+"']").each(function(i, obj) {
		var type = obj.tagName.toLowerCase()
		if (! $(obj).attr("data-default")) {
			$(obj).attr("data-default", $(obj).val() || $(obj).html())
		}
		if (value == "") {
			value = $(obj).attr("data-default")
		}
		if (type == "input") {
			$(obj).value(value)
		} else {
			$(obj).html(value)
		}
	})
}

$("body").on("input change paste", "[data-bind]", function() {
	update_binded_values(this) 
})

$("[data-bind]").each(function(i, e) {
	update_binded_values(e)
})