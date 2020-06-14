<?php
global $root;

$file = fopen($root."/core/imports/wp_posts.csv", "r");
$posts = array();

$index = 0;
while (($data = fgetcsv($file, 1000, ",")) !== false) {
	$index++;
	if ($index == 1) {
		debug($data);
		continue;
	}

	$posts[] = $data;
}

// debug($posts);

foreach ($posts as $data) {
	$created = $data[2];
	$title = $data[5];
	$content = $data[4];

	$post = new Post();
	$post->load("*", array("title = :title", ":title" => $title));

	$post->post_type = "news";
	$post->created = $created;
	$post->title = $title;
	$post->content = htmlspecialchars($content);
	$post->author = 1;

	// debug($post);

	// break;
	$post->save();
}

return;

// lets do users first
$file = fopen($root."/core/imports/users.csv", "r");

$users = array();

$index = 0;
while (($data = fgetcsv($file, 1000, ",")) !== false) {
	$index++;
	if ($index == 1) {
		continue;
	}

	// add to array
	$users[$data[4]] = $data;
}

foreach ($users as $email => $data) {
	$user = new User();
	$user->load("id", array("email = :email", ":email" => $email));

	$user->email = $email;
	$user->username = $data[1];
	$user->created = $data[6];
	if (! $user->role_id) {
		$user->role_id = 7; 
	}
	$user->save();

}

// debug($users);