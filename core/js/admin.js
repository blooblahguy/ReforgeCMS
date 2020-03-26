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

function getWidthOfText(txt, fontname, fontsize){
    if(getWidthOfText.c === undefined){
        getWidthOfText.c=document.createElement('canvas');
        getWidthOfText.ctx=getWidthOfText.c.getContext('2d');
    }
    getWidthOfText.ctx.font = fontsize + ' ' + fontname;
    return getWidthOfText.ctx.measureText(txt).width;
}

function slugify(text) {
	if (! text) { return text; }
	return text.replace(/\s+/g, '-') // Replace spaces with -
		.replace(/&/g, '-and-') // Replace & with 'and'
		.replace(/[^\w\-]+/g, '') // Remove all non-word characters
		.replace(/\-\-+/g, '-') // Replace multiple - with single -
		.replace(/^-+/, '') // Trim - from start of text
		.replace(/-+$/, '') // Trim - from end of text
		.toLowerCase()
}

function set_seo_title() {
	var seo_title = $("input.seo_title")
	if (seo_title.val() == "") {
		var title_slug = $("input.post_title").val()
		seo_title.val(title_slug)
	}
}
function set_permalink() {
	var permalink = $("input.post_permalink")
	if (permalink.val() == "") {
		var title_slug = slugify($("input.post_title").val())
		permalink.val(title_slug)
	}
}
function set_file_slug() {
	var post_file = $("input.post_file")
	var slug = slugify($("input.post_permalink").val())
	if (slug != "") {
		post_file.val("partials/"+slug+".php")
	}
}

$(".post_permalink").on("blur", function() {
	set_file_slug();
});
$(".post_title").on("blur", function() {
	set_permalink();
	set_file_slug();
	set_seo_title();
});

set_permalink();
set_file_slug();
set_seo_title();
update_role_checks();

window.setTimeout(function() {
	$(".message_outer").css("opacity", "0")

	window.setTimeout(function() {
		$(".message_outer").hide();
	}, 300)
}, 3000)



function hook_editors() {
	$(".wysiwyg").not(".hooked").each(function(i, e) {
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

// Default SEO Values
if ($(".post_content") && $(".seo_description").val() == "") {
	var els = $(".content").find("#subtitle, .post_content textarea, .post_content .ql-editor").not('[field-slug="table"]')

	var default_seo_description = "";
	els.each(function(i, e) {
		var clone = $(e).clone()
		clone.find("h1").remove()
		clone.find("h2").remove()
		clone.find("h3").remove()
		clone.find("h4").remove()
		clone.find("h5").remove()

		var text = (clone.text() || clone.val()).trim();
		
		if (text) {
			var n = text.endsWith(".");
			if (! n) {
				text += ".";
			}
			default_seo_description += text
		}
	})

	if (default_seo_description.length > 160) {
		default_seo_description = default_seo_description.substring(0, 160)
		default_seo_description += "..."
	}

	$("#seo_description").attr("placeholder", default_seo_description)
	$("#default_seo").data("default", default_seo_description)
	$("#default_seo_description").val(default_seo_description)
	console.log(default_seo_description);
}

// quill.on("text-change", sync_editor)
// function sync_editor() {
// 	var content = quill.getContent();
// 	console.log(content)
// }