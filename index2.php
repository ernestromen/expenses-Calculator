

<?php

class DB{
//pdo connection to be inherited
private $dns;
private $user;
private $password;
public $pdo;



  public function connect(){
    $this->dns = 'mysql:host=localhost;dbname=node4u';
    $this->user='root';
    $this->password='';
    $this->pdo = new PDO($this->dns,$this->user,$this->password); 
    return $this;
  }

  
  function __construct() {
    $this->connect();
}


 public function show(){
   $date =  date("Y-m-d H:i:s");
   var_dump($date);
$sql2 = "SELECT SUM(amount) FROM expenses;";

    $sql = "SELECT date FROM expenses WHERE date = ";
    echo '<pre>';   
var_dump($this->pdo->query($sql)->fetchall());
} 
  


}



$res = new DB();
$res->show();