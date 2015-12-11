<?php
namespace src\model\orm;
/**
 *
 */
class QueryBuilder
{
  private $connexion;
  private $select;
  private $from;
  private $where = [];
  private $query;
  private $class;
  private $action = ['INSERT','UPDATE'];



  function __construct($class)
  {
    $this->class = $class;
    $this->connexion = OrmConfig::getConnection();
  }

  public function _select($params = '*')
  {
    $this->select = $params;
    return $this;
  }

  public function _from($table)
  {
    $this->from = $table;
    return $this;
  }

  public function _where($condition)
  {
    if(!empty($condition)){
      foreach ($condition as $key => $value) {
        $this->where[$key] = $value;
      }
    }
    return $this;
  }

  public function _buildWhere($array = null)
  {
    $array     = $this->where;

    $rowNb     = count($this->where);
    $condition = ' WHERE ';
    $i         = 1;

    foreach ($array as $key => $value) {

      $condition .= $key.' = :'.$key;

      if($i < $rowNb){
        $condition .= ' AND ';
        $i++;
      }

    }
    return $condition;
  }

  public function _execute()
  {
    $where = (!empty($this->where)) ? $this->_buildWhere() : '';


    $sql = 'SELECT '.$this->select.' FROM '.$this->from.$where;

    $this->query = $this->connexion->prepare($sql);
    $this->query->execute($this->where);
    return $this;
  }

  public function _fetchAll()
  {
    $res = $this->query->fetchAll(\PDO::FETCH_ASSOC);
    return $this->_hydration($res);
  }

  public function _hydration($res){
    $array = [];
    foreach ($res as $object) {
      $row = new $this->class();
      foreach ($object as $key => $value) {
        $method = 'set'.ucfirst($key);
        $row->$method($value);
      }
      $row->setUpdate(true);
      array_push($array, $row);
    }
    return $array;
  }

  public function _persist($array){
    $update    = $array['_update'];
    $row       = $this->preg_grep_keys('/^((?!_).)*$/',$array);
    $fieldname = '';
    $data      = '';

    if($update === false){
      // if $update is set at FALSE => INSERT
      foreach ($row as $key => $value) {
        $fieldname .= $key.', ';
        $data      .= ':'.$key.', ';
      }
      $fieldname = rtrim($fieldname, ', ');
      $data      = rtrim($data, ', ');
      $sql       = 'INSERT INTO '.$this->from.' ('.$fieldname.') VALUE ('.$data.')';
    }else{
      // if $update is set at TRUE => UPDATE
      foreach ($row as $key => $value) {
        $fieldname .= $key.', ';
        $data      .= $key.'=:'.$key.', ';
      }

      if($row['id']){
        $where = ' WHERE id = :id';
      }else{
        $this->where = $row;
        $where       = $this->_buildWhere();
      }

      $fieldname = rtrim($fieldname, ', ');
      $data      = rtrim($data, ', ');
      $sql       = 'UPDATE '.$this->from.' SET '.$data.$where;

    }

    $this->query = $this->connexion->prepare($sql);
    $res         = $this->query->execute($row);
  }

  public function preg_grep_keys($pattern, $input, $flags = 0) {
    return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
  }

}
