<?php
// Get the autoload
require_once('vendor/autoload.php');

// Classes
use orm\OrmConfig;
use orm\Table;
use orm\Log;

use orm\Entity\Users;
use orm\Entity\Messages;

// Get the config database
$config_db = json_decode(file_get_contents('app/config/config_db.json'), true);
try{
  OrmConfig::init($config_db);
} catch(PDOException $e) {
  echo $e->getMessage();
}


// Create a user
// - - - - -- - - - - - - - - - - - -
$user1 = new Users();
$user1->setName('Vermesch');
$user1->SetFirstname('Killian');
$user1->SetAge(18);
$user1->SetStatus(0);
// var_dump($user1->save());


// Get all users
// - - - - -- - - - - - - - - - - - -
$user2 = new Users();
// $user2->delete();
$users = $user2->getAll();
// var_dump($user2->getAll($users ));

// Update user
// - - - - -- - - - - - - - - - - - -
$morgan = $users[2];
// $killian->SetName('Vermersch');
// $killian->SetAge(25);
// $killian->SetStatus(1);
// $killian->save();

// Is user exist ?
// - - - - -- - - - - - - - - - - - -
$user3 = new Users();
// var_dump($user3->exist(16));


// count all users
// - - - - -- - - - - - - - - - - - -
$user4 = new Users();
// var_dump($user4->count());

// count all users with condition
// - - - - -- - - - - - - - - - - - -
$user5 = new Users();
// var_dump($user5->count(['name'=>'Boaventura', 'status'=>1]));

// Join user to another table
// - - - - -- - - - - - - - - - - - -
$morgan->join('messages');
var_dump($morgan->_join);
// $messages = $morgan->join[0];
// $messages->setContent('tutu');
// $messages->save();


// Delete user
// - - - - -- - - - - - - - - - - - -
// $morgan->delete();
