

<?php

class DB{

private $dns;
private $user;
private $password;



  public function connect(){

    $this->dns = 'mysql:host=localhost;dbname=test';
    $this->user='root';
    $this->password='';
    $this->db = new PDO($this->dns,$this->user,$this->password); 
  }
}
 
class Validation{

  public  $errors = [
        'input' => ''
        
        ];
        
      public function validate(){


        if(isset($_POST['submit'])){
// var_dump(empty($_POST['input']));

          if(empty($_POST['input']) ){
            $this->errors['input'] = '<br>'.'* the input is not valid';
            
            
            }else if(!(is_numeric($_POST['input']))){

              $this->errors['input'] = '<br>'.'* the input must be a number';
            }
            else{
            
             $result = $_POST['input'];
            
            $sql = "INSERT INTO dailyExpenses VALUES ($result)";
            
            
            }
            
            
            
            
            }


      }  
        


}
$val = new Validation();
$val->validate();
$res = new DB();
$res->connect();






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
<form method="post" style="text-align: center;">
<input class ="test" type="text" name="input" placeholder="amount" type="text">
<input   type="submit" name="submit" placeholder="amount" type="text">
<span style="color:red;">
<?= $val->errors['input']; ?>
</span>


</form>
</body>
<!-- <script src="script.js"></script> -->
</html>