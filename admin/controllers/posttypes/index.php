<div class="row content-middle padb2">
	<div class="os-min padr2">
		<h2 class="marg0" tooltip-right tooltip="Post types are what structure your website. Create a post type for news, pages, events, and whatever else you could need. From there you can add custom fields to your post types, and start building and displaying content through your website's theme."><? echo $core->get("page_title"); ?><i class="info">info</i></h2>
	</div>
	<div class="os padl2">
		<a href="post_types/edit/0" class="btn">Create New Post Type</a>
	</div>
</div>

<? 
$post_types = $db->exec("SELECT * FROM post_types ORDER BY `order` ASC");
display_results_table($post_types, array(
	'icon' => array(
		"label" => "",
		"class" => "min",
		"html" => '<i class="material-icons">%1$s</i>',
	),
	'label' => array(
		"label" => "Name",
		"html" => '<a href="post_types/edit/%2$d">%1$s</a>',
	),
	'slug' => array(
		"label" => "Slug",
		"html" => '<a href="post_types/edit/%2$d">%1$s</a>',
	),
	'children' => array(
		"label" => "Children",
	),
	'id' => array(
		"label" => "ID",
		"class" => "min"
	),
	'admin_menu' => array(
		"label" => "Admin Menu",
		"class" => "min",
		"calculate" => function($string, $id) {
			if ($string == 1) {
				return "Yes";
			} else {
				return "No";
			}
		}
	),
	'order' => array(
		"label" => "Order",
		"class" => "min"
	),
)); ?>