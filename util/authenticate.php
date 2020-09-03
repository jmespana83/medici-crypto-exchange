<?php
session_start();

// AUTHENTICATION //
if (isset($_POST['signInButton'])) {
    $db_server = getenv('DB_SERVER');
    $db_name = getenv('DB_NAME');
    $db_user = getenv('DB_USER');
    $db_password = getenv('DB_PASSWORD');

    $conn = new mysqli($db_server, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if login form fields were filled and submitted
    if (!isset($_POST['username'], $_POST['password'])) {
        die ('Please fill both username and password.');
    }

    // Fetch record with matching username. Use binary to match exact case.
    if ($sql = $conn->prepare("SELECT * FROM crypto WHERE BINARY un = ?")) {
        // s for string param
        $sql->bind_param('s', $_POST['username']);
        $sql->execute();
        $sql->store_result();
    }

    if ($sql->num_rows > 0) {
        // store fetched results in PHP variables
        $sql->bind_result($un, $pw, $acc, $bal);
        $sql->fetch();
        // if account exists, verify password
        if ($_POST['password'] === $pw) {
            // If successful, create sessions to remember user
            session_start();
            session_regenerate_id();
            $_SESSION['logged_in'] = TRUE;
            $_SESSION['user'] = [
                'un' => $un,
                'acc' => $acc,
                'bal' => $bal
            ];

            // redirect to home page
            $url = 'Location: ' . "https://" . $_SERVER['SERVER_NAME'] . '/home.php';
            header($url);

        } else {
            $_SESSION['error'] = ['message' => 'Incorrect password'];
            // redirect to login page
            $url = 'Location: ' . "https://" . $_SERVER['SERVER_NAME'] . '/index.php';
            header($url);
        }
    } else {
        $_SESSION['error'] = ['message' => 'Invalid username'];
        // redirect to login page
        $url = 'Location: ' . "https://" . $_SERVER['SERVER_NAME'] . '/index.php';
        header($url);
    }
}
