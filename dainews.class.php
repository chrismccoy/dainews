<?php

class DAINEWS {
	var $_baseurl = 'http://www.dainews.nu/DAINEWS/';
	var $_thumburl = 'http://216.98.166.148/%s.jpg';
	var $_user;
	var $_pass;
	var $_ch;

	function __construct($user, $pass) {
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_initCurl();
	}

	function __destruct() {
		curl_close($this->_ch);
	}

	function _initCurl() {
		$this->_ch = curl_init();
		curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->_ch, CURLOPT_USERPWD, DAI_USER . ':' . DAI_PASS);
	}

	function _getPage($url) {
		curl_setopt($this->_ch, CURLOPT_URL, $this->_baseurl . $url);
		$s = curl_exec($this->_ch);

		return $s;
	}

	function getCelebsAlphaFname($letter) {
		$result = FALSE;
		$s = $this->_getPage('index.html?state=Show_Alpha&first_name=' . $letter);
		$matches = array();
		$pattern = '/.*\?state=View_Alpha&f_n=([^&]*)&l_n=([^"]*)">([a-zA-Z]+[^<]+)<\/a>/';
		$m = preg_match_all($pattern, $s, $matches);

		if ($m) {
			$result = array();

			foreach ($matches[3] as $idx => $name) {
				$fname = urldecode($matches[1][$idx]);
				$lname = urldecode($matches[2][$idx]);
				$result[] = array('name' => $name, 'fname' => $fname, 'lname' => $lname);
			}
		}

		unset($matches);
		unset($m);
		unset($s);

		return $result;
	}

	function getCelebsAlphaLname($letter) {
		$result = FALSE;
		$s = $this->_getPage('index.html?state=Show_Alpha&last_name=' . $letter);
		$matches = array();
		$pattern = '/.*\?state=View_Alpha&f_n=([^&]*)&l_n=([^"]*)">([a-zA-Z]+[^<]+)<\/a>/';
		$m = preg_match_all($pattern, $s, $matches);

		if ($m) {
			$result = array();

			foreach ($matches[3] as $idx => $name) {
				$fname = urldecode($matches[1][$idx]);
				$lname = urldecode($matches[2][$idx]);
				$result[] = array('name' => $name, 'fname' => $fname, 'lname' => $lname);
			}
		}

		unset($matches);
		unset($m);
		unset($s);

		return $result;
	}

	function getPics($fname, $lname) {
		$result = FALSE;
		if (!stristr($fname, '%')) {
			$fname = urlencode($fname);
		}

		if (!stristr($lname, '%')) {
			$lname = urlencode($lname);
		}

		$s = $this->_getPage(sprintf('index.html?state=View_Text&f_n=%s&l_n=%s&n_lv=', $fname, $lname));

		if (stristr($s, 'Excessive Server Requests')) {
			sleep((60*5)+30);
		}

		$matches = array();
		$pattern = '/HREF="good\/([^"]+)">\s+(.*)<\/A>.*\s+Q:<font color=[^>]+>([0-9]+)<\/font> N:<font color=[^>]+>([0-9]+)<\/font> - ([0-9]+x[0-9]+) -([^\-]+)- ([^<]+)<br>/';
		$m = preg_match_all($pattern, $s, $matches);

		if ($m) {
			$result = array();
			foreach ($matches[1] as $idx => $img) {
				$quality = $matches[3][$idx];
				$nudity = $matches[4][$idx];
				$resolution = $matches[5][$idx];
				$fsize = strip_tags($matches[6][$idx]);
				$date = $matches[7][$idx];
				$thumburl = '' . $img . '.jpg';
				$result[] = array('img' => $img, 'q' => $quality, 'n' => $nudity, 'res' => $resolution,
					'fsize' => $fsize, 'date' => $date, 'thumb' => $thumburl, 'thumbfile' => $img . '.jpg');
			}
		}

		unset($matches);
		unset($m);
		unset($s);

		return $result;
	}

	function getUpdatedCelebs($nt = 172800) {
		$url = 'index.html?state=Show_New_Alpha&new_time=' . $nt;
		$s = $this->_getPage($url);
		$matches = array();
		$pattern = '/.*\?state=View_Alpha&f_n=([^&]*)&l_n=([^"]*)">([a-zA-Z]+[^<]+)<\/a>/';
		$m = preg_match_all($pattern, $s, $matches);

		if ($m) {
			$result = array();

			foreach ($matches[3] as $idx => $name) {
				$fname = urldecode($matches[1][$idx]);
				$lname = urldecode($matches[2][$idx]);
				$result[] = array('name' => $name, 'fname' => $fname, 'lname' => $lname);
			}
		}

		unset($matches);
		unset($m);
		unset($s);

		return $result;
	}

	function getImg($img) {
		return $this->_getPage('/good/' . $img);
	}

	function getThumb($img) {
		curl_setopt($this->_ch, CURLOPT_URL, sprintf($this->_thumburl, $img));
		return curl_exec($this->_ch);
	}
}
?>
