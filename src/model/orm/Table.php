<?php
namespace src\model\orm;
/**
 *
 */
class Table
{
  private $_class;
  private $_update = true;

  function __construct()
  {
    $this->_class = get_class($this);
  }

  public function getAll()
  {
      $req= $this->query();
      return $req
      ->_select()
      ->_execute()
      ->_fetchAll();
  }

  public function query()
  {
    $req = new QueryBuilder($this->_class);
    return $req->_from($this->_table);
  }

  public function setUpdate($value)
  {
    $this->update = $value;
  }

  public function getUpdate()
  {
    return $this->id;
  }

  public function save()
  {
    return $this->persit();
  }

  public function persit()
  {
    $req = $this->query();
    return $req->_persist(get_object_vars ($this));
  }

}
