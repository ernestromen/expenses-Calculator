

<?php

class DB{

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
}
 
class Validation extends DB{

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
            //practicing queries;!

            // $sql = "CREATE TABLE salary(
            //   id int NOT NULL AUTO_INCREMENT,
            //   amount int NOT NULL,
            //   created_at datetime,
            //    PRIMARY KEY (id)
            // )";
//             if($this->pdo->query($sql)){
// echo 'yes';

//             }else{
//               echo'no';
//             };

            
            }
            
            
            
            
            }


      }  
        


}
$res = new DB();
$val = new Validation();

$val->connect()->validate();







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
</html>