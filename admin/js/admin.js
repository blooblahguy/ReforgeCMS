$(".role_wrapper .toggle").on("click", update_role_checks)

function update_role_checks() {
	var admin = $(".role_wrapper.administrator .toggle").prop("checked")

	if (admin) {
		$(".role_wrapper").not(".administrator").addClass("disabled")
		$(".role_wrapper").not(".administrator").find(".toggle").prop("disabled", true)
	} else {
		$(".role_wrapper").each(function(i, e) {
			$(e).removeClass("disabled")
			var checkbox = $(e).find(".toggle");
			var checked = checkbox.prop("checked")
			$(".role_wrapper").find(".toggle").prop("disabled", false)
			if (checked) {
				$(e).addClass("enabled")
			} else {
				$(e).removeClass("enabled")
			}
		})
	}
}

function set_permalink() {
	var permalink = $("input.post_permalink")
	if (permalink.val() == "") {
		// console.log($(".post_title"))
		var title_slug = $("input.post_title").val()
			.replace(/\s+/g, '-') // Replace spaces with -
			.replace(/&/g, '-and-') // Replace & with 'and'
			.replace(/[^\w\-]+/g, '') // Remove all non-word characters
			.replace(/\-\-+/g, '-') // Replace multiple - with single -
			.replace(/^-+/, '') // Trim - from start of text
			.replace(/-+$/, '') // Trim - from end of text
			.toLowerCase()

		permalink.val(title_slug)
	}
}
$(".post_title").on("blur", function() {
	set_permalink();
});
set_permalink();

update_role_checks();