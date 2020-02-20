<div class="row content-middle padb2">
	<div class="os-min padr2">
		<h2 class="marg0"><? echo $core->get("page_title"); ?></h2>
	</div>
	<div class="os padl2">
		<a href="post_types/edit/0" class="btn">Create New Post Type</a>
	</div>
</div>

<? 
$post_types = $db->exec("SELECT * FROM post_types ORDER BY `order` ASC");
display_results_table($post_types, array(
	'icon' => array(
		"label" => "Icon",
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
		"label" => "ID"
	),
)); ?>