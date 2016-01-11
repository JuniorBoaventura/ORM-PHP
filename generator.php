<?php
require_once('autoload.php');

use src\model\orm\OrmConfig;
use src\model\orm\Generator;

$tableName = $argv[1];
$tableName = rtrim($tableName, "\n");


$generator = new Generator($tableName);
$res = $generator->generateEntity();

echo $res;
