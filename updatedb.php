#!/usr/bin/php
<?php
$ts = time();

set_time_limit(0);

require_once 'config.inc.php';
require_once 'dainews.class.php';


function o($s) {
	echo $s . "\n";
}

$DAINEWS = new DAINEWS(DAI_USER, DAI_PASS);

$total = 0;
$dupes = 0;
$downloaded = 0;
$had = 0;
$tdownloaded = 0;
$thad = 0;

$celebs = $DAINEWS->getUpdatedCelebs(43200);

$celeb_names = '';
$ii = 0;

if (!empty($celebs)) {
foreach ($celebs as $c) {
	if ($ii > 0) {
		$celeb_names .= ', ';
	}

	$celeb_names .= "'" . mysql_escape_string($c['name']) . "'";
	$ii++;
}

$sql = "SELECT * FROM celebrities WHERE name IN (" . $celeb_names . ")";
$res = mysql_query($sql);

while ($c = mysql_fetch_assoc($res)) {
	$added = 0;
	$IMG_DIR = str_replace("%celebname%", $c['name'], IMG_DIR);
	o('spidering ' . $c['name']);
	if (($pics = $DAINEWS->getPics($c['firstname'], $c['lastname'])) !== FALSE) {
		o(count($pics) . ' pics found');

		foreach ($pics as $p) {
			if (!is_file($IMG_DIR . $p['img'])) {
				$im = $DAINEWS->getImg($p['img']);
				$fp = fopen($IMG_DIR . $p['img'], 'w');
				fwrite($fp, $im);
				fclose($fp);

				o('DOWNLOADED ' . IMG_DIR . $p['img']);
				$downloaded++;
			} else {
				$had++;
			}

			if (!is_file($IMG_DIR . $p['thumbfile'])) {
				$im = $DAINEWS->getThumb($p['img']);
				$fp = fopen($IMG_DIR . $p['thumbfile'], 'w');
				fwrite($fp, $im);
				fclose($fp);
				o('DOWNLOADED ' . THUMB_DIR . $p['thumbfile']);
				$tdownloaded++;
			} else {
				$thad++;
			}

			$sql2 = sprintf("SELECT * FROM images WHERE filename='%s' AND celeb_id=%d", mysql_escape_string($p['img']), $c['celeb_id']);
			$res2 = mysql_query($sql2);

			if (mysql_num_rows($res2) == 0) {
				$info = explode('x', $p['res']);

				$sql = sprintf("INSERT INTO images VALUES (0, %d, %d, %d, '%s', %d, %d, %d, '%s', 0, 0, 1)",
					$c['celeb_id'], $p['n'], $p['q'], date('Ymd'), filesize($IMG_DIR . $p['img']), $info[0], $info[1], mysql_escape_string($p['img']));

				mysql_query($sql);

				unset($info);

				$total++;
				$added++;
			} else {
				$dupes++;
			}

			mysql_free_result($res2);
		}
	}

	$sql = sprintf("UPDATE celebrities SET images=images+%d,last_crawl=%d WHERE celeb_id=%d", $added, time(), $c['celeb_id']);
	mysql_query($sql);

	o('spidered ' . $c['firstname'] . ' ' . $c['lastname'] . ' added ' . $added . ' pics');
	unset($c);
}

mysql_free_result($res);
}

o('done: ' . $total . ' images added, ' . $dupes . ' dupes, had ' . $had . ' images, dl\'d ' . $downloaded . ', had ' . $thad . ' thumbs, dl\'d ' . $tdownloaded . ', ' . (time()-$ts) . ' seconds');
?>
