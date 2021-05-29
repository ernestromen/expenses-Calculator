<?php


namespace foobarwhatever\dingdong;
// var_dump('insde database file');
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;


class DB  {
    //heroku shit
    
    
    
    
    
    //pdo connection to be inherited
    private $dns;
    private $user;
    private $password;
    protected $pdo;
    // public $cleardb_server = $cleardb_url["host"];
    // public $cleardb_username = $cleardb_url["user"];
    // public $cleardb_password = $cleardb_url["pass"];
    // public $cleardb_db = substr($cleardb_url["path"],1);
    
    
    // function __construct($server,$db,$user,$pass) {
    //   $this->connect($server,$db,$user,$pass);
    // }
    
      public function __construct($server,$db,$user,$pass){
        // var_dump('here');
      
                    // "mysql:host='.$host.'; dbname='.$dbname.';"
        $this->dns = "mysql:host=". $server."; dbname=" .$db.";";
        $this->user=$user;
        $this->password=$pass;
    // echo '<pre>';
    //     var_dump($this->dns,'dns');
    //     var_dump($db,'database');
    //     var_dump($this->user,'user');
    //     var_dump($this->password,'pass');
    
        try {
        $this->pdo = new PDO($this->dns,$this->user,$this->password); 
        // var_dump( $this->pdo);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //   foreach($this->pdo->query("SELECT * FROM expenses") as $row) {
      //     echo htmlentities($row["firstname"]);
      // }
      // $this->pdo = null;
    }catch(PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>" . "<br>";
      die();
    }
    
        return $this;
      }
    
      
    
    
      
      
    
    
    }
    

