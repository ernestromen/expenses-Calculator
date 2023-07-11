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

    public function getByPurchaseType()
    {
        $sql = "select purchasetype,amount,created_at from expenses where purchasetype = 'food'";
        // --  WHERE DATE_FORMAT(created_at,'%m') =MONTH(NOW());";
        $this->result = $this->db->pdo->query($sql)->fetchall();

        return $this->result;
    }

}


$res = new DB('localhost', 'db0123', 'root', '');

$crud = new CRUD($res);

if (isset($_GET['type'])) {
    // The 'type' query parameter exists
$data = $crud->getByPurchaseType();
echo json_encode(['expenses' => $data]);
} else {
    $data = $crud->show();
    echo json_encode(['expenses' => $data]);
}
