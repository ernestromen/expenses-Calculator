

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



  public function insert(){

    $sql = "INSERT INTO (amount) VALUES($result)";
$this->pdo->query($sql);

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
            // $tmp = 'hello';
            // $tmp = json_encode($tmp);

            // echo json_encode($_POST['input']);
            // echo json_encode($_GET['input']);
             $result = $_POST['input'];
             $a = 'from php with love';
            //  var_dump($result);
             if($result){
              $a = 'from php with love';

echo '<div id="mytarget" style="display:none">';
echo $a;
echo '</div>';


echo '<script>

console.log(document.querySelector("#mytarget").textContent);
</script>';
// $result = json_encode($result);
// echo $result;
//               echo '<script  type="text/javascript"> 

//             var ajax = new XMLHttpRequest();
// ajax.onreadystatechange = function(){

//   httpRequest.open("POST", "http://localhost/codwares/expensesCalculator/", true);
//   httpRequest.send();


// }
//                                         </script>';


              // // var res = confirm("Hello! I am an alert box!!");
              // // // if not true
              // // if(!(res)){
                
              // //   alert("suit yourself!!");
                
              // // }else{
              // //   console.log("on else");
          
              // // }

             }




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




// class Queries{
// public function insert($pdo,$result){

// $sql = "INSERT INTO (amount) VALUES($result)";
// $

// }
// }
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
<form id="myForm" method="post" style="text-align: center;">
<input class ="test" type="text" name="input" placeholder="amount" type="text">
<input id="btnSubmit"  type="submit" name="submit" placeholder="amount" type="text">
<span style="color:red;">
<?= $val->errors['input']; ?>
</span>


</form>
<script type="text/javascript">


</script>
</body>

</html>