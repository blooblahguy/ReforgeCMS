<?php

$progression = get_option("bdg_progression");


//============================================
// DISPLAY DATA
//============================================
?>
<div class="progression">
	<h2>Raid Progression</h2>
	<div class="row g1">
		<div class="os-min">
			<a class="rank" target="_blank" href="https://raider.io<?= $progression['guild']['path']; ?>"><?= deslugify_title($progression['tier']); ?></a>
		</div>
		<div class="os"><?= $progression['score']; ?></div>
		<div class="os-min rank">
			US <span class="<?= $progression['rank_type']; ?>"><?= $progression['rank']; ?></span>
		</div>
	</div>
	<div class="row boss_kills">
		<? foreach ($progression['progress'] as $k => $boss) { ?>
			<div class="os">
				<img src="https://cdnassets.raider.io/images/<?= $progression['tier']; ?>/bossicons/<?= $boss['slug']; ?>.jpg" alt="<? $boss['slug']; ?>">
			</div>
			<? //debug ($boss); ?>
		<? } ?>
	</div>
	
	<?
	// debug($progression);
	?>
</div>
<? return; ?>
<div class="progression">
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
