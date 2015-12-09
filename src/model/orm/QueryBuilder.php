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
  public $query;


  function __construct()
  {
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
    if(!empty($condition))
      array_push($this->where, $condition);
      return $this;
  }

  public function _buildWhere()
  {
    $array =$this->where;
    $rowNb = count($this->where);
    $condition =' WHERE ';

    for ($i=0; $i < $rowNb ; $i++) {

      $key   = key($array[$i]);
      $value = $array[$i][$key];

      if(is_string($value))
        $condition .= $key.' = "'.$value.'"';
      else
        $condition .= $key.' = '.$value;

      if($i < $rowNb-1)
        $condition .= ' AND ';

    }

    return $condition;
  }

  public function _execute()
  {
    $where = (!empty($this->where)) ? $this->_buildWhere() : '';


    $sql = 'SELECT '.$this->select.' FROM '.$this->from.$where;

    $this->query = $this->connexion->prepare($sql);
    $this->query->execute();
    return $this;
  }

  public function _fetchAll()
  {
    return $this->query->fetchAll(\PDO::FETCH_ASSOC);
  }

}
