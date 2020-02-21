<div class="row content-middle padb2">
	<div class="os-min padr2">
		<h2 class="marg0"><? echo $core->get("page_title"); ?></h2>
	</div>
	<div class="os padl2">
		<a href="/admin/roles/edit/0" class="btn">Add Role</a>
	</div>
</div>

<? 
$roles = $db->exec("SELECT roles.* FROM roles ORDER BY `priority` ASC");

display_results_table($roles, array(
	'label' => array(
		"label" => "Label",
		"html" => '<a href="/admin/roles/edit/%2$d">%1$s</a>',
	),
	'slug' => array(
		"label" => "Slug",
		"html" => '<a href="/admin/roles/edit/%2$d">%1$s</a>',
	),
	'users' => array(
		"label" => "Users",
		"calculate" => function($s, $id) {
			global $db;
			$rs = $db->exec("SELECT id FROM `users` WHERE role_id = $id");
			return (count($rs));
		},
	),
	'default' => array(
		"label" => "Default",
		"class" => "min",
		"calculate" => function($s) {
			if ($s == 1) {
				return "Yes";
			} else {
				return "No";
			}
		}
	),
	'actions' => array (
		"label" => "Actions",
		"class" => "min",
		"calculate" => function($s, $id) {
			return '<a href="/admin/roles/delete/'.$id.'" class="delete">Delete</a>';
		}
	)
)); ?>