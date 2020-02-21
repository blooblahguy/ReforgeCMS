<div class="row content-middle padb2">
	<div class="os-min padr2">
		<h2 class="marg0"><? echo $core->get("page_title"); ?></h2>
	</div>
	<div class="os padl2">
		<a href="/admin/roles/edit/0" class="btn">Add Role</a>
	</div>
</div>

<? 
$roles = $db->exec("SELECT roles.* FROM roles ORDER BY `order` ASC");

display_results_table($roles, array(
	'label' => array(
		"label" => "Label",
		"html" => '<a href="/admin/roles/edit/%2$d">%1$s</a>',
	),
	'slug' => array(
		"label" => "Slug",
		"html" => '<a href="/admin/roles/edit/%2$d">%1$s</a>',
	),
	'id' => array(
		"label" => "ID",
	),
)); ?>