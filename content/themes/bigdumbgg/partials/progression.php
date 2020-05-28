<?php

// return;

//=====================================================================
// Update DATA
//=====================================================================
$guild = get_data("https://raider.io/api/guilds/us/illidan/Big%20Dumb%20Guild");
$guild = json_decode($guild, true);

// tier
if (! $guild) { return; }

$tiers = $guild['guildDetails']['raidProgress'];
$tier = false;
$lastdate = false;
foreach ($tiers as $t) {
	if ( ! $lastdate || strtotime($t['encountersDefeated']['heroic'][0]['firstDefeated']) > $lastdate ) {
		$lastdate = strtotime($t['encountersDefeated']['heroic'][0]['firstDefeated']);
		$tier = $t;
	}
}

$raid_title = deslugify_title($tier['raid']);

// ranks
$ranks = $guild['guildDetails']['raidRankings'];
foreach ($ranks as $r) {
	if ($r['raid'] == $tier['raid']) {
		$ranks = $r;
		break;
	}
}

$ranks = end($ranks);

$us_rank = $ranks['mythic']['region'];
$world_rank = $ranks['mythic']['world'];
$rank_type = "blue";
if ($us_rank <= 10) {
	$rank_type = "orange";
} elseif ($us_rank < 100) {
	$rank_type = "purple";
}

// bosses & kills
$bosses = array();
$num_bosses = 0;
$num_killed = 0;
foreach ($tier['encountersDefeated']['normal'] as $k => $boss) {
	$bosses[$boss['slug']] = 'N';
	$num_bosses += 1;
}
foreach ($tier['encountersDefeated']['heroic'] as $k => $boss) {
	$bosses[$boss['slug']] = 'H';
}
foreach ($tier['encountersDefeated']['mythic'] as $k => $boss) {
	$bosses[$boss['slug']] = 'M';
	$num_killed += 1;
}

$progression = array(
	"tier" => $tier
	, "raid_title" => $raid_title
	, "us_rank" => $us_rank
	, "world_rank" => $world_rank
	, "rank_type" => $rank_type
	, "bosses" => $bosses
	, "num_bosses" => $num_bosses
	, "num_killed" => $num_killed
);

set_option("bdg_progression", serialize($progression));

//============================================
// DISPLAY DATA
//============================================
?>
<div class="widget progression">
	<div class="title text-left"><a href="http://raider.io/guilds/us/illidan/Big%20Dumb%20Guild" target="_blank" class="instance"><?= ucwords($progression['raid_title']);?></a> <div class="rank pull-right">US: <span class="<?= $progression['rank_type']; ?>"><?= $progression['us_rank']; ?></span></div></div>
	<div class="content">
		<div class="progression_main">
			<? foreach ($progression['bosses'] as $slug => $status) { ?>
				<div class="boss <? if ($status == "M") {echo "dead";} else {echo "alive";} ?>">
					<?= ucwords($status); ?>: <?= desanitize_title($slug); ?>
				</div>
			<? } ?>
		</div>
		<div class="progression_footer">
			<div class="clear"></div>
			<div class="hours">Tue/Wed/Thu/Mon 9-1 CST (16hr)</div>
		</div>
	</div>
</div>
