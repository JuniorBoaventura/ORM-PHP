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
    if(!empty($condition)){
      foreach ($condition as $key => $value) {
        $this->where[$key] = $value;
      }
    }
    return $this;
  }

  public function _buildWhere()
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

    var_dump($condition);

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
    return $this->query->fetchAll(\PDO::FETCH_ASSOC);
  }

}
