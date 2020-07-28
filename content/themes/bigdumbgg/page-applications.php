<? 
get_template_part("parts", "page-header");
?>

<div class="container">
	<div class="os pady2">
		<? 
		$front = RFA_Front();
		if (! $params['id']) {
			$front->render_index();
		} else {
			$front->view_application($params['id']);
		}
		?>			
	</div>
</div>