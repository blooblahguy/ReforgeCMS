// Template multiplication
$("body").on("click", "[data-template]", function() {
	var template = $($(this).attr("data-template"))
	var target = $(this).attr("data-target")
	var index = $(this).attr("data-index") || 0 

	$(target).append(template.html().replace(/\$i/gi, index));
	$(this).attr("data-index", parseInt(index) + 1)
})
$("body").on("click", "[data-cf]", function() {
	fetch_cf_settings_template()
})

// Template accordions
$("body").on("click", "[data-accordion]", function() {
	var target = $(this).attr("data-accordion")

	$(this).toggleClass("toggled")
	if (! $(target).hasClass("collapsed")) {
		var height = $(target).outerHeight()
		$(target).css("max-height", height + 100);
		setTimeout(function() {
			$(target).removeAttr("style")
		}, 200)
	}
	$(target).toggleClass("collapsed")
})

// Remove rows from html
$("body").on("click", "[data-remove]", function() {
	var target = $(this).attr("data-remove")

	var parent = $(this).parents(target).first()
	parent.remove()
})

function fetch_cf_settings_template() {
	// console.log("ftech")
	$(".cf_type").each(function(i, e) {
		if ($(e).hasClass("loaded")) { return; }
		$(e).addClass("loaded")
		var field = $(e).val()
		var id = $(e).attr("data-id")

		nanoajax.ajax({
				url:'/core/custom_fields/settings/'+field
			},
			function (code, response) {
				console.log("loaded", field)
				$(".field_options."+id).html(response)
			}
		)
	})
}

$("body").on("change", ".cf_type", function() {
	$(this).removeClass("loaded")
	fetch_cf_settings_template();
})
fetch_cf_settings_template();