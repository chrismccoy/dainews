#!/usr/bin/php
<?php
set_time_limit(0);

require_once 'config.inc.php';
require_once 'dainews.class.php';

function o($s) {
	echo $s . "\n";
	flush();
}

$DAINEWS = new DAINEWS(DAI_USER, DAI_PASS);
$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

$total = 0;
$dupes = 0;

o(date('m/d/Y h:i A') . ' - checking for new celebrities...');
foreach ($alpha as $a) {
	o('spidering ' . $a);

	$celebs = 	$DAINEWS->getCelebsAlphaFname($a);

	foreach ($celebs as $c) {
		$idir = str_replace("%celebname%", $c['name'], IMG_DIR);

		if (!is_dir($idir)) {
			mkdir($idir, 0775);
		}

		$sql = sprintf("SELECT * FROM celebrities WHERE LOWER(name)='%s'",
			mysql_escape_string(strtolower($c['name'])));

		$res = mysql_query($sql);

		if (mysql_num_rows($res) == 0) {
			$sql = sprintf("INSERT INTO celebrities VALUES (0, '%s', '%s', '%s', 0, 0)", 
				mysql_escape_string($c['name']), mysql_escape_string($c['fname']), mysql_escape_string($c['lname']));

			mysql_query($sql);

			o('added ' . $c['name']);
			$total++;
		} else {
			o($c['name'] . ' is already in the database');
			$dupes++;
		}
	}
}

o('done: ' . $total . ' added, ' . $dupes . ' dupes');
?>
