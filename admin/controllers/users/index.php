<div class="row content-middle padb2">
	<div class="os-min padr2">
		<h2 class="marg0"><? echo $core->get("page_title"); ?></h2>
	</div>
	<div class="os padl2">
		<a href="users/edit/0" class="btn">Add User</a>
	</div>
</div>

<? 
$users = $db->exec("SELECT users.*, roles.label AS role FROM users
	LEFT JOIN roles ON roles.id = users.role_id
	ORDER BY roles.priority ASC, users.role_id ASC, users.id ASC
"); 

display_results_table($users, array(
	'username' => array(
		"label" => "Username",
		"html" => '<a href="/admin/users/edit/%2$d">%1$s</a>',
	),
	'email' => array(
		"label" => "Email",
		"html" => '<a href="/admin/users/edit/%2$d">%1$s</a>',
	),
	'role' => array(
		"label" => "Role",
	),
	'last_login' => array(
		"label" => "Last Login",
		"calculate" => function($label, $id) {
			return Date("Y-m-d", strtotime($label));
		}
	),
	'created' => array(
		"label" => "Member Since",
		"calculate" => function($label, $id) {
			return Date("Y-m", strtotime($label));
		}
	),
)); ?>