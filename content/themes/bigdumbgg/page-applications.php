<? 
get_template_part("parts", "page-header");
?>

<div class="container">
	<div class="row g1">
		<div class="os pady2">
			<? 
			$front = RFA_Applications_Front::instance();
			if (! $params['id']) {
				$front->render_index();
			} else {
				$front->view_application($params['id']);
			}
			?>			
		</div>
		<div class="os-3 sidebar padt2">
			<? get_template_part("sidebar"); ?>
		</div>
	</div>
</div>