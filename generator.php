<?php
require_once('autoload.php');

use src\model\orm\OrmConfig;
use src\model\orm\Generator;

$table = $argv[1];
$table = rtrim($table, "\n");


$generator = new Generator($table);
$res = $generator->generateEntity();

echo $res;
