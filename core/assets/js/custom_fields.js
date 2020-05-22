// Row removal
$("body").on("click", ".group_remove a", function() {
	$(this).parents(".field_group_outer").first().remove()
	return false;
})

// specifically for custom fields
$("body").on("click", "[data-rcf-template]", function() {
	var target = $(this).data("target")
	var template = $($(this).data("rcf-template"))
	var replace = $(this).attr("data-replace")
	var index = $(this).data("index")
	var html = template.html()

	var repreg = new RegExp("\\$"+replace, "gi")
	html = html.replace(repreg, index)

	$(this).attr("data-index", parseInt(index) + 1)

	$(target).append(html);

	hook_editors()
	hook_sorters()
})

$("body").on("click", ".flexible_add", function() {
	var index = $(this).data("index")
	var siblings = $(this).siblings();

	siblings.each(function(i, e) {
		$(e).data("index", index)
	})
})



// Template multiplication
$("body").on("click", "[data-template]", function() {
	var self = $(this)
	var target = $(this).data("target")
	var template = $($(this).data("template"))

	var replace = $(this).attr("data-replace")
	if (replace) {
		var data = replace.split(",")
		var html = template.html()
		$.each(data, function(i, e) {
			var r = self.attr("data-"+e)
			var repreg = new RegExp("\\$"+e, "gi")
			html = html.replace(repreg, r)
		})
	}

	$(target).append(html);
	if ($(this).attr("data-index")) {
		$(this).attr("data-index", parseInt($(this).attr("data-index")) + 1)
	}

	hook_editors()
	hook_sorters()
})

// Template accordions
$("body").on("click", "[data-accordion]", function(event) {
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


	// =========================================================================================
	// Auto Slug
	// =========================================================================================
	$("body").on("blur", ".field-label", function() {
		var title_slug = slugify($(this).val())

		var parent = $(this).parents(".row").first()
		var slug = parent.find("input.field-slug")

		if (slug.val() == "") {
			slug.val(title_slug)
		}
	});

	// =========================================================================================
	// Rules
	// =========================================================================================
	// add AND
	$("body").on("click", ".rcf-remove-rule", function() {
		var parent = $(this).parents(".rule_group").first()
		var outer = $(this).parents(".rule_group_outer").first()

		$(this).parents(".condition_row").first().remove()

		var children = parent.children()
		if (children.length == 0) {
			outer.remove()
			console.log(children)
		}
	})
	$("body").on("click", ".rcf-add-rule", function() {
		console.log("here")
		var template = $(this).parents(".condition_row").first()
		var target = $($(this).data("target"))

		target.append(template.clone())
	})
	// add OR
	$("body").on("click", ".rcf-add-rulegroup", function() {
		var template = $(".blank_rule").html()
		var target = $($(this).data("target"))
		var index = $(this).data("index")

		var html = template.replace(/\$group/gi, index)

		target.append(html)

		$(this).data("index", ++index)
	})

	// rules ajax
	rf.fetch_rules = function() {
		$(".rule_values").each(function(i, e) {
			if ($(e).hasClass("loaded")) { return; }
			$(e).addClass("loaded")

			var parent = $(e).parents(".condition_row").first()
			var rule = parent.find(".load_key").first().val()

			console.log(rule)

			nanoajax.ajax({
					url:'/admin/custom_fields/rules/'+rule,
				},
				function (code, response) {
					$(e).html(response)
					console.log(response)
					// owner.find(".field_settings").first().html(response)
				}
			)
		})
	}
	$("body").on("change", ".load_key", function() {
		var parent = $(this).parents(".condition_row").first()
		var dropdown = parent.find(".rule_values").first()

		dropdown.removeClass("loaded")
		rf.fetch_rules();
	})

	// Template replication

	// dropdown field ajax
	rf.fetch_cfield = function() {
		$(".rcf_dropdown").each(function(i, e) {
			if ($(e).hasClass("loaded")) { return; }
			$(e).addClass("loaded")

			var type = $(e).val()

			var owner = $(e).parents(".rcf_field").first()
			var post_id = owner.data("post_id")
			var key = owner.data("key")
			var parent = owner.data("parent")
			var order = owner.siblings().length

			var url = '/admin/custom_fields/settings/'+type+'?field_key='+key+'&parent_key='+parent+'&menu_order='+order+'&post_id='+post_id;

			console.log(url)

			nanoajax.ajax({
					url: url,
				},
				function (code, response) {
					owner.find(".field_settings").first().html(response)

					$("[data-bind]").each(function(i, e) {
						update_binded_values(e)
					})
				}
			)
		})
	}

	$("body").on("change", ".rcf_dropdown", function() {
		$(this).removeClass("loaded")
		rf.fetch_cfield();
	})

	// add CF row
	$("body").on("click", ".cf-add", function() {
		var template = $(".blank_field").html()
		var target = $($(this).data("target"))
		var key = rf.uniqid("field_")
		var parent = $(this).data("parent")

		var html = template.replace(/\$key/gi, key)
		html = html.replace(/\$parent/gi, parent)

		var outer = $(this).parents('.fieldset_footer').first().prev()
		target = outer.children(target).first()

		target.append(html)

		var inserted = target.children().last().find("[data-accordion]").first()
		inserted.trigger("click")

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