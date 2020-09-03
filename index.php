<?php
// Run this script if you need to create the tables
/*
use CryptoDB;
$db = new CryptoDB();
$db->createTables();
*/

session_start();

//echo phpinfo();

if (isset($_SESSION['logged_in'])) {
    $url = 'Location: ' . "https://" . $_SERVER['SERVER_NAME'] . '/home.php';
    header($url);
}
?>
<html>
<header>
  <title>Crypto Exchange</title>
  <link href="/styles/bootstrap.css" type="text/css" rel="stylesheet">
  <link href="/styles/mdb.css" type="text/css" rel="stylesheet">
  <link href="/styles/style.css" type="text/css" rel="stylesheet">
</header>

<body>
<!-- Material form login -->
<div class="container mt-5">
  <img src="./assets/medici_coin_edit.png" alt="" class="my-3 mx-auto d-block z-depth-2 rounded">

  <div class="card">

    <h5 class="card-header info-color white-text text-center py-4">
      <strong>Sign in</strong>
    </h5>

    <!--Card content-->
    <div class="card-body px-lg-5 pt-0">

      <!-- Form -->
      <form class="text-center" style="color: #757575;" action="./util/authenticate.php" method="post">

        <!-- Username -->
        <div class="md-form">
          <input required type="text" id="username" name="username" class="form-control">
          <label for="username" class="">Username</label>
        </div>

        <!-- Password -->
        <div class="md-form">
          <input required type="password" id="password" name="password" class="form-control">
          <label for="password">Password</label>
        </div>

        <!-- Sign in button -->
        <button name="signInButton"
                class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0"
                type="submit">
          Sign in
        </button>

<!--        <p>Not registered yet? <a href="/signUp.php">Sign Up Here</a></p>-->

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
      </form>

    </div>

  </div>

</div>


<script src="./scripts/jquery.js"></script>
<script src="./scripts/bootstrap.js"></script>
<script src="./scripts/mdb.js"></script>
<script src="./scripts/popper.js"></script>
</body>
</html>
