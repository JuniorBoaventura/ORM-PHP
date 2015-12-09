<?php
// Get the autoload
require_once('autoload.php');

// Classes
use src\model\orm\OrmConfig;
use src\model\orm\Table;
use src\model\orm\User;

// Get the config database
$config_db = json_decode(file_get_contents('app/config/config_db.json'), true);
try{
  OrmConfig::init($config_db);
} catch(PDOException $e) {
  echo $e->getMessage();
}

$users = new User();
var_dump($users->getAll());
