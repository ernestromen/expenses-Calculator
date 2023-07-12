<?php

require 'database.php';
use foobarwhatever\dingdong\DB;

error_reporting(E_ALL);
ini_set("display_errors", 1);
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

    return $this->result;
  }
  public function insert($purchaseType, $amount)
  {
    $sql = "INSERT INTO expenses (purchasetype,amount,created_at) VALUES ('$purchaseType','$amount',NOW())";
    $this->db->pdo->query($sql);
  }
}


// $res = new DB($cleardb_server,$cleardb_db,$cleardb_username,$cleardb_password);
$res = new DB('localhost', 'db0123', 'root', '');
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
</head>

<body>
  <div class="mt-3" style="display: grid; grid-template-columns: auto 20% auto auto;gap: 20px;">
    <div class="w-75" style="text-align:center;">
      <form method="post">
        <select class="custom-select" name="purchasetype">
          <option value="">Choose Timeline</option>
          <option value="Today">Today</option>
          <option value="Mounthly">Mounthly</option>
          <option value="Yearly">Yearly</option>
        </select>
        <select class="custom-select" name="purchasetype">
          <option value="">Choose purchasetype</option>
          <option value="Utilities">Utilities</option>
          <option value="Recreational">Recreational</option>
          <option value="Food">Food</option>
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
  </div>
  <br>
  <br>

  <h1 class="mb-5" style="text-align:center">daily expenses</h1>
  <div id="app">
    <div v-if="expenses.length === 0" class="loader-container">
      <div class="loader"></div>
    </div>
    <table v-else class="table w-50 text-center m-auto">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Type</th>
          <th scope="col">Amount</th>
          <th scope="col">Created at</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(expense, index) in expenses" :key="index">
          <th scope="row">{{ index }}</th>
          <td>{{ expense.purchasetype }}</td>
          <td>{{ expense.amount }}</td>
          <td>{{ expense.created_at }}</td>
        </tr>
      </tbody>
    </table>
  </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
  <script>
    new Vue({
      el: '#app',
      data: function () {
        return {
          expenses: [],
        }
      },
      mounted() {
        this.getAllExpenses();
      },
      methods: {
        getAllExpenses: function () {
          axios.get('index2.php')
            .then(response => {
              this.expenses = response.data['expenses'];
              console.log(this.expenses);
            })
            .catch(error => {
              console.error(error);
            });
        },
        getExpenseByPurchaseType: function () {
          axios.get(`index2.php?type=food`)
            .then(response => {
              const expense = response.data['expense'];
              console.log(expense,'expense'); // Handle the response as needed
            })
            .catch(error => {
              console.error(error);
            });
        }

      }
    });

  </script>
</body>

</html>