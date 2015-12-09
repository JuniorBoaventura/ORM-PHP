<?php

namespace src\model\orm;

class OrmConfig{
  private static $connexion;

  public static function init($config)
  {
    self::$connexion = new \PDO('mysql:host='.$config['host'].';dbname='.$config['db'], $config['user'], $config['pwd'], [
      \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION, \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
    ]);
  }

  public static function getConnection()
  {
    return self::$connexion;
  }

}
