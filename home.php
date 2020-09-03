<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === FALSE) {
    $url = 'Location: ' . "https://" . $_SERVER['SERVER_NAME'];
    header($url);
}

include 'util/TxnDB.php';
$txnDB = new TxnDB();
$txnDB->getLatestTxns();
?>

<!DOCTYPE html>
<html>
<header>
  <title>Crypto Exchange</title>
  <link href="/styles/bootstrap.css" type="text/css" rel="stylesheet">
  <link href="/styles/mdb.css" type="text/css" rel="stylesheet">
  <link href="/styles/style.css" type="text/css" rel="stylesheet">
</header>

<body>

<nav class="navbar navbar-expand-lg navbar-dark default-color">
  <a class="navbar-brand" href="#">Crypto Exchange</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav w-100 d-flex justify-content-end">
      <li class="nav-item">
        <a class="nav-link text-right" href="#" onclick="logout()">Logout <span class="sr-only">(current)</span></a>
      </li>
    </ul>
  </div>
</nav>

<h1 class="text-center m-4">Welcome <?php echo $_SESSION['user']['un'] ?>!</h1>

<img src="./assets/medici_coin_edit.png" alt="" class="my-3 mx-auto d-block z-depth-2 rounded">

<div class="container">
  <div class="row">
    <div class="col-md-6 my-2">
      <h2 class="text-center">Account Number:</h2>
      <p class="text-center"><?php echo $_SESSION['user']['acc']; ?></p>
    </div>

    <div class="col-md-6 my-2">
      <h2 class="text-center">Balance:</h2>
      <h3 class="text-center"><?php echo $_SESSION['user']['bal']; ?></h3>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-6 mx-auto my-2">
      <form class="text-center" action="util/transaction.php" method="post">
        <h4>Transfer to Another User</h4>
        <!-- Account Number -->
        <div class="md-form">
          <input type="text" id="account" name='account' class="form-control" required>
          <label for="account">Receiver Account</label>
        </div>

        <!-- Amount -->
        <div class="md-form">
          <input type="number" min="1" max="99999" step="1" id="amount" name="amount" class="form-control" required>
          <label for="amount">Amount</label>
        </div>

        <button type="submit" class="btn aqua-gradient" name="sendButton">Send</button>
      </form>

      <div class="text-center">
          <?php
          // if session messages are set, display them to notify user after reroute

          if (isset($_SESSION['success'])) {
              echo '<div class="text-success mark">' . $_SESSION['success']['message'] . '</div>';
              unset($_SESSION['success']);
          } elseif (isset($_SESSION['error'])) {
              echo '<div class="text-danger mark">' . $_SESSION['error']['message'] . '</div>';
              unset($_SESSION['error']);
          }
          ?>
      </div>
    </div>
  </div>
</div>

<div class="container text-center">
  <div class="row mt-4 d-flex align-items-center">
    <div class="col-md-3">
      <h3 class="lead">Latest Transactions</h3>
    </div>
    <div class="col-md-9">
      <table class="table">
        <thead class="text-center">
        <tr>
          <th scope="col">Sender</th>
          <th scope="col">Receiver</th>
          <th scope="col">Amount</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($_SESSION['txn'])) {
            $records = $_SESSION['txn'];

            // loop through records and print to table
            foreach ($records as $record) {
                echo "
                  <tr class='text-center'>
                    <td>$record[0]</td>
                    <td>$record[2]</td>
                    <td>$$record[1]</td>
                  </tr>
                 ";
            }
        } else {
            echo "
              <tr>
                <td colspan='3' class='text-center'>There are no transactions</td>
              </tr>
            ";
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  const logout = () => {
    // AJAX call to link PHP functionality to DOM object
    const baseUrl =  'https://' + window.location.hostname;
    const logoutUrl = baseUrl + '/util/logout.php';
    fetch(logoutUrl)
      .then(res => {
        console.log('Logging out...');
        // redirect with javascript after running logout.php
        window.location = baseUrl;
      })
      .catch(err => {
        console.log(`Error logging out: ${err}`);
      })
  }
</script>
<script src="./scripts/jquery.js"></script>
<script src="./scripts/bootstrap.js"></script>
<script src="./scripts/mdb.js"></script>
<script src="./scripts/popper.js"></script>
</body>
</html>
