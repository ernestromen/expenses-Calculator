

<?php

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
          if(empty($_POST['input']) ){
          

            $this->errors['input'] = '<br>'.'* the input is not valid';
            
            
            }else if(!(is_numeric($_POST['input']))){

              $this->errors['input'] = '<br>'.'* the input must be a number';
            }
            //if the input passes all the validation
            else{
   
             $result = $_POST['input'];
            //  var_dump($result);
             if($result){
              $this->insert($result);
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
public function __construct($db){
// global $db;
$this->db = $db;
// var_dump($this->db);
}

  public  function insert($res){
// insert values
    $sql = "INSERT INTO expenses (amount,date) VALUES ($res,NOW())";
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
    
$sql = "SELECT id,purchasetype,SUM(amount) as amount, date FROM expenses GROUP BY DATE_FORMAT(date,'%Y-%m-%d');";
$this->result =  $this->db->pdo->query($sql)->fetchall();
return ($this->result);
      }



      public function show2(){
$sql = "SELECT id,purchasetype,SUM(amount) as amount, DATE_FORMAT(date,'%Y-%m') AS date FROM expenses GROUP BY DATE_FORMAT(date,'%Y-%m');";
$this->result2 =  $this->db->pdo->query($sql)->fetchall();
return ($this->result2);

      }

}








$res = new DB();
$val = new Validation();
$crud = new CRUD($res);

$crud->connect()->validate();

$crud->show();
$crud->show2();





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
<input class ="test" type="text" name="input" placeholder="amount" type="text">
<input id="btnSubmit"  type="submit" name="submit" placeholder="add" type="text">
<span style="color:red;">
<?= $crud->errors['input']; ?>
</span>
<br>  
<!-- 
<label for="select">select PurchaeType</label>
<select id="select" value="something" name="select">


<option value="credit_card">Credit card</option>
<option value="food">Food</option>
<option value="going_out">Going out</option>
<option value="gas">gass</option>
</select> -->


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