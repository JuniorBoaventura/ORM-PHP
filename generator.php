<?php
require_once('autoload.php');

use orm\OrmConfig;
use orm\Generator;

$tableName = $argv[1];
$tableName = rtrim($tableName, "\n");


$generator = new Generator($tableName);
$res = $generator->generateEntity();

echo $res;
