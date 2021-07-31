<?php
$table = $content['table'];
$header = false;
$lines = array();

$table = preg_replace('~\r\n?~', "\n", $table);
$table = explode("\n", $table);
foreach ($table as $line) {
	$line = str_getcsv($line);
	if (! $header) {
		$header = $line;
		continue;
	}

	$lines[] = $line;
}
?>


<table>
	<thead>
		<tr>
			<? foreach($header as $h) { ?>
				<th><?= trim($h); ?></th>
			<? } ?>
		</tr>
	</thead>
	<tbody>
		<? foreach ($lines as $l) { ?>
			<tr>
				<? foreach ($l as $k => $e) { 
					$class = slugify($header[$k]);
					$val = trim($e);
					if (is_numeric($val)) {
						$val = (int) $val;
						if ($e <= 3) {
							$class .= " artifact";
						} elseif ($e <= 10) {
							$class .= " legendary";
						} elseif ($e <= 20) {
							$class .= " epic";
						} elseif ($e <= 50) {
							$class .= " rare";
						} else {
							$class .= " purple";
						}
					}
					?>
					<td class="<?= $class; ?>"><?= $val; ?></td>
				<? } ?>
			</tr>
		<? } ?>
	</tbody>
</table>