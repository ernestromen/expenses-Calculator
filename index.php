

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

        // $a_date = "2009-11-23";
        //  $date =  date("Y-m-d");
        // var_dump(date("Y-m-t", strtotime($date)));
        // $datetime = new DateTime('tomorrow');

        // $date =  date("Y-m-d");
  //  var_dump( $date); 
$sql = "SELECT id,purchasetype,SUM(amount) as amount, date FROM expenses GROUP BY DATE_FORMAT(date,'%Y-%m-%d');";
$this->result =  $this->db->pdo->query($sql)->fetchall();
return ($this->result);




      }

}








$res = new DB();
$val = new Validation();
$crud = new CRUD($res);

$crud->connect()->validate();

$crud->show();





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
<?= $val->errors['input']; ?>
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
<script type="text/javascript">


</script>
</body>

</html>