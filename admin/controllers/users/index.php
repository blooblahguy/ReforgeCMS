<div class="row content-middle padb2">
	<div class="os-min padr2">
		<h2 class="marg0"><? echo $core->get("page_title"); ?></h2>
	</div>
	<div class="os padl2">
		<a href="users/edit/0" class="btn">Add User</a>
	</div>
</div>

<? 
$users = $db->exec("SELECT users.*, roles.label FROM users
	LEFT JOIN roles ON roles.id = users.role_id
	ORDER BY roles.menu_order ASC, users.role_id ASC, users.id ASC
"); 
?>

<table>
	<thead>
		<tr>
			<td>Username</td>
			<td>Role</td>
			<td>Email</td>
			<td>Last Login</td>
			<td>Member Since</td>
		</tr>
	</thead>
	<tbody>
		<? foreach ($users as $user) { ?>
			<td><a href="users/edit/<?= $user["id"]; ?>"><?= $user["username"]; ?></a></td>
			<td><a href="users/edit/<?= $user["id"]; ?>"><?= $user["label"]; ?></a></td>
			<td><a href="users/edit/<?= $user["id"]; ?>"><?= $user["email"]; ?></a></td>
			<td><a href="users/edit/<?= $user["id"]; ?>"><?= Date("Y-m-d", strtotime($user["last_login"])); ?></a></td>
			<td><a href="users/edit/<?= $user["id"]; ?>"><?= Date("Y", strtotime($user["created"])); ?></a></td>
		<? } ?>
	</tbody>
</table>