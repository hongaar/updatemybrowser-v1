#!/usr/bin/php
<?php
include ('private/lib/Ajde/Http/Curl.php');
$e = Ajde_Http_Curl::get('http://updatemybrowser.org/admin/cron.html');
echo $e;
?>