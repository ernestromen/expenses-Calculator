<?php

require 'database.php';
use foobarwhatever\dingdong\DB;
use PDO;
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;



class Signin extends DB {
    public $errors =[
"confirmation" => ''
    ];
    public $result;
    public $db;

    public function __construct($db){
        // global $db;
        $this->db = $db;
        // var_dump($this->db);
        }

    public function process(){
if(isset($_POST['submit'])){
$name = $_POST['name'];
$password = $_POST['password'];
$sql = "SELECT name,password FROM users WHERE name = '$name' AND password='$password'";

///line of code make 500 error!! 
$this->result =  $this->db->pdo->query($sql);

if($this->result->rowCount() > 0){
// var_dump('number of rows is more than 0');
session_start();
$sql = "SELECT id,name FROM users WHERE password='$password'";
// $_SESSION['userid'] = $this->db->pdo->query($sql)->fetch()['id'];
var_dump($this->db->pdo->query($sql)->fetch());


}else{

    $this->errors['confirmation'] = 'wrong user or password';
};




}

    }

}

$res = new DB($cleardb_server,$cleardb_db,$cleardb_username,$cleardb_password);
$outcome = new Signin($res);
$outcome->process();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2 style="text-align:center;">SignUp</h2>
    <form style="text-align:center;" method="post" action="">
<input  style="margin-bottom:15px;width:30%;text-align:center;" type="text" name="name" placeholder="name here"><br>
<input style="margin-bottom:15px;width:30%;text-align:center;" type="password" name="password" placeholder="password here"><br>


<button style="width:30%;text-align:center;" name="submit" type="submit">click to submit!</button>
   <br><span style="color:red;"><?= $outcome->errors['confirmation'];?></span>
    </form>
</body>
</html>