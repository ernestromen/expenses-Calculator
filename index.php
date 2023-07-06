<?php

require 'database.php';
use foobarwhatever\dingdong\DB;

// session_start();
// if(!(isset($_SESSION['userid']) && isset($_SESSION['useruid']))){
// header("location: ../signin.php");
// exit();


// }


error_reporting(E_ALL);
ini_set("display_errors", 1);





$chosenDate = null;
class Validation extends DB
{
  //validation 
  public function __construct($db)
  {
    $this->db = $db;
  }

  public $errors = [

  ];

  public function validate()
  {

    //when expenses submitted 
    if (isset($_POST['submit'])) {
      if (empty($_POST['purchasetype']) || empty($_POST['amount'])) {

        $this->errors['purchasetype'] = '<br>' . '* the input is not valid';


      } else if (!(is_numeric($_POST['amount']))) {

        $this->errors['amount'] = '<br>' . '* the amount must be a number';
      }

      //if the input passes all the validation
      else {

        $purchaseType = $_POST['purchasetype'];
        $amount = $_POST['amount'];
        $this->insert($purchaseType, $amount);

      }
    }


    //when salary is submitted 

    if (isset($_POST['submitSalary'])) {



      if (empty($_POST['salary']) || empty($_POST['source'])) {
        $this->errors['salaryInput'] = '<br>' . '* the inputs must not be a empty';

      } else if (!(is_numeric($_POST['salary']))) {

        $this->errors['salaryInput'] = '<br>' . '* the salary input must be a number';

      } else if (is_numeric($_POST['source'])) {
        $this->errors['salaryInput'] = '<br>' . '* the source input must not be a number';

      } else {


        $result = $_POST['source'];
        $result2 = $_POST['salary'];
        $where = 'submitSalary';
        $this->insert($result, $result2, $where);

      }


    }

    ////validation for additinal income

    if (isset($_POST['submitIncome'])) {



      if (empty($_POST['income']) || empty($_POST['source2'])) {
        $this->errors['incomeInput'] = '<br>' . '* the inputs must not be a empty';

      } else if (!(is_numeric($_POST['income']))) {

        $this->errors['incomeInput'] = '<br>' . '* the salary input must be a number';

      } else if (is_numeric($_POST['source2'])) {
        $this->errors['incomeInput'] = '<br>' . '* the source input must not be a number';

      } else {


        $result = $_POST['source2'];
        $result2 = $_POST['income'];
        $where = 'submitIncome';
        $this->insert($result, $result2, $where);

      }


    }



  }

}

class CRUD extends Validation
{
  //does all the actions
  private $db;
  public $result;
  public function __construct($db)
  {
    $this->db = $db;
  }

  public function show()
  {

    $sql = "SELECT purchasetype,amount,created_at FROM expenses";
    // --  WHERE DATE_FORMAT(created_at,'%m') =MONTH(NOW());";
    $this->result = $this->db->pdo->query($sql)->fetchall();

    return ($this->result);
  }
  public function insert($purchaseType, $amount)
  {
    $sql = "INSERT INTO expenses (purchasetype,amount,created_at) VALUES ('$purchaseType','$amount',NOW())";
    $this->db->pdo->query($sql);

  }
}


// $res = new DB($cleardb_server,$cleardb_db,$cleardb_username,$cleardb_password);
$res = new DB('localhost', 'db0123', 'root', '');
//$val causes 'too few arguments passed' Error 
$val = new Validation($res);
$crud = new CRUD($res);
$crud->validate();
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <style>
    .container2 {
      padding: 20px;
      display: grid;
      grid-template-columns: 20% 20%;
      justify-content: center;


    }
  </style>
</head>

<body>

  <div class="mt-3" style="display: grid; grid-template-columns: auto 20% auto auto;">
    <div style="text-align:center;">

      <form method="post">
        <select class="custom-select" name="purchasetype">
          <option value="">Choose Timeline</option>
          <option value="Today">Today</option>
          <option value="Mounthly">Mounthly</option>
          <option value="Yearly">Yearly</option>
        </select>
      </form>
    </div>

    <?php if (count($crud->errors) > 0): ?>
      <div class="text-center mt-3">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Error!</strong>
          <?= $crud->errors['purchasetype']; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <form method="post">
        <select class="custom-select" name="purchasetype">
          <option value="">Choose purchasetype</option>
          <option value="Utilities">Utilities</option>
          <option value="Recreational">Recreational</option>
          <option value="Food">Food</option>
        </select>
        <div class="form-group">
          <input name="amount" class="form-control" name="amount" type="number" placeholder="amount" />
        </div>
        <div class="form-group">
          <input class="btn btn-primary w-100" name="submit" type="submit" value="click">
        </div>
      </form>
    </div>


    <div style="text-align:center;">

    </div>


    <div style="text-align:center;">
    </div>
  </div>
  <br>
  <br>

  <h1 style="text-align:center">daily expenses</h1>

  <div class="container ">
    <div class="itemgrid">type</div>
    <div class="itemgrid">Amount</div>
    <div class="itemgrid">Created at</div>
    <?php foreach ($crud->result as $row): ?>
      <div class="itemgrid">
        <?= $row['purchasetype']; ?>
      </div>
      <div class="itemgrid">
        <?= $row['amount']; ?>
      </div>
      <div id="date" class="itemgrid">
        <?= $row['created_at']; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
</body>

</html>