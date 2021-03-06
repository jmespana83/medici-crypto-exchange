<?php
if (!isset($_SESSION)) {
    session_start();
}

Class CryptoDB
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

    /**
     * method to create tables in the database (meant to be done once only)
     */
    public function createTables()
    {
        // sql to create crypto table
        $sql = "CREATE TABLE crypto (
            un VARCHAR(100) NOT NULL,
            pw VARCHAR(100) NOT NULL,
            acc VARCHAR(100) NOT NULL,
            bal INT
        )";

        if ($this->conn->query($sql) === TRUE) {
            echo "Table CRYPTO created successfully";
        } else {
            echo "Error creating table: " . $this->conn->error;
        }

        // sql to create crypto table
        $sql = "CREATE TABLE txn (
            senderAcc VARCHAR(100) NOT NULL,
            amt INT NOT NULL,
            receiverAcc VARCHAR(100) NOT NULL
        )";

        // sql to create TXN table
        if ($this->conn->query($sql) === TRUE) {
            echo "Table TXN created successfully";
        } else {
            echo "Error creating table: " . $this->conn->error;
        }
    }

    /**
     * creates user in the database. Inserts argument into the appropriate columns.
     * acc is randomly generated by PHP function "uniqid()". Called from signUp.php.
     * Re-routes home with error message if unsuccessful.
     *
     * @param $username - un
     * @param $password - pw
     * @param $amount - bal
     * @return bool|mysqli_result
     */
    public function createUser($username, $password, $amount)
    {
        // unique account id assigned to auto-assigned to the user
        $acc = uniqid();
        $sql = "INSERT INTO crypto (un, pw, acc, bal)
           VALUES ('$username', '$password', '$acc', '$amount'); 
        ";

        $result = $this->conn->query($sql);

        if ($result === FALSE) {
            $_SESSION['error'] = ['message' => 'User cannot be added to the database. Try again.'];
            // redirect to sign-in page (index.php) after successful sign up. Remove PORT in production! (last
            // portion of the string
            $homeUrl = 'Location: ' . "https://" . $_SERVER['SERVER_NAME'];
            header($homeUrl);
            exit();
        }

        return $result;
    }

    /**
     * Gets user from Crypto table. If successful, it will store
     * the user in sessions as a "receiver"
     *
     * @param $acc - acc from crypto table
     * @return bool|mixed|mysqli_result - record in array form
     */
    public function getUser($acc, $receiverFlag)
    {
        $sql = "SELECT un, acc, bal FROM crypto 
            WHERE '$acc' = acc
        ";

        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            // if receiver flag is set, set retrieved record for receiver
            // else set as retrieved record for user
            $receiverFlag
                ? $_SESSION['receiver'] = $result->fetch_assoc()
                : $_SESSION['user'] = $result->fetch_assoc();
        }
        return $result->num_rows > 0;
    }

    /**
     * updates the balance in the database
     *
     * @param $acc - account number
     * @param $newBalance - the new balance of the account
     * @return bool|mysqli_result - if the query was successful
     */
    public function updateBalance($acc, $newBalance)
    {
        $sql = "UPDATE crypto
            SET bal = '$newBalance'
            WHERE acc = '$acc'
        ";

        return $this->conn->query($sql);
    }

}
