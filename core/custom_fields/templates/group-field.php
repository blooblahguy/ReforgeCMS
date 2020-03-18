<?

// Gaurantee data for blank groups
if (! isset($data['meta_key'])) {
	$data["meta_key"] = $field['slug'];
}
if (! isset($data['meta_info'])) {
	$data["meta_info"] = $field['key'];
}

$data = apply_filters("rcf/prepare_value/type={$field['type']}", $data, $field, $context);

// $name = preg_replace('/(?:_[0-9]_)/', "][][", $context);
$name = "rcf_meta[$context][meta_value]";
$meta_name = "rcf_meta[$context]";
$data['name'] = $name;
$data[$name] = $data["meta_value"];

$layout = "os-12";
if (isset($field["layout"])) {
	$layout = $field['layout'];
}

?>

<div class="custom_field <?= $layout; ?>">
	<div class="meta">
		<input type="hidden" name="<?= $meta_name; ?>[meta_key]" value="<?= $data['meta_key']; ?>" >
		<input type="hidden" name="<?= $meta_name; ?>[meta_info]" value="<?= $data['meta_info']; ?>" >
		<input type="hidden" name="<?= $meta_name; ?>[type]" value="<?= $field['type']; ?>" >
	</div>
	

	<? 
		do_action("rcf/html", $data, $field, $context);
		do_action("rcf/html/type={$field['type']}", $data, $field, $context);
	?>
	
	
</div>