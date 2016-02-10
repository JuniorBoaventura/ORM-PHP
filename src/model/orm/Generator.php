<?php
namespace orm;
/**
 *
 */
class Generator
{
  private $table;
  private $connexion;

  public $space = 2;

  function __construct($table)
  {
    $this->table = $table;
    $this->connexion = OrmConfig::init($this->getConfig());
    $this->connexion = OrmConfig::getConnection();

  }

  public function generateEntity()
  {
    $columns = $this->getColumns($this->table);
    if(empty($columns))
      return 'This table does not exist';

    $code = "<?php\n\n";
    $code .= "namespace orm\Entity;\n\n";
    $code .= "use orm\Table;\n\n";
    $code .= "class ".ucfirst($this->table)." extends table \n{\n";

    foreach ($columns as $column){
      $code .= $this->tabs($this->space) . 'protected $'.$column.";\n";
    }

    $code .= "\n";
    $code .= $this->tabs($this->space)."protected \$_table = '".$this->table."';";
    $code .= "\n\n";

    foreach ($columns as $column){
      $code .= $this->tabs($this->space) . 'public function get'.ucfirst($column)."()\n";
      $code .= $this->tabs($this->space) . "{\n";
      $code .= $this->tabs($this->space+2) . 'return $this->'.$column.";\n";
      $code .= $this->tabs($this->space) . "}\n\n";
      $code .= $this->tabs($this->space) . 'public function set'.ucfirst($column).'($'.$column.")\n";
      $code .= $this->tabs($this->space) . "{\n";
      $code .= $this->tabs($this->space+2) . '$this->'.$column.' = $'.$column.";\n";
      $code .= $this->tabs($this->space) . "}\n\n";
    }
    $code .= "}\n";

    file_put_contents(__DIR__.'/Entity/'.ucfirst($this->table).".php", $code);
    return 'Entity '.ucfirst($this->table).' successfully created';
  }

  private function getColumns($table)
  {
    $req = $this->connexion->prepare('SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`= :table');
    $req->execute(['table'=>$table]);
    $res = $req->fetchAll(\PDO::FETCH_ASSOC);

    $columns = [];

    foreach ($res as $value) {
      foreach ($value as $key => $col) {
        array_push($columns, $col);
      }
    }
    return $columns;
  }

  public function tabs($space)
  {
    $ret = '';
    for ($i=0; $i < $space ; $i++) {
      $ret .= ' ';
    }
    return $ret;
  }

  public function setSpace($num)
  {
    $this->space = (int)$num;
  }

  private function getConfig()
  {
    return json_decode(file_get_contents(__DIR__ .'/../../../app/config/config_db.json'), true);
  }
}
