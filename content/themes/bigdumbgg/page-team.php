<? get_template_part("parts", "page-header"); ?>

<div class="container">
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
						<td class="event"><a href="https://www.wowprogress.com/guild/us/illidan/Bdgg" target="_blank"><?= $ach['event']; ?></a></td>
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

	// sort into roles and tabs
	foreach ($players as $user) {
		// debug($user['role_id']);
		if ($user['role_id'] == 1 || $user['role_id'] == 2) {
			$user['role'] = "Leadership";
		} else {
			$user['role'] = "Raiders";
		}
		$groups[$user['role']] = $user['role'];
		$users[$user['role']][] = $user;
	}

	$groups['Leadership'] = "Leadership";

	$leadership = $users['Leadership'];
	$raiders = $users['Raiders'];

	foreach ($groups as $rank) {
		$players = $users[$rank];
		?>

		<h2 class="text-center"><?= $rank; ?></h2>
		<div class="row g1">
			<?

			foreach ($players as $k => $player) { ?>
				<div class="os-6 os-lg-4">
					<div class="player_card">
						
						<img class="disablemouse" src="/content/themes/bigdumbgg/img/<?= $player['class']; ?>_emblem.png" alt="">
						<div class="row g1 content-middle">
							<div class="os player_name <?= $player['class']; ?>">
								<?= $player['character_name']; ?>
							</div>
							<div class="os-min">
								<div class="row g05 content-middle user_socials">
									<? if ($player['twitch'] != "") { ?>
										<div class="os-min">
											<a href="https://twitch.tv/<?= $player['twitch']; ?>" target="blank" class="bg-twitch">
												<span class="svg fill-white">
													<? echo get_file_contents_url("/core/assets/img/twitch.svg"); ?>
												</span>
											</a>
										</div>
									<? } ?>
									<? if ($player['twitter'] != "") { ?>
										<div class="os-min">
											<a href="https://twitter.com/<?= $player['twitter']; ?>" target="blank" class="bg-twitter">
												<span class="svg fill-white">
													<? echo get_file_contents_url("/core/assets/img/twitter.svg"); ?>
												</span>
											</a>
										</div>
									<? } ?>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			<? } ?>
		</div>
		<br>
	<? } ?>
</div>