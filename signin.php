<?php

require 'database.php';
use foobarwhatever\dingdong\DB;
// require 'helpers.php';
// var_dump($res);

// $servername = 'localhost';
// $dBUsername = 'root';
// $dBPassword = '';
// $dBName = 'phpproject01';
//connect real database on heroku
// $conn = mysqli_connect($servername,$dBUsername,$dBPassword,$dBName);
// if(!$conn){

//     die("connection failed" . mysqli_connect_error());
// }

class Signin extends DB {
    public $result;
    private $db;

    public function __construct($db){
        // global $db;
        $this->db = $db;
        // var_dump($this->db);
        }

    public function process(){
if(isset($_POST['submit'])){
//    $rtoken = $_POST['csrf_token'];
$name = $_POST['name'];
$password = $_POST['password'];
$sql = "SELECT name,password FROM users WHERE name = '$name' AND password='$password'";
// $this->result = $this->db->pdo->query($sql);
$this->result = 'hello';
// var_dump($name);
// var_dump($password);
// var_dump('im here 01');


// if($result){
//     echo 'result exists';
// }else{
//     echo 'it doesnt';
// }
// var_dump($result);
//  // $this->db->pdo->query($sql);

//  if (mysqli_num_rows($result) > 0) {



//     header('location:index.php');
// // echo 'there such a user';
// }else{


// echo 'no such user';

// }



}
// else{

// $token = csrf();

// }
// $token = csrf();
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
    <h2>SignUp</h2>
    <form method="post" action="">
<input style="margin-bottom:15px;" type="text" name="name" placeholder="name here"><br>
<input style="margin-bottom:15px;" type="password" name="password" placeholder="password here"><br>
<!-- <input type="hidden" name="csrf_token" value="<?= $token; ?>"> -->

<button name="submit" type="submit">click to submit!</button>
    
    </form>
</body>
</html>