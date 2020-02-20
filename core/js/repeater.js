
// Template multiplication
function bind_templates() {
	$("[data-template]").one("click", function() {
		var template = $($(this).attr("data-template"))
		var target = $(this).attr("data-target")
		var index = $(this).attr("data-index") || 0 
		console.log(index)

		$(target).append(template.html().replace("$i", index));
		// $(target).append(template.html());
		// template_index += 1
		$(this).attr("data-index", parseInt(index) + 1)

		rebind();
	})
}

// Remove rows from html
function bind_removers() {
	$("[data-remove]").one("click", function() {
		var target = $(this).attr("data-remove")


		console.log(target)
		var parent = $(this).parents(target).first()
		parent.remove()
	})
}

function rebind() {
	bind_removers()
	bind_templates()
}

rebind();