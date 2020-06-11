#!/usr/bin/php
<?php
include ('private/lib/Ajde/Http/Curl.php');
$e = Ajde_Http_Curl::get('https://updatemybrowser.org/admin/cron.html');
echo $e;
?>
