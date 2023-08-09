<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST,DELETE");
header("Access-Control-Allow-Headers: Content-Type, content-type");
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
        $this->result = $this->db->pdo->query("SELECT id,purchasetype,amount,created_at FROM expenses")->fetchAll(PDO::FETCH_ASSOC);
        return $this->result;
    }

    public function searchPurchases($searchTypeVariable, $searchTimeVariable)
    {
        $query = "SELECT id, purchasetype, amount, created_at FROM expenses WHERE 1";

        if ($searchTimeVariable) {
            if ($searchTimeVariable == 'Today') {
                $query .= " AND DATE_FORMAT(created_at, '%d') = DAY(NOW())";
            } else if ($searchTimeVariable == 'Monthly') {
                $query .= " AND DATE_FORMAT(created_at, '%m') = MONTH(NOW())";
            } else if ($searchTimeVariable == 'Yearly') {
                $query .= " AND DATE_FORMAT(created_at, '%Y') = YEAR(NOW())";
            }
        }

        if ($searchTypeVariable && $searchTypeVariable != 'All') {
            $query .= " AND purchasetype = '$searchTypeVariable'";
        }

        $this->result = $this->db->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

        return $this->result;
    }

    public function insertExpense($amount, $purchaseType, $currentDateTime)
    {
        $stmt = $this->db->pdo->prepare("INSERT INTO expenses (purchasetype,amount,created_at) VALUES(?,?,?)");
        $stmt->execute([$purchaseType, $amount, $currentDateTime]);
        return $this->lastInsertedId = $this->db->pdo->lastInsertId();
    }

    public function deleteExpense($id = null, $checkedIds = null)
    {
        !is_null($checkedIds) ? $sasitizedIds = implode(',', $checkedIds) : '';
        !is_null($checkedIds) && count($checkedIds) > 0 ? $this->result = $this->db->pdo->query("DELETE FROM expenses WHERE id IN ($sasitizedIds)") :
            $this->result = $this->db->pdo->query("DELETE FROM expenses WHERE id = '$id'");
    }
    public function getTotalSum($searchVariable = NULL)
    {
        $this->result = isset($searchVariable) && $searchVariable !== 'All' ? $this->db->pdo->query("SELECT SUM(amount) as totalSum FROM expenses WHERE purchasetype = '$searchVariable'")->fetchAll(PDO::FETCH_ASSOC) :
            $this->db->pdo->query("SELECT SUM(amount) as totalSum FROM expenses")->fetchAll(PDO::FETCH_ASSOC);
        return $this->result;
    }
}

$res = new DB('db', 'db0123', 'root', 'root');
$crud = new CRUD($res);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && count($_GET) > 0) {
    if (isset($_GET['time']) || $_GET['type']) {
        echo json_encode(['expenses' => $crud->searchPurchases($_GET['type'], $_GET['time']), 'totalSum' => $crud->getTotalSum()]);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['expenses' => $crud->show(), 'totalSum' => $crud->getTotalSum()]);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastInsertedIdValue = $crud->insertExpense($_POST['typedAmount'], $_POST['selectedOptionAddingExpense'], $_POST['currentDateTime']);
    echo json_encode(['lastInsertedId' => $lastInsertedIdValue, 'totalSum' => $crud->getTotalSum()]);
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $url = explode('/', $_SERVER['REQUEST_URI'])[1];

    if (strpos($url, '=') !== false) {
        $queryString = explode("=", $url)[1];
        if ($queryString == 'delete-all-check-ids') {
            $ids = json_decode(file_get_contents('php://input'), true);
            $crud->deleteExpense(null, $ids);
        } else if (is_numeric($queryString)) {
            $crud->deleteExpense($queryString);
        }
    }
    echo json_encode(['totalSum' => $crud->getTotalSum()]);
}