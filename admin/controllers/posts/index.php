<?
$post_type = $core->get('post_type');
$type = new PostType();
$type->load("slug = '$post_type'");
?>

<div class="row content-middle padb2">
	<div class="os-min padr2">
		<h2 class="marg0"><?= $type->label_plural; ?></h2>
	</div>
	<div class="os padl2">
		<a href="users/edit/0" class="btn">Add <?= $type->label; ?></a>
	</div>
</div>

<? 
$posts = $db->exec("SELECT * FROM posts WHERE post_type = '{$post_type}' "); 

display_results_table($posts, array(
	"title" => array(
		"label" => "Title",
		"html" => '<a href="/admin/posts/'.$post_type.'/edit/%2$d">%1$s</a>',
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