<?php
namespace src\model\orm;
/**
 *
 */
class Table
{
  private $_class;
  private $_update  = false;
  private $_initial = null;

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

  public function save()
  {
    return $this->persit();
  }

  public function persit()
  {
    $req = $this->query();
    return $req->_persist(get_object_vars ($this));
  }

  public function delete()
  {
    $req = $this->query();
    return $req->_delete(get_object_vars ($this));
  }

  public function setUpdate($value)
  {
    $this->_update = $value;
  }

  public function getUpdate()
  {
    return $this->_update;
  }

  public function setInitial($array)
  {
    $this->_initial = $array;
  }

  public function getInitial()
  {
    return $this->_initial;
  }

}
