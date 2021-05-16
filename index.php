

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
// var_dump($cleardb_url);
class DB  {
//heroku shit





//pdo connection to be inherited
private $dns;
private $user;
private $password;
protected $pdo;
// public $cleardb_server = $cleardb_url["host"];
// public $cleardb_username = $cleardb_url["user"];
// public $cleardb_password = $cleardb_url["pass"];
// public $cleardb_db = substr($cleardb_url["path"],1);


// function __construct($server,$db,$user,$pass) {
//   $this->connect($server,$db,$user,$pass);
// }

  public function __construct($server,$db,$user,$pass){
    // var_dump('here');
  
                // "mysql:host='.$host.'; dbname='.$dbname.';"
    $this->dns = "mysql:host=". $server."; dbname=" .$db.";";
    $this->user=$user;
    $this->password=$pass;
// echo '<pre>';
//     var_dump($this->dns,'dns');
//     var_dump($db,'database');
//     var_dump($this->user,'user');
//     var_dump($this->password,'pass');

    try {
    $this->pdo = new PDO($this->dns,$this->user,$this->password); 
    // var_dump( $this->pdo);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //   foreach($this->pdo->query("SELECT * FROM expenses") as $row) {
  //     echo htmlentities($row["firstname"]);
  // }
  // $this->pdo = null;
}catch(PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>" . "<br>";
  die();
}

    return $this;
  }

  


  
  


}



 
class Validation extends DB{
     //validation 
     public function __construct($db){
      // global $db;
      $this->db = $db;
      // var_dump($this->db);
      }
      
  public  $errors = [
        'input' => '',
        'salaryInput' =>'' 
        ];
    
      public function validate(){
    
//when expenses submitted 
        if(isset($_POST['submit'])){
          if(empty($_POST['select']) || empty($_POST['input']) ){
            // var_dump('in select');


         
          

            $this->errors['input'] = '<br>'.'* the input is not valid';
            
            
            }else if(!(is_numeric($_POST['input']))){

              $this->errors['input'] = '<br>'.'* the input must be a number';
            }
            //if the input passes all the validation
            else{
             $result = $_POST['input'];
             $result2 = $_POST['select'];
             $where = 'amount';
            //  var_dump($result2);
             if($result && $result2){
              $this->insert($result,$result2,$where);
              // exit;

              
          

             }
            
            }
            
            
            
            
            }


//when salary is submitted 

if(isset($_POST['submitSalary'])){



  var_dump($_POST['salary'],'salary');
  var_dump($_POST['sourse'],'source');
// else{
//       //if the input passes all the validation
//    $result = $_POST['salary'];
//    $result2= $_POST['source'];
//    $where = 'submitSalary';
//   //  var_dump('passed all the validtaion');
//   //  $result2 = $_POST['select'];
//   //  var_dump($result2);
//    if($result){
//     $this->insert($result,$result2,$where);
//     // exit;

    


//    }
  
// //   }
  

// }


          }

      }  
        





class CRUD extends Validation {
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

  public  function insert($res,$res2,$w){
// insert values from select and expenses input
if($w === 'amount'){

    $sql = "INSERT INTO expenses (purchasetype,amount,date) VALUES ('$res2','$res',NOW())";
$this->db->pdo->query($sql);
}else if($w === 'submitSalary'){
  echo '<pre>';
var_dump(is_numeric($_POST['source']),'check if source is numeric');//false
var_dump(empty($_POST['source']),'check if source is empty');//true
var_dump('in submitSalarys');

}




    
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
// var_dump($this->db);
// var_dump()
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







$res = new DB($cleardb_server,$cleardb_db,$cleardb_username,$cleardb_password);
//$val causes 'too few arguments passed' Error 
$val = new Validation($res);
$crud = new CRUD($res);

$crud->validate();

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

<form action="" method="post">
<div class="container2">
<div class="itemgrid">
<input class ="test" type="text" name="salary" placeholder="salary" type="text">
<input id="btnSubmit"  type="submit" name="submitSalary" placeholder="add" type="text">
</div>



<div class="itemgrid">
<label for="select">select PurchaeType</label>

<input class ="test" type="text" name="source" placeholder="source" type="text">
</div>

</div>

<span style="color:red;">
<?= $crud->errors['salaryInput']; ?>
</span>


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
