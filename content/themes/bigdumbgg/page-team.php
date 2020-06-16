<? get_template_part("parts", "page-header"); ?>

<div class="container">
	<div class="row g1">
		<div class="os pady0">
			<div class="row g4 section content-middle">
				<div class="os-3 fill-primary">
					<?= get_file_contents_url(theme_url()."/img/bdgg.svg"); ?>
				</div>
				<div class="os">
					<h2 class='title'>BD<span class="text-primary">GG</span> - Illidan</h2>
					<h3>US WoW Raiding Team</h3>
					<?= $page->content; ?>
				</div>
			</div>
			<div class="acheivements">
				<?
				$fields = get_fields();
				?>
				<table>
					<thead>
						<tr>
							<th class="text-center" style="width: 80px">Rank</th>
							<th>Event</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
						<? foreach ($fields['achievements'] as $ach) {
							$color = "legendary";
							if (intval($ach['rank']) <= 3) {
								$color = "artifact";
							}
							
							?>
							<tr>
								<td class="text-center rank<?= $ach['rank']; ?> <?= $color; ?>"><?= $ach['rank']; ?></td>
								<td class="event"><a href="https://www.wowprogress.com/guild/us/illidan/Big+Dumb+Guild" target="_blank"><?= $ach['event']; ?></a></td>
								<td class="date"><?= $ach['date']; ?></td>
							</tr>
						<? } ?>
					</tbody>
				</table>
			</div>
			<?

			$players = new User();
			$role = new Role();

			$players = $user->query("SELECT users.*, roles.label as role FROM {$user->table} as users
				LEFT JOIN {$role->table} as roles ON roles.id = users.role_id 
				WHERE character_name != '' AND (users.role_id = 1 OR users.role_id = 2 OR users.role_id = 4)
				ORDER BY roles.priority ASC, users.role_id ASC, users.id ASC
			");

			$users = array();
			$groups = array();
			// global $wow_class_colors;

			// sort into roles and tabs
			foreach ($players as $user) {
				if ($user['role_id'] == 1 || $user['role_id'] == 2) {
					$user['role'] = "Leadership";
				} else {
					$user['role'] = "Raiders";
				}
				$groups[$user['role']] = $user['role'];
				$users[$user['role']][] = $user;
			}

			$groups['Leadership'] = "Leadership";

			foreach ($groups as $rank) {
				$players = $users[$rank];
				?>
				<h2 class="text-center"><?= $rank; ?></h2>
				<div class="row g1">
					<? foreach ($players as $p) { ?>
						<div class="os-6 os-lg-4">
							<div class="player_card">
								<strong class='<?= $p['class']; ?>'><?= $p['character_name']; ?></strong>
								<img src="/content/themes/bigdumbgg/img/<?= $p['class']; ?>_emblem.png" alt="">
							</div>
						</div>
					<? } ?>
				</div>
				<?
			}
			// $players = 
			// debug($groups);
			
			// do_action("page/{$page['id']}/content_high", $page);
			// get_template_part("parts", "content-mega");
			// do_action("page/{$page['id']}/content", $page);
			// do_action("page/{$page['id']}/content_low", $page);
			?>		
		</div>
		<div class="os-3 sidebar padt2">
			<? get_template_part("sidebar"); ?>
		</div>
	</div>
</div>