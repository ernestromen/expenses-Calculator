<?php
$servername = 'localhost';
$dBUsername = 'root';
$dBPassword = '';
$dBName = 'phpproject01';
//connect real database on heroku
// $conn = mysqli_connect($servername,$dBUsername,$dBPassword,$dBName);
// if(!$conn){

//     die("connection failed" . mysqli_connect_error());
// }



if(isset($_POST['submit'])){
    
$name = $_POST['name'];
$password = $_POST['password'];
var_dump($name);
var_dump($password);
$sql = "SELECT name,password FROM users WHERE name = '$name' AND password='$password'";

 $result = mysqli_query($conn, $sql);
 if (mysqli_num_rows($result) > 0) {
 
echo 'there such a user';
}else{


echo 'no such user';

}



}


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
<button name="submit" type="submit">click to submit!</button>
    
    </form>
</body>
</html>