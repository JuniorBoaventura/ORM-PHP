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
  private $count = false;
  private $join = false;

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

  public function getWhere($row)
  {
    if(isset($row['id']) && $row['id'] !== null){
      $where = ' WHERE id = :id';
      $row   = ['id'=>$row['id']];
    }else{
      unset($row['id']);
      $this->where = $row;
      $where       = $this->_buildWhere();

      if(isset($row['_update'])){
        unset($row['_update']);
        foreach ($row as $key => $value) {
          $row['old'.ucfirst($key)] = $row[$key];
          unset($row[$key]);
        }
      }

    }

    return ['sql'=>$where, 'data'=>$row];
  }

  public function _buildWhere($array = null)
  {
    $array     = $this->where;

    if(isset($array['_update'])){
      $update = $array['_update'];
      unset($array['_update']);
    }

    $condition = ' WHERE ';

    foreach ($array as $key => $value) {
      // if update is true add the label old
      if(isset($update))
        $condition .= $key.' = :old'.ucfirst($key);
      else
        $condition .= $key.' = :'.$key;

      $condition .= ' AND ';
    }

    $condition = rtrim($condition, ' AND ');
    return $condition;
  }

  public function _execute()
  {

    $where = !empty($this->where) ? $this->getWhere($this->where) : ['sql'=>'', 'data'=>[]];

    if($this->count)
      $this->select = 'COUNT(*) as nb';

    $sql = 'SELECT '.$this->select.' FROM '.$this->from.$where['sql'];


    $this->query = $this->connexion->prepare($sql);
    $res = $this->query->execute($where['data']);



    if (!$res)
      Log::error($this->query->errorInfo(), $this->query->queryString);
    Log::access($this->query->queryString);

    return $this;
  }

  public function _fetchAll($hydration = true)
  {
    $res = $this->query->fetchAll(\PDO::FETCH_ASSOC);

    if($hydration)
      return $this->_hydration($res);
    return $res;
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
      $row->setInitial($object);
      array_push($array, $row);
    }
    return $array;
  }

  public function _persist($array){

    $initial   = $array['_initial'];

    $row       = $this->formatArray($array);
    unset($array['_join']);


    $fieldname = '';
    $data      = '';

    if($initial === null){
      // if $initial is set at FALSE => INSERT
      foreach ($row as $key => $value) {
        $fieldname .= $key.', ';
        $data      .= ':'.$key.', ';
      }
      $fieldname = rtrim($fieldname, ', ');
      $data      = rtrim($data, ', ');
      $sql       = 'INSERT INTO '.$this->from.' ('.$fieldname.') VALUE ('.$data.')';
    }else{
      // if $initial is set at TRUE => UPDATE
      foreach ($row as $key => $value) {
        if($value !== null){
          $fieldname .= $key.', ';
          $data      .= $key.'=:'.$key.', ';
        }
      }

      $where     = $this->getWhere($initial);

      $fieldname = rtrim($fieldname, ', ');
      $data      = rtrim($data, ', ');
      $sql       = 'UPDATE '.$this->from.' SET '.$data.$where['sql'];

    }

  if($row['id'] !== null)
    $row = array_merge($row, $where['data']);

  $this->query = $this->connexion->prepare($sql);
  ### Modification !! Return the last insert id or the updated row ###
  return $this->query->execute($row);
  }

  public function _delete($array)
  {
    $row         = $this->formatArray($array);
    $where       = $this->getWhere($row);

    $sql         = "DELETE FROM ".$this->from.$where['sql'];
    ### Modification !! Return the rows deleted ###
    $this->query = $this->connexion->prepare($sql);
    return $this->query->execute($where['data']);
  }

  public function _count()
  {
    $this->count = true;
    return $this;
  }

  public function _join($class)
  {
      $this->join = true;
      $this->class = $class;
      return $this;
  }

  public function formatArray($array){
    return $this->preg_grep_keys('/^((?!_).)*$/',$array);
  }

  public function preg_grep_keys($pattern, $input, $flags = 0) {
    return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
  }

}
