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
      $req = new QueryBuilder();
      $res = $req->_select()
        ->_from($this->table)
        ->_where(['firstname'=>'junior'])
        ->_where(['age'=>21])
        ->_execute()
        ->_fetchAll();
      // var_dump($req->query);

      var_dump($res);
  }

}
