<?php
include "common.php";

//sample 1
$map = new HashMap();
$map->put("test", 3);
echo $map;
$map->put("test",  300);
$map->put("test1", 4);
$map->put("test2", 5);
echo $map;
$map->remove("test1");
$map->remove("test42");
echo $map;
echo $map->get("test") . "<br />";

$map2 = new HashMap();
$map2->put("test4", 1234);
$map->putAll($map2);
echo $map;

echo "nyoe"

?>
