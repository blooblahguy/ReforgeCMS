let eventer = {}
function sendEvent(event) {
	$(eventer).trigger(event)
}
function onEvent(event, fn) {
	$(eventer).on(event, fn)
}

/** Functions */
function preventDefaults(e) {
	e.preventDefault();
	e.stopPropagation();
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
		.substring(0, 100)
}


function update_binded_values(e) {
	var variable = $(e).attr("name")
	var type = e.tagName.toLowerCase()

	if (type == "select" || type == "input" || type == "checkbox" || type == "textarea") {
		var value = $(e).val()
	} else {
		var value = $(e).html()
	}


	$("[data-hide-on-empty='"+variable+"']").each(function(i, obj) {
		if (value == "") {
			$(obj).hide()
		} else {
			$(obj).show()
		}
	})

	$("[data-value='"+variable+"']").each(function(i, obj) {
		var type = obj.tagName.toLowerCase()
		// if (! $(obj).attr("data-default")) {
		// 	$(obj).attr("data-default", $(obj).val() || $(obj).html())
		// }
		if (value == "" && $(obj).data("default")) {
			value = $(obj).data("default")
		}
		if (type == "input") {
			$(obj).val(value)
		} else {
			$(obj).html(value)
		}
	})
}

$("body").on("input blur change paste", "[data-bind]", function() {
	update_binded_values(this) 
})

function update_binds() {
	$("[data-bind]").each(function(i, e) {
		update_binded_values(e)
	})
}
update_binds()

/**
 * Extend Cash.js
 */
$.ajax = function(method, url, data, success, failure) {
	// console.log(this.tester)

	var xhr = new XMLHttpRequest();
	var formData = new FormData();
	xhr.open(method, url, true);

	xhr.addEventListener('readystatechange', function(e) {
		if (success && xhr.readyState == 4 && xhr.status == 200) {
			success(xhr.response)
			rf_media_initialize()
		} else if (failure && xhr.readyState == 4 && xhr.status != 200) {
			failure(xhr.response)
		}
	})

	$.each(data, function (i, e) {
		console.log(i, e)
		formData.append('i', e)
	})
	xhr.send(formData)
}

$.post = function(url, data, success, failure) {
	$.ajax("POST", url, data, success, failure)
}
$.get = function(url, data, success, failure) {
	$.ajax("GET", url, data, success, failure)
}


/**
 * Modal Windows
 */

$("body").on("click", ".modal_outer", function(e, e2) {
	if ($(e.target).hasClass("modal_outer")) {
		modal_destroy()
	}
})
$("body").on("click", ".modal_close", function() {
	modal_destroy()
})
$("body").on("keyup", function(ev) {
	var code = ev.code;
	if (code == "Escape") {
		modal_destroy()
	}
})
function modal_create() {
	var modal = $('<div class="modal_outer"><div class="modal_inner"><div class="modal_header row"><div class="modal_title os"></div><div class="modal_close os-min"><i class="close">close</i></div></div><div class="modal_body"></div></div></div>');

	$("body").prepend(modal)
}

function modal_destroy() {
	$(".modal_outer").remove()
}

function hook_sorters() {
	[].forEach.call(document.getElementsByClassName('menu_group'), function (el){
		new Sortable(el, { 
			group: 'menu',
			handle: ".dragger",
			direction: "vertical",
			invertSwap: true,
			animation: 150,
		});
	});

	[].forEach.call(document.getElementsByClassName('sortable'), function (el){
		new Sortable(el, { 
			group: 'sortable',
			handle: ".dragger",
			draggable: ".sort",
			// direction: "vertical",
			// invertSwap: true,
			animation: 150,
		});
	})
}
hook_sorters();


// tabs
$(".tabs").each(function() {
	if ($(this).find("[data-tab].active").length == 0) {
		$(".tab_nav [data-tab]").first().addClass("active")
		$(".tab_content[data-tab]").first().addClass("active")
	}
});
$(".tab_nav [data-tab]").on("click", function() {
	var tar = $(this).data("tab")
	$("[data-tab].active").removeClass('active')
	$('[data-tab="'+tar+'"]').addClass("active")

	return false	
})


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

// $(".sortable").each(function(i, e) {
	// var group = $(this).data("sort-group")
	// if (! group) { group = false; }

	// new Sortable(e, {
	// 	group: group,
	// 	handle: ".dragger",
	// 	draggable: ".sort",
	// 	ghostClass: "sortable-ghost",
	// 	onAdd: function (evt){
	// 		console.log("add", evt)
	// 	},
	// 	onUpdate: function (evt){
	// 		var itemEl = evt.item; // the current dragged HTMLElement
	// 		console.log("update", evt, itemEl)
	// 	},
	// 	onRemove: function (evt){
	// 		var itemEl = evt.item;
	// 		console.log("remove", evt, itemEl)
	// 	}
	// });

// })
