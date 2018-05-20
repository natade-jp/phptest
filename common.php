<?php

ini_set("display_errors", On);
error_reporting(E_ALL);

require_once("./lib/AutoClassLoader.php");

$CommonClassLoader = new AutoClassLoader();
$CommonClassLoader->add("./lib/mylib/");

?>