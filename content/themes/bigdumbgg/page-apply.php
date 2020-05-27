<? 
$id = $params['id'];
$rfa = RFApps();

$application = false;
if ($id) {
	$application = new Post();
	$application->load("*", array("id = :id", ":id" => $id));
}

get_template_part("parts", "page-header");

?>

<div class="container">
	<div class="row g1">
		<div class="os pady2">
			<?
			render_form($rfa->form, array(
				"hide_title" => true,
			));
			?>

		</div>
		<div class="os-3 sidebar padt2">
			<? get_template_part("sidebar"); ?>
		</div>
	</div>
</div>