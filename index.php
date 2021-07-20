

<?php

require 'database.php';
use foobarwhatever\dingdong\DB;

session_start();
if(!(isset($_SESSION['userid']) && isset($_SESSION['useruid']))){
header("location: ../signin.php");
exit();


}




error_reporting(E_ALL);
ini_set("display_errors", 1);





$chosenDate = null;
 
class Validation extends DB{
     //validation 
     public function __construct($db){
      $this->db = $db;
      }
      
  public  $errors = [
        'input' => '',
        'salaryInput' =>'' 
        ];
    
      public function validate(){
    
//when expenses submitted 
        if(isset($_POST['submit'])){
          if(empty($_POST['select']) || empty($_POST['input']) ){

            $this->errors['input'] = '<br>'.'* the input is not valid';
            
            
            }else if(!(is_numeric($_POST['input']))){

              $this->errors['input'] = '<br>'.'* the input must be a number';
            }
            //if the input passes all the validation
            else{
             $result = $_POST['input'];
             $result2 = $_POST['select'];
             $where = 'amount';
             if($result && $result2){
              $this->insert($result,$result2,$where);

              
          

             }
            
            }
            
            
            
            
            }


//when salary is submitted 

if(isset($_POST['submitSalary'])){



if(empty($_POST['salary']) || empty($_POST['source']) ){
  $this->errors['salaryInput'] = '<br>'.'* the inputs must not be a empty';

}else if(!(is_numeric($_POST['salary']))){

  $this->errors['salaryInput'] = '<br>'.'* the salary input must be a number';

}else if(is_numeric($_POST['source'])){
  $this->errors['salaryInput'] = '<br>'.'* the source input must not be a number';

}else{


  $result = $_POST['source'];
  $result2 = $_POST['salary'];
  $where = 'submitSalary';
  $this->insert($result,$result2,$where);

}


}



      }  
        
    }




class CRUD extends Validation {
//does all the actions
private $db;
public $result;
public $result2;
public $result3;
public $result4;
public $result5;
public $total;
public $soFar;
public $subtract1;
public $subtract2;
public $substract3;
public $expected;
public function __construct($db){
$this->db = $db;
}

  public  function insert($res,$res2,$w){
// insert values from select and expenses input
if($w === 'amount'){
    $sql = "INSERT INTO expenses (purchasetype,amount,date) VALUES ('$res2','$res',NOW())";
$this->db->pdo->query($sql);
}else if($w === 'submitSalary'){
//insert value from salary and source
$sql = "INSERT INTO salary (source, amount) VALUES ('$res','$res2')";
$this->db->pdo->query($sql);
}




    
      }

      public function show(){
        
        if(isset($_POST['submitCurrent'])){
          if(empty($_POST['selectDate'])){
            var_dump('empty option in select tag');


          }else{

            if($_POST['selectDate'] == 'current'){

              $sql = "SELECT id,purchasetype,amount,date FROM expenses WHERE DATE_FORMAT(date,'%m') =MONTH(NOW());";

              $this->result = $this->db->pdo->query($sql)->fetchall();
              
              return ($this->result);

            }
            

$updatedDate = $_POST['selectDate'];
$sql = "SELECT id,purchasetype,amount,date FROM expenses WHERE DATE_FORMAT(date,'%Y-%m-%d') ='$updatedDate';";
    $this->result = $this->db->pdo->query($sql)->fetchall();

    return ($this->result);


          }
    
        }else{
          var_dump('nothing is passed');
        }




$sql = "SELECT id,purchasetype,amount,date FROM expenses WHERE DATE_FORMAT(date,'%m') =MONTH(NOW());";

$this->result = $this->db->pdo->query($sql)->fetchall();

return ($this->result);
      }

//show mounthly expenses

      public function show2(){

$sql = "SELECT SUM(amount) as amount,DATE_FORMAT(date,'%Y-%m') AS date FROM expenses GROUP by DATE_FORMAT(date,'%Y-%m');";


$this->result2 =($this->db->pdo->query($sql)->fetchall(PDO::FETCH_ASSOC));
return $this->result2;
      }

      //show yearly expenses
      public function show3(){
        
        $sql = "SELECT SUM(amount) as amount,DATE_FORMAT(date,'%Y') AS date FROM expenses GROUP by DATE_FORMAT(date,'%Y');";
        
        // echo '<pre>';
        
        $this->result4 =($this->db->pdo->query($sql)->fetchall(PDO::FETCH_ASSOC));
        return $this->result4;
              }


      public function selectTag(){
        $sql = "SELECT * FROM purchasetypes";
        $this->result3 =  $this->db->pdo->query($sql)->fetchall();
return ($this->result3);
      }

      //for showing specific date expense table
      public function selectTag2(){
        $sql = "SELECT DATE_FORMAT(date,'%Y-%m-%d') as date from expenses GROUP by DATE_FORMAT(date,'%Y-%m-%d');";
        $this->result5 =  $this->db->pdo->query($sql)->fetchall();
return ($this->result5);
      }

      public function total(){
$sql = "SELECT total from totalMoney";
$this->total =  $this->db->pdo->query($sql)->fetchall();
return $this->total;
      }
      public function soFar(){

        $sql = "SELECT SUM(amount) as amount FROM expenses WHERE DATE_FORMAT(date,'%m') =MONTH(NOW());";
$this->soFar = $this->db->pdo->query($sql)->fetchall();
return $this->soFar;
        
      }
      public function SubtractAmount(){
        $sql1 = "SELECT total from totalMoney";

        $sql2 = "SELECT SUM(amount) as amount FROM expenses WHERE DATE_FORMAT(date,'%m') =MONTH(NOW());";

        $this->subtract1 = $this->db->pdo->query($sql1)->fetchall();
        $this->subtract2 = $this->db->pdo->query($sql2)->fetchall();
        echo '<pre>';

      // return [$this->subtract1,$this->subtract2];
// var_dump($this->subtract1-$this->subtract2);
$this->expected= $this->subtract1[0]['total']-$this->subtract2[0]['amount'];
      }



}







$res = new DB($cleardb_server,$cleardb_db,$cleardb_username,$cleardb_password);
//$val causes 'too few arguments passed' Error 
$val = new Validation($res);
$crud = new CRUD($res);

$crud->validate();

$crud->show();
$crud->show2();
$crud->show3();
$crud->selectTag();
$crud->selectTag2();
$crud->total();
$crud->soFar();
$crud->SubtractAmount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
    .container2{
    padding:20px;
    display: grid;
    grid-template-columns: 20% 20%;
    justify-content: center;
    
    
    }

    </style>
</head>
<body>

<div id="root"></div>

/**adding expenses overall to every table */
<form id="myForm" method="post">
<div id="mobile1">
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



/**adding the amount of the salary */
<form action="" method="post">
<div id="mobile2">
<div  class="itemgrid">
<input class ="test" type="text" name="salary" placeholder="salary" type="text">
<input id="btnSubmit"  type="submit" name="submitSalary" placeholder="add" type="text">
</div>



<div style="text-align:center;" class="itemgrid">
<label for="select">select PurchaeType</label>

<input class ="test" type="text" name="source" placeholder="source" type="text">
</div>

</div>

<span style="color:red;">
<?= $crud->errors['salaryInput']; ?>
</span>

</form>

/**showing only data from a speicif date */
<form id="myForm" method="post">
<div id="mobile1">


 
<div class="itemgrid2">
<label for="select">select date</label>
<select id="select" value="something" name="selectDate">
<option  value="">date</option>
<option value="current">current</option>
<?php foreach($crud->result5 as $row):?>
<option  value="<?=$row['date'];?>"><?=$row['date'];?></option>
<?php endforeach;?>
</select> 
<input id="btnSubmit2"  type="submit" name="submitCurrent" placeholder="add" type="text">

<div>

</div>

</div>
<span style="color:red;">
<?= $crud->errors['input']; ?>
</span>


</div>
</form>
<?php foreach($crud->total as $row):?>
<h1 style="text-align:center">total amount of money:<?=$row['total']?></h1>
<?php endforeach;?>

<?php foreach($crud->soFar as $row):?>
<h1 style="text-align:center">monthly amount of money spent so far:<?=$row['amount']?></h1>
<?php endforeach;?>


<h1 style="text-align:center">Expected amount of money to be left after expenses:<?=$crud->expected?></h1>
<br>
<br>

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

<div class="container2 ">
  <div class="itemgrid">Amount</div>
  <div class="itemgrid">Date</div>
  <?php foreach($crud->result2 as $row):?>
  <div class="itemgrid">  <?=$row['amount'];?></div>
  <div class="itemgrid">  <?=$row['date'];?></div>
 
  <?php endforeach;?>

</div>



<h1 style="text-align:center">yearly expenses</h1>

<div class="container2 ">
  <div class="itemgrid">Amount</div>
  <div class="itemgrid">Date</div>
  <?php foreach($crud->result4 as $row):?>
  <div class="itemgrid">  <?=$row['amount'];?></div>
  <div class="itemgrid">  <?=$row['date'];?></div>
 
  <?php endforeach;?>

</div>
<script type="text/javascript">


</script>
</body>

</html>
