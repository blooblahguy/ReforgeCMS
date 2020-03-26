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
}
hook_sorters();

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
