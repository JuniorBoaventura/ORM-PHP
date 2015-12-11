<?php
namespace src\model\orm;
/**
 *
 */
class User extends table
{
  public $id;
  public $name;
  public $firstname;
  public $age;
  public $status;

  protected $_table = "users";

  public function setId($id){
    $this->id = $id;
  }

  public function getId(){
    return $this->id;
  }

  public function setName($name){
    $this->name = $name;
  }

  public function getName(){
    return $this->name;
  }

  public function setFirstname($firstname){
    $this->firstname = $firstname;
  }

  public function getFirstname(){
    return $this->firstname;
  }

  public function setAge($age){
    $this->age = $age;
  }

  public function getAge(){
    return $this->age;
  }

  public function setStatus($status){
    $this->status = $status;
  }

  public function getStatus(){
    return $this->status;
  }
}
