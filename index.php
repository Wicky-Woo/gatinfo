<?php
require('gat.class.php');

//to scan multiple maps, alter the array like so:
//$maps = ["ecl_tdun01", "ecl_tdun02", "ecl_tdun03", "ecl_tdun04"];
$maps = ["ecl_tdun04"];
foreach ($maps as $map)
{
    $gat = new GAT("maps/" . $map . ".gat");
    echo "<pre>";
    var_dump($gat->getTileData());
    echo "</pre>";
}