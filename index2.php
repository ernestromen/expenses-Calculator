<?php
namespace foobarwhatever\dingdong;

require 'database.php';


class CRUD extends DB
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    private $db;
    public $result;

    public function show()
    {
        
        $sql = "SELECT purchasetype,amount,created_at FROM expenses";
        // --  WHERE DATE_FORMAT(created_at,'%m') =MONTH(NOW());";
        $this->result = $this->db->pdo->query($sql)->fetchall();

        return $this->result;
    }

    public function getByPurchaseType($searchVariable)
    {
       
        // $sql = "SELECT purchasetype,amount,created_at FROM expenses WHERE purchasetype = 'Food'";
        $sql = "select * from expenses where purchasetype = 'food'";
        // --  WHERE DATE_FORMAT(created_at,'%m') =MONTH(NOW());";
        $this->result = $this->db->pdo->query($sql)->fetchall();

        return $this->result;
    }

}
$res = new DB('localhost', 'db0123', 'root', '');

$crud = new CRUD($res);
// $data = $crud->show();

if (isset($_GET['type'])) {
    $purchaseType = $_GET['type'];
    $data = $crud->getByPurchaseType($purchaseType);    
} else {
    $data = $crud->show();
}


echo json_encode(['expenses' => $data]);