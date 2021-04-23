

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
              // $a = 'from php with love';
echo '<script>

    var res = confirm("Hello! I am an alert box!!");
    console.log(res);
              // if not true
              if((res)){

document.cookie = "confirm =1;";
                // alert("income recored");
                
              }else if(!(res)){

                document.cookie = "confirm =0;";


              }
</script>';
echo '<pre>';
// var_dump($_COOKIE);
if($_COOKIE['confirm'] =='1'){
  // var_dump($this->connect());
  // die;
$this->insert($result);
}else{
var_dump('in else if');
}
// foreach($_COOKIE as $key=>$value)
// {
//   echo "key: ".$key.'<br />';
//   echo "value: ".$value.'<br />';
// };



          

             }
            
            }
            
            
            
            
            }


      }  
        


}


class CRUD extends Validation{
//does all the actions
private $db;
public function __construct($db){
// global $db;
$this->db = $db;
// var_dump($this->db);
}

  public  function insert($res){
        $sql = "INSERT INTO expenses (amount) VALUES ($res)";

    // var_dump($this->db->pdo);
    var_dump($res);
    var_dump($this->db->pdo->query($sql));
    // $test = 'inside insert';
    // var_dump($test);
    // var_dump($this->db);
    // $attmept = new DB();
    // var_dump($attmept);

    
      }

      public function show(){
$sql = "SELECT * FROM expenses";
$result =  $this->db->pdo->query($sql);
foreach($result as $res1){

echo  ($res1['id']);
}





      }

}








$res = new DB();
$val = new Validation();
$crud = new CRUD($res);

$crud->connect()->validate();






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
<input id="btnSubmit"  type="submit" name="submit" placeholder="amount" type="text">
<span style="color:red;">
<?= $val->errors['input']; ?>
</span>


</form>



<div class="">
  
<?php $crud->show();?>
</div>
<script type="text/javascript">


</script>
</body>

</html>