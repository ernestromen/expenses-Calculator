

<?php


// //Get Heroku ClearDB connection information
// $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
// $cleardb_server = $cleardb_url["host"];
// $cleardb_username = $cleardb_url["user"];
// $cleardb_password = $cleardb_url["pass"];
// $cleardb_db = substr($cleardb_url["path"],1);
// $active_group = 'default';
// $query_builder = TRUE;
// // Connect to DB
// $conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
    

// if (!$conn) {
//   die("Connection failed: " . mysqli_connect_error());
// }
// echo "Connected successfully";

error_reporting(E_ALL);
ini_set("display_errors", 1);
// $sql = "SELECT * FROM example";
// $result = mysqli_query($conn, $sql);



// while($row = mysqli_fetch_assoc($result)) {
//   echo "id: " . $row["id"]. "<br>". " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>" . $row ["email"];
// }



//heroku shit
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;
var_dump($cleardb_url);
// $conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);

$pdo = new PDO("mysql:host=$cleardb_server; dbname=$cleardb_db;", $cleardb_username, $cleardb_password);
$sql = "SELECT * FROM example";
$result = $pdo->query($sql);
var_dump($result);
while($row =$result->fetch_assoc()){
echo $row['firstname'] . $row['lastname'] . $row['email'];

}