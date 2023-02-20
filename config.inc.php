<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'database');
define('DB_USER', 'dbuser');
define('DB_PASS', 'dbpass');

define('DAI_USER', 'username');
define('DAI_PASS', 'password');

define('IMG_DIR', 'images/%celebname%/');

$DB = mysql_connect(DB_HOST, DB_USER, DB_PASS);
mysql_select_db(DB_NAME, $DB);
?>
