<?php

namespace foobarwhatever\dingdong;

use PDO;

// $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
// $cleardb_server = $cleardb_url["host"];
// $cleardb_username = $cleardb_url["user"];
// $cleardb_password = $cleardb_url["pass"];
// $cleardb_db = substr($cleardb_url["path"],1);
// $active_group = 'default';
// $query_builder = TRUE;

$cleardb_server = 'localhost';
$cleardb_username = 'root';
$cleardb_password = '';
$cleardb_db = 'db0123';

class DB
{
  //pdo connection to be inherited
  private $dns;
  private $user;
  private $password;
  protected $pdo;

  public function __construct($server, $db, $user, $pass)
  {
    $this->dns = "mysql:host=" . $server . "; dbname=" . $db . ";";
    $this->user = $user;
    $this->password = $pass;
    try {
      $this->pdo = new PDO($this->dns, $this->user, $this->password);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>" . "<br>";
      die();
    }
  }
}