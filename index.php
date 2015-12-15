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

$user = new User();

$nb = $user->count();
var_dump($nb);
// $all = $user->getAll();
// var_dump($all);

// $morgan = $all[0];
//
// $morgan->setName('Olsen');
// $morgan->save();
//
// var_dump($morgan->getName());
//
// $raph->setId(null);
// var_dump($raph);
// $raph->delete();
//
// $junior = $all[0];
//
// var_dump($junior);

// $junior->setAge(22);
// // $junior->setName('Boaventuraa');
// $junior->setId(null);
// $junior->delete();

// Create a User
// $user1 = new User();
// $user1->setId(11);
// $user1->setName('Wenger');
// $user1->SetFirstname('Killian');
// $user1->SetAge(22);
// $user1->SetStatus(1);
// var_dump($user1->save());

// $user2 = new User();
// $user2->setId(6);
// $user2->setName('Enrico');
// $user2->SetFirstname('Robin');
// $user2->SetAge(22);
// $user2->SetStatus(2);
// $user2->setUpdate(true);

// Get all users
// $user3 = new User();

// Delete a User
// $user1->delete();
