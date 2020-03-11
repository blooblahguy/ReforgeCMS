<?

// debug($fields);
// debug($source);

?>

<div class="rcf_fields">
	<? 
		if ($fields) {
			foreach( $fields as $i => $field ) {
				$data = $source[$field['key']];
				rcf_get_view('group-field', array( 'field' => $field, 'i' => $i, 'data' => $data ));
			}
		}
	?>
</div>