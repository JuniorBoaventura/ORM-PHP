<?php
namespace src\model\orm;
/**
 *
 */
class Table
{
  private $class;

  function __construct()
  {
    $this->class = get_class($this);
  }

  public function getAll()
  {
      $req= $this->query();
      $res = $req
      ->_select()
      ->_execute()
      ->_fetchAll();
      // var_dump($req->query);

      var_dump($res);
  }

  public function query(){
    $req = new QueryBuilder($this->class);
    return $req->_from($this->table);
  }

}
