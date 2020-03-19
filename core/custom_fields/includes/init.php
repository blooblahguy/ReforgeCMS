<?
$local = dirname(__FILE__);

require "$local/functions.php";
require "$local/field.php";
require "$local/rule.php";

//===============================================
// Field Types
//===============================================
$fields = array();
// $fields["checkbox"] = "checkbox.php";
// $fields["color"] = "color.php";
// $fields["date"] = "date.php";
// $fields["file"] = "file.php";
// $fields["form"] = "form.php";
$fields["image"] = "image.php";
// $fields["link"] = "link.php";
// $fields["post"] = "post.php";
// $fields["radio"] = "radio.php";
$fields["select"] = "select.php";
$fields["text"] = "text.php";
$fields["textarea"] = "textarea.php";
// $fields["user"] = "user.php";
// $fields["wysiwyg"] = "wysiwyg.php";
// $fields["group"] = "groups/group.php";
$fields["flexible"] = "groups/flexible.php";
// $fields["tabs"] = "groups/tab.php";
$fields["repeater"] = "groups/repeater.php";
$fields["wysiwyg"] = "wysiwyg.php";



foreach ($fields as $type => $file) {
	$class_name = 'reforge_field_' . strtoupper($type);
	require "$local/fields/{$file}";
	new $class_name();
}


//===============================================
// Rule Types
//===============================================
$rules = array();
// $rules["form"] = "form.php";
// $rules["page"] = "page.php";
$rules["adminpage"] = "admin-page.php";
$rules["user"] = "user.php";
// $rules["userrole"] = "userrole.php";
// $rules["widget"] = "widget.php";

foreach ($rules as $type => $file) {
	$class_name = 'rcf_rule_' . strtoupper($type);
	require "$local/rules/{$file}";
	new $class_name();
}

?>