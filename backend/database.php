<?php

class DB
{
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