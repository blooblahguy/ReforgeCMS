// Template multiplication
$("body").on("click", "[data-template]", function() {
	var self = $(this)
	var target = $(this).attr("data-target")
	var template = $($(this).attr("data-template"))
	var replace = $(this).attr("data-replace")
	var data = replace.split(",")
	var html = template.html()

	$.each(data, function(i, e) {
		var r = self.attr("data-"+e)
		var repreg = new RegExp("\\$"+e, "gi")
		html = html.replace(repreg, r)
	})

	// var index = $(this).attr("data-index") || 0
	// var key = $(this).attr("data-key")
	// if (! key) {
	// 	key = index
	// }

	// .replace(/\$key/gi, key)

	$(target).append(html);
	// $(this).attr("data-index", parseInt(index) + 1)
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



!function($, e) {
	var rf = {}
	window.rf = rf

	// Template replication


	// dropdown field ajax
	rf.fetch_cfield = function() {
		$(".cf_dropdown").each(function(i, e) {
			if ($(e).hasClass("loaded")) { return; }
			$(e).addClass("loaded")
			var type = $(e).val()
			var key = $(e).data("key")
			var parent = $(e).data("parent")
			var order = $(".cf_fields > .entry").length

			nanoajax.ajax({
					url:'/core/custom_fields/settings/'+type+'?field_key='+key+'&parent_key='+parent+'&menu_order='+order
				},
				function (code, response) {
					// console.log("loaded", field)
					console.log(".field_options."+key)
					$(".field_options."+key).html(response)
				}
			)
		})
	}
	$("body").on("change", ".cf_dropdown", function() {
		$(this).removeClass("loaded")
		rf.fetch_cfield();
	})
	rf.fetch_cfield();

	// add CF row
	$("body").on("click", ".cf-add", function() {
		var template = $(".blank_field").html()
		var target = $($(this).data("target"))
		var key = rf.uniqid("field_")
		var parent = $(this).data("id")

		var html = template.replace(/\$key/gi, key)
		html = html.replace(/\$parent/gi, parent)
		
		target.append(html)

		rf.fetch_cfield();
	})

	// generate unique row id
	var a = "";
	rf.uniqid = function(t, e) {
		var i;
		void 0 === t && (t = "");
		var n = function(t, e) {
			return e < (t = parseInt(t, 10).toString(16)).length ? t.slice(t.length - e) : e > t.length ? Array(e - t.length + 1).join("0") + t : t
		};
		return a || (a = Math.floor(123456789 * Math.random())),
		a++,
		i = t,
		i += n(parseInt((new Date).getTime() / 1e3, 10), 8),
		i += n(a, 5),
		e && (i += (10 * Math.random()).toFixed(8).toString()),
		i
	}
}(cash)