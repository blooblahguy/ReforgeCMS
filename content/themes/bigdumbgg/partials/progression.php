<?php

$progression = get_option("bdg_progression");

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
