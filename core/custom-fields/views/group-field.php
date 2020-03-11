<?
	
	// debug($data);
	
	// Render the field
	echo '<div class="fields">';
		do_action("rcf/render_field", $data, $field);
		do_action("rcf/render_field/type={$field['type']}", $data, $field);
	echo '</div>';

?>