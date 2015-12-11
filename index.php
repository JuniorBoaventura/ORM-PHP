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


// Create or update a user
$user1 = new User();
$user1->setId(6);
$user1->setName('Wenger');
$user1->SetFirstname('Killian');
$user1->SetAge(21);
$user1->SetStatus(1);
$user1->save();

// Get all users
$user2 = new User();
// var_dump($user2->getAll());
