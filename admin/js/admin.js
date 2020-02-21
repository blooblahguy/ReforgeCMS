$(".role_wrapper .toggle").on("click", update_role_checks)

function update_role_checks() {
	var admin = $(".role_wrapper.administrator .toggle").prop("checked")

	console.log(admin)

	if (admin) {
		$(".role_wrapper").not(".administrator").addClass("disabled")
		$(".role_wrapper").not(".administrator").find(".toggle").prop("disabled", true)
	} else {
		$(".role_wrapper").each(function(i, e) {
			$(e).removeClass("disabled")
			var checkbox = $(e).find(".toggle");
			var checked = checkbox.prop("checked")
			console.log(checked)
			$(".role_wrapper").find(".toggle").prop("disabled", false)
			if (checked) {
				$(e).addClass("enabled")
			} else {
				$(e).removeClass("enabled")
			}
		})
	}
}

update_role_checks();