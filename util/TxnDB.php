<?php
if (!isset($_SESSION)) {
    session_start();
}

Class TxnDB
{
    private $conn;

    public function __construct()
    {
        // Set db variables here...
         $db_server = getenv('DB_SERVER');
         $db_name = getenv('DB_NAME');
         $db_user = getenv('DB_USER');
         $db_password = getenv('DB_PASSWORD');

        $this->conn = new mysqli(
            $db_server,
            $db_user,
            $db_password,
            $db_name
        );
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function createTxn($userAcc, $receiverAcc, $amount)
    {
        $sql = "INSERT INTO txn
            (senderAcc, amt, receiverAcc)
            VALUES ('$userAcc', '$amount', '$receiverAcc')        
        ";

        return $this->conn->query($sql);
    }

    public function getLatestTxns()
    {
        $sql = "SELECT * FROM txn";

        $records = $this->conn->query($sql)->fetch_all();
        $length = count($records);

        // return only 10 latest transaction
        if ($length > 10) {
            $_SESSION['txn'] = array_slice($records, length - 10);
        } else {
            $_SESSION['txn'] = $records;
        }

    }
}
