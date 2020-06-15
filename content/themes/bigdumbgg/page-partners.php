<? get_template_part("parts", "page-header"); ?>

<div class="container">
	<div class="row g1">
		<div class="os pady0">
			<?
			do_action("page/{$page['id']}/content_high", $page);

			// get_template_part("parts", "content-mega");

			$fields = get_fields();
			$partners = $fields['partners']; // debug($fields);
			foreach ($partners as $i => $partner) {
				$img = new File();
				$img->load("*", array("id = :id", ":id" => $partner['image']));
				$img = $img->get_size(400);

				$odd = $i % 2 == 0;
				// debug($odd);
				// debug($img);
				?>
				<div class="partner row g2 content-middle section<? if (! $odd) { echo " dark"; } ?>">
					<a href="<?= $partner['link']; ?>" target="_blank" class="os-4<? if (! $odd) { echo " order-last"; } ?>">
						<img src="<?= $img; ?>" alt="">
					</a>
					<div class="os">
						<h2><a href="<?= $partner['link']; ?>" target="_blank"><?= $partner['name']; ?></a></h2>
						<p><?= $partner['description']; ?></p>
					</div>
				</div>
				<?
			}

			do_action("page/{$page['id']}/content", $page);
			do_action("page/{$page['id']}/content_low", $page);
			?>		
		</div>
		<div class="os-3 sidebar padt2">
			<? get_template_part("sidebar"); ?>
		</div>
	</div>
</div>