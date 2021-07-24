

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
        'salaryInput' =>'',
        'incomeInput'=>''

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

////validation for additinal income

if(isset($_POST['submitIncome'])){



  if(empty($_POST['income']) || empty($_POST['source2']) ){
    $this->errors['incomeInput'] = '<br>'.'* the inputs must not be a empty';
  
  }else if(!(is_numeric($_POST['income']))){
  
    $this->errors['incomeInput'] = '<br>'.'* the salary input must be a number';
  
  }else if(is_numeric($_POST['source2'])){
    $this->errors['incomeInput'] = '<br>'.'* the source input must not be a number';
  
  }else{
  
  
    $result = $_POST['source2'];
    $result2 = $_POST['income'];
    $where = 'submitIncome';
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
public $subtract3;
public $expected;
public $salary;
public $included;
public $otherIncome;
public $lastMonthIncome;
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
// $sql = "INSERT INTO salary (source, amount) VALUES ('$res','$res2')";
$sql = "UPDATE salary SET source ='$res',amount ='$res2';";
$this->db->pdo->query($sql);

// UPDATE totalmoney SET total = '$var';

$this->db->pdo->query($sql);
}else if($w === 'submitIncome'){
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
        //total monney
        $sql1 = "SELECT total from totalMoney";
//total speding in this month
        $sql2 = "SELECT SUM(amount) as amount FROM expenses WHERE DATE_FORMAT(date,'%m') =MONTH(NOW());";
        //select salary
        $sql3 = "SELECT amount FROM salary WHERE id =5 ";
        // $sql4 = "SELECT SUM(amount) as amount,source FROM salary WHERE id != 5 AND DATE_FORMAT(created_at,'%m') =MONTH(NOW())";

        $this->subtract1 = $this->db->pdo->query($sql1)->fetchall();
        $this->subtract2 = $this->db->pdo->query($sql2)->fetchall();
        $this->subtract3 = $this->db->pdo->query($sql3)->fetchall();
        // $this->otherIncome = $this->db->pdo->query($sql4)->fetchall();


        echo '<pre>';
//when you get salary
      if(date('d') == 10){
        if($this->db->pdo->query("SELECT amount FROM `salary` WHERE id != 5 AND DATE_FORMAT(created_at,'%m') =MONTH(NOW() - INTERVAL 1 MONTH);")->rowcount() > 0){
          $var;
          
          $sql = "SELECT SUM(amount) as amount FROM salary AND DATE_FORMAT(created_at,'%m') =MONTH(NOW() - INTERVAL 1 MONTH);";
          $var = $this->db->pdo->query($sql)->fetchall();
         $var+= $this->subtract1;
          $this->db->pdo->query("UPDATE totalmoney SET total = '$var';");
          // $this->db->pdo->query("DELETE FROM salary  WHERE id != 5");
        }else{
          $var = $this->subtract1+$this->subtract3;
          $this->db->pdo->query("UPDATE totalmoney SET total = '$var';");

        }
//insert amount of money in the start of the month with paycheck;
       

        ///when you pay for expenses
      }else if(date('d') == 1){
        $var2 =$this->db->pdo->query("SELECT SUM(amount) as amount FROM expenses WHERE DATE_FORMAT(date,'%m') =MONTH(NOW() - INTERVAL 1 MONTH);");
        //substracts the spendings from totalamount of money in the start of the month
        $var =$this->subtract1- $var2;
        $sql = "UPDATE totalmoney SET total = '$var';";
        $this->db->pdo->query($sql);
      }

$this->expected= $this->subtract1[0]['total']-$this->subtract2[0]['amount'];
      }

      public function showSalary(){

$sql = "SELECT amount FROM salary WHERE id = 5";
$sql2 = "SELECT SUM(amount) as amount,source FROM salary WHERE id != 5 AND DATE_FORMAT(created_at,'%m') =MONTH(NOW())";
//   ("SELECT SUM(amount) as amount FROM expenses WHERE DATE_FORMAT(date,'%m') =MONTH(NOW() - INTERVAL 1 MONTH);");

$this->salary = $this->db->pdo->query($sql)->fetchall();
$this->otherIncome = $this->db->pdo->query($sql2)->fetchall();
if(date('d') > 0 && date('d') < 10){
  $sql3 = "SELECT SUM(amount) as amount,source FROM salary WHERE id != 5 AND DATE_FORMAT(created_at,'%m') =MONTH(NOW() - INTERVAL 1 MONTH);";
  $this->lastMonthIncome = $this->db->pdo->query($sql3)->fetchall();

}
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
$crud->showSalary();

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



/**updating the amount of the salary */
<form action="" method="post">
<div id="mobile2">
<div  class="itemgrid">
<input class ="test" type="text" name="salary" placeholder="salary" type="text">
<input id="btnSubmit"  type="submit" name="submitSalary" placeholder="add" type="text">
</div>



<div style="text-align:center;" class="itemgrid">
<label for="select">type source of income</label>

<input class ="test" type="text" name="source" placeholder="source" type="text">update</input>
</div>

</div>

<span style="color:red;">
<?= $crud->errors['salaryInput']; ?>
</span>

</form>


/**inserting another income additionally to the constant salary */
<form action="" method="post">
<div id="mobile2">
<div  class="itemgrid">
<input class ="test" type="text" name="income" placeholder="salary" type="text">
<input id="btnSubmit"  type="submit" name="submitIncome" placeholder="add" type="text">
</div>



<div style="text-align:center;" class="itemgrid">
<label for="select">type source of income</label>

<input class ="test" type="text" name="source2" placeholder="source" type="text">
</div>

</div>

<span style="color:red;">
<?= $crud->errors['incomeInput']; ?>
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



<?php foreach($crud->salary as $row):?>
<h1 style="text-align:center">monthly salary from this month: <?=$row['amount']?></h1>
<?php endforeach;?>


<?php foreach($crud->otherIncome as $row):?>
<h1 style="text-align:center">income other than monthly salary current month: <?=$row['amount']?></h1>
<?php endforeach;?>

<?php if(isset($crud->lastMonthIncome)):?>
<?php foreach($crud->lastMonthIncome as $row):?>
<h1 style="text-align:center">income other than monthly salary previous month: <?=$row['amount']?></h1>
<?php endforeach;?>
<?php else:?>
  <h1 style="text-align:center">income other than monthly salary previous month: 0</h1>
<?php endif;?>


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
