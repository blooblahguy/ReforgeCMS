<?php


//=====================================================================
// Update DATA
//=====================================================================
$raid = "nyalotha-the-waking-city";

// first get x / x for tier
$total = get_data("https://raider.io/api/v1/guilds/profile?region=us&realm=illidan&name=BDGG&fields=raid_progression");
$total = json_decode($total, true);
$total = $total['raid_progression'][$raid];
$total = $total['total_bosses']."/".$total['mythic_bosses_killed'];

// now get specific kill and guild data
$ranks = get_data("https://raider.io/api/v1/raiding/raid-rankings?raid={$raid}&difficulty=mythic&region=us&realm=illidan&guilds=81");
$ranks = json_decode($ranks, true);
$ranks = reset($ranks['raidRankings']);

$rank_type = "blue";
if ($ranks['regionRank'] <= 3) {
	$rank_type = "legendary";
} elseif ($ranks['regionRank'] <= 10) {
	$rank_type = "orange";
} elseif ($ranks['regionRank'] < 100) {
	$rank_type = "purple";
}

// if we wanted to, we could get boss specific rankings from this
// https://raider.io/api/v1/raiding/boss-rankings?{$raid}&boss={$boss_slug}&difficulty=mythic&region=us&realm=illidan

$progression = array(
	"tier" => $raid,
	"guild" => $ranks['guild'],
	"rank" => $ranks['regionRank'],
	"rank_type" => $rank_type,
	"score" => $total,
	"progress" => $ranks['encountersDefeated'],
);

set_option("bdg_progression", serialize($progression));