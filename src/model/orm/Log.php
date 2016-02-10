<?php

namespace orm;

class Log{


  public static function access($sql)
  {
    $access = "--------------------\n";
    $access .= date('j/m/Y H:i:s') . ': \'' . $sql . "'\n";
    file_put_contents(__DIR__ .'/../../../logs/access.log', $access, FILE_APPEND);
  }

  public static function error($errors, $sql = '')
  {
    $error = "--------------------\n";
    $error .= date('j/m/Y H:i:s') . ': ' . $errors[2] . "\n";
    if (!empty($sql))
      $error .= '-> Query tried: \'' . $sql . "'\n";
    file_put_contents(__DIR__ . '/../../../logs/error.log', $error, FILE_APPEND);
  }

}
