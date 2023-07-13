<?php
namespace foobarwhatever\dingdong;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST,DELETE");
header("Access-Control-Allow-Headers: Content-Type");
require 'database.php';

class CRUD extends DB
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    private $db;
    public $result;
    public $lastInsertedId;

    public function show()
    {
        $sql = "SELECT id,purchasetype,amount,created_at FROM expenses";
        $this->result = $this->db->pdo->query($sql)->fetchall();

        return $this->result;
    }

    public function getByPurchaseType($searchVariable)
    {
        $sql = "SELECT id,purchasetype,amount,created_at FROM expenses WHERE purchasetype = '$searchVariable'";
        // --  WHERE DATE_FORMAT(created_at,'%m') =MONTH(NOW());";
        $this->result = $this->db->pdo->query($sql)->fetchall();

        return $this->result;
    }
    public function insertExpense($amount, $purchaseType, $currentDateTime)
    {
        $sql = "INSERT INTO expenses (purchasetype,amount,created_at) VALUES(?,?,?)";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$purchaseType,$amount, $currentDateTime]);
        return $this->lastInsertedId = $this->db->pdo->lastInsertId();
    }

    public function deleteExpense($id)
    {
        $sql = "DELETE FROM expenses WHERE id = '$id'";
        $this->result = $this->db->pdo->query($sql);
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
    $data = isset($_POST) ? $_POST : json_decode(file_get_contents("php://input"), true);
    $selectedOptionAddingExpense = $data['selectedOptionAddingExpense'];
    $typedAmount = $data['typedAmount'];
    $currentDateTime = $data['currentDateTime'];
    $lastInsertedIdValue = $crud->insertExpense($typedAmount, $selectedOptionAddingExpense, $currentDateTime);
    echo json_encode(['lastInsertedId' => $lastInsertedIdValue]);
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = isset($_DELETE) ? $_DELETE : json_decode(file_get_contents("php://input"), true);
    $url = $_SERVER['REQUEST_URI'];
    $url = explode('/', $url);
    $urlLength = count($url);
    $id = $url[$urlLength - 1];
    $crud->deleteExpense($id);
}