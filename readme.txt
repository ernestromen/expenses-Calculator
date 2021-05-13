


1.validation, needed to be fixed
2.a function that caculates the sum amount column when the month is finished
3.delete option to the expense table.
3.display/edit/delete salary
4.create a select tag and make it send a query that saves the purchase option which was selected.


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
while($row = $result->fetch()){
echo $row['firstname'] . $row['lastname'] . $row['email'];

}