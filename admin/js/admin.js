$(".role_wrapper .toggle").on("click", update_role_checks)

function update_role_checks() {
	var admin = $(".role_wrapper.administrator .toggle").prop("checked")

	if (admin) {
		$(".role_wrapper.administrator").addClass("enabled")
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

window.setTimeout(function() {
	$(".message_outer").css("opacity", "0")

	window.setTimeout(function() {
		$(".message_outer").hide();
	}, 300)
}, 3000)



function hook_editors() {
	$(".wysiwg").not(".hooked").each(function(i, e) {
		var quill_div = $(this)
		quill_div.addClass("hooked")
		var input = $(this).siblings(".wysiwyg_input").first();
		var form = input.parents("form").first();

		// Text Editor
		var quill = new Quill(this, {
			modules: {
				toolbar: [
					['bold', 'italic', 'underline', 'strike'], // toggled buttons
					['blockquote', 'code-block'],
					['link'],
					['image'],

					[{ 'header': 1 }, { 'header': 2 }], // custom button values
					[{ 'list': 'ordered'}, { 'list': 'bullet' }],
					[{ 'indent': '-1'}, { 'indent': '+1' }], // outdent/indent

					[{ 'header': [1, 2, 3, 4, 5, 6, false] }],

					[{ 'color': [] }, { 'background': [] }], // dropdown with defaults from theme

					['clean'] // remove formatting button
				]
			},
			theme: 'snow',
		});

		form.on("submit", function() {
			var value = quill_div.find(".ql-editor").first().html();
			input.val(value)
		})
	})
}
hook_editors()

// quill.on("text-change", sync_editor)
// function sync_editor() {
// 	var content = quill.getContent();
// 	console.log(content)
// }