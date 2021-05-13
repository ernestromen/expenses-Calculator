

<?php


//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;
// Connect to DB
$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
    

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

error_reporting(E_ALL);
ini_set("display_errors", 1);
$sql = "SELECT * FROM example";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
  echo "id: " . $row["id"]. "<br>". " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
}




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


  
  


}
 
class Validation extends DB{
     //validation 
     
  public  $errors = [
        'input' => ''
        
        ];
    
      public function validate(){
    
//when submitted
        if(isset($_POST['submit'])){
          if(empty($_POST['select']) || empty($_POST['input']) ){
            var_dump('in select');


         
          

            $this->errors['input'] = '<br>'.'* the input is not valid';
            
            
            }else if(!(is_numeric($_POST['input']))){

              $this->errors['input'] = '<br>'.'* the input must be a number';
            }
            //if the input passes all the validation
            else{
             $result = $_POST['input'];
             $result2 = $_POST['select'];
            //  var_dump($result2);
             if($result && $result2){
              $this->insert($result,$result2);
              // exit;

              
          

             }
            
            }
            
            
            
            
            }
          }

      }  
        





class CRUD extends Validation{
//does all the actions
private $db;
public $result;
public $result2;
public $result3;
public function __construct($db){
// global $db;
$this->db = $db;
// var_dump($this->db);
}

  public  function insert($res,$res2){
// insert values
    $sql = "INSERT INTO expenses (purchasetype,amount,date) VALUES ('$res2','$res',NOW())";
     ($this->db->pdo->query($sql));



    
      }

      public function show(){

//          $date =  date("Y-m-d");
//         $date = new DateTime($date);
// if($date->format('d') == '1'){
//   // var_dump($date->format('m'));

//   $date =  date('Y-m-d', strtotime(date('Y-m')." -1 month"));
//   $date = new DateTime($date);
//   var_dump($date->format('m'));
// };
    
$sql = "SELECT id,purchasetype,amount,date FROM expenses WHERE DATE_FORMAT(date,'%m') =MONTH(date);";
$this->result = $this->db->pdo->query($sql)->fetchall();
// echo '<pre>';
return ($this->result);
      }



      public function show2(){
$sql = "

SELECT id,purchasetype,SUM(amount) as amount,(SELECT  DATE_FORMAT(date,'%Y-%m') AS date FROM expenses GROUP BY DATE_FORMAT(date,'%Y-%m')) as date FROM expenses GROUP BY purchasetype";
$this->result2 =  $this->db->pdo->query($sql)->fetchall();
return ($this->result2);

      }
      public function selectTag(){
        $sql = "SELECT * FROM purchasetypes";
        $this->result3 =  $this->db->pdo->query($sql)->fetchall();
return ($this->result3);
      }

}








$res = new DB();
$val = new Validation();
$crud = new CRUD($res);

$crud->connect()->validate();

$crud->show();
$crud->show2();
$crud->selectTag();





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>

<div id="root"></div>
<form id="myForm" method="post" style="text-align: center;">
<div class="container2">
<div class="itemgrid">
<input class ="test" type="text" name="input" placeholder="amount" type="text">
<input id="btnSubmit"  type="submit" name="submit" placeholder="add" type="text">
</div>


<div class="itemgrid2">
<label for="select">select PurchaeType</label>
<select id="select" value="something" name="select">
<option  value="">option</option>

<?php foreach($crud->result3 as $row):?>
<option  value="<?=$row['purchasetype'];?>"><?=$row['purchasetype'];?></option>
<?php endforeach;?>
</select> 
</div>
<span style="color:red;">
<?= $crud->errors['input']; ?>
</span>
</div>

</form>
<h1 style="text-align:center">daily expenses</h1>

<div class="container ">
<div class="itemgrid">ID</div>
  <div class="itemgrid">type</div>
  <div class="itemgrid">Amount</div>
  <div class="itemgrid">Date</div>
  <?php foreach($crud->result as $row):?>
  <div class="itemgrid">  <?=$row['id'];?></div>
  <div class="itemgrid">  <?=$row['purchasetype'];?></div>
  <div class="itemgrid">  <?=$row['amount'];?></div>
  <div id="date" class="itemgrid">  <?=$row['date'];?></div>
  <?php endforeach;?>

</div>



<h1 style="text-align:center">monthly expenses</h1>

<div class="container ">
<div class="itemgrid">ID</div>
  <div class="itemgrid">type</div>
  <div class="itemgrid">Amount</div>
  <div class="itemgrid">Date</div>
  <?php foreach($crud->result2 as $row):?>
  <div class="itemgrid">  <?=$row['id'];?></div>
  <div class="itemgrid">  <?=$row['purchasetype'];?></div>
  <div class="itemgrid">  <?=$row['amount'];?></div>
  <div class="itemgrid">  <?=$row['date'];?></div>
 
  <?php endforeach;?>

</div>
<script type="text/javascript">


</script>
</body>

</html>