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
// $sql = "SELECT name,password FROM users WHERE name = '$name' AND password='$password'";
$sql = "SELECT * FROM users";

///line of code make 500 error!! 
$this->result =  $this->db->pdo->query($sql);
var_dump($this->result-fecthall());




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
    <h2>SignUp</h2>
    <form method="post" action="">
<input style="margin-bottom:15px;" type="text" name="name" placeholder="name here"><br>
<input style="margin-bottom:15px;" type="password" name="password" placeholder="password here"><br>
<!-- <input type="hidden" name="csrf_token" value="<?= $token; ?>"> -->

<button name="submit" type="submit">click to submit!</button>
    
    </form>
</body>
</html>