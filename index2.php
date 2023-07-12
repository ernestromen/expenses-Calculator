<?php
namespace foobarwhatever\dingdong;

require 'database.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
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
        $this->result = $this->db->pdo->query($sql)->fetchall();

        return $this->result;
    }

    public function getByPurchaseType($searchVariable)
    {
        $sql = "select purchasetype,amount,created_at from expenses where purchasetype = '$searchVariable'";
        // --  WHERE DATE_FORMAT(created_at,'%m') =MONTH(NOW());";
        $this->result = $this->db->pdo->query($sql)->fetchall();

        return $this->result;
    }
    public function insertExpense($amount, $purchaseType)
    {
        $sql = "INSERT INTO expenses (purchasetype,amount,created_at) VALUES('$purchaseType','$amount',NOW())";
        $this->db->pdo->query($sql);
    }
}

$res = new DB('localhost', 'db0123', 'root', '');
$crud = new CRUD($res);
$type = isset($_GET['type']);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['type'])) {
    $data = $crud->getByPurchaseType($_GET['type']);
    echo json_encode(['expenses' => $data]);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = $crud->show();
    echo json_encode(['expenses' => $data]);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $selectedOptionAddingExpense = $data['selectedOptionAddingExpense'];
    $typedAmount = $data['typedAmount'];
    $crud->insertExpense($typedAmount, $selectedOptionAddingExpense);
    $data = $crud->show();
    echo json_encode(["expenses" => $data]);
}