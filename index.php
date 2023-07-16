<?php
namespace foobarwhatever\dingdong;

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, POST,DELETE");
header("Access-Control-Allow-Headers: Content-Type");
require 'database.php';
use PDO;

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
        $this->result = $this->db->pdo->query("SELECT id,purchasetype,amount,created_at FROM expenses")->fetchAll(PDO::FETCH_ASSOC);
        return $this->result;
    }

    public function getByPurchaseType($searchVariable)
    {
        // --  WHERE DATE_FORMAT(created_at,'%m') =MONTH(NOW());";
        $this->result = $this->db->pdo->query("SELECT id,purchasetype,amount,created_at FROM expenses WHERE purchasetype = '$searchVariable'")->fetchall(PDO::FETCH_ASSOC);
        return $this->result;
    }
    public function insertExpense($amount, $purchaseType, $currentDateTime)
    {
        $stmt = $this->db->pdo->prepare("INSERT INTO expenses (purchasetype,amount,created_at) VALUES(?,?,?)");
        $stmt->execute([$purchaseType, $amount, $currentDateTime]);
        return $this->lastInsertedId = $this->db->pdo->lastInsertId();
    }

    public function deleteExpense($id)
    {
        $this->result = $this->db->pdo->query("DELETE FROM expenses WHERE id = '$id'");
    }
    public function getTotalSum($searchVariable = NULL)
    {
        $this->result = isset($searchVariable) ? $this->db->pdo->query("SELECT SUM(amount) as totalSum FROM expenses WHERE purchasetype = '$searchVariable'")->fetchAll(PDO::FETCH_ASSOC) :
        $this->db->pdo->query("SELECT SUM(amount) as totalSum FROM expenses")->fetchAll(PDO::FETCH_ASSOC);
        return $this->result;
    }
}

$res = new DB('localhost', 'db0123', 'root', '');
$crud = new CRUD($res);
$type = isset($_GET['type']);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['type'])) {
    $data = $crud->getByPurchaseType($_GET['type']);
    $totalSum = $crud->getTotalSum($_GET['type']);
    echo json_encode(['expenses' => $crud->getByPurchaseType($_GET['type']), 'totalSum' => $totalSum]);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = $crud->show();
    $totalSum = $crud->getTotalSum();
    echo json_encode(['expenses' => $data, 'totalSum' => $totalSum]);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = isset($_POST) ? $_POST : json_decode(file_get_contents("php://input"), true);
    $selectedOptionAddingExpense = $data['selectedOptionAddingExpense'];
    $typedAmount = $data['typedAmount'];
    $currentDateTime = $data['currentDateTime'];
    $lastInsertedIdValue = $crud->insertExpense($typedAmount, $selectedOptionAddingExpense, $currentDateTime);
    echo json_encode(['lastInsertedId' => $lastInsertedIdValue]);
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $url = explode('/', $_SERVER['REQUEST_URI']);
    $urlLength = count($url);
    $id = $url[$urlLength - 1];
    $crud->deleteExpense($id);
}