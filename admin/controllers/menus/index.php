<?

?>

<div class="row content-middle padb2">
	<div class="os-min padr2">
		<h2 class="marg0">Menus</h2>
	</div>
	<div class="os padl2">
		<a href="/admin/menus/edit/0" class="btn">Add Menu</a>
	</div>
</div>

<? 
$fieldsets = $db->exec("SELECT * FROM menus"); 

display_results_table($fieldsets, array(
	"title" => array(
		"label" => "Title",
		"html" => '<a href="/admin/menus/edit/%2$d">%1$s</a>',
	),
	// 'username' => array(
	// 	"label" => "Username",
	// 	"html" => '<a href="/admin/users/edit/%2$d">%1$s</a>',
	// ),
	// 'email' => array(
	// 	"label" => "Email",
	// 	"html" => '<a href="/admin/users/edit/%2$d">%1$s</a>',
	// ),
	// 'role' => array(
	// 	"label" => "Role",
	// ),
	// 'last_login' => array(
	// 	"label" => "Last Login",
	// 	"calculate" => function($label, $id) {
	// 		return Date("Y-m-d", strtotime($label));
	// 	}
	// ),
	// 'created' => array(
	// 	"label" => "Member Since",
	// 	"calculate" => function($label, $id) {
	// 		return Date("Y-m", strtotime($label));
	// 	}
	// ),
)); ?>