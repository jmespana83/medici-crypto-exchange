<!DOCTYPE html>
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

  <img src="assets/medici_coin_edit.png" class="m-auto">

  <div class="card">

    <h5 class="card-header info-color white-text text-center py-4">
      <strong>Sign Up</strong>
    </h5>

    <!--Card content-->
    <div class="card-body px-lg-5 pt-0">

      <!-- Form -->
      <form class="text-center" style="color: #757575;" action="signUp.php" method="post">
        <!-- Username -->
        <div class="md-form">
          <input type="text" id="username" name='username' class="form-control" required>
          <label for="username">Username</label>
        </div>

        <!-- Password -->
        <div class="md-form">
          <input type="password" id="password" name="password" class="form-control" required>
          <label for="password">Password</label>
        </div>

        <!-- confirm -->
        <div class="md-form">
          <input type="password" id="confirm" name="confirm" class="form-control" required>
          <label for="confirm">Confirm Password</label>
        </div>

        <!-- Initial Amount -->
        <div class="md-form">
          <input type="number" min="0" max="99999" id="amount" name="amount" class="form-control" required step="1">
          <label for="amount">Initial Amount</label>
        </div>

        <button
                class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit"
                name="signUpButton"
        >
          Sign Up
        </button>
        <a class="text-center" href="home.php">Back</a>
        <div class="text-center text-danger">
            <?php
            include 'util/CryptoDB.php';

            if (isset($_POST['signUpButton'])) {
                if ($_POST['password'] === $_POST['confirm']) {
                    session_start();

                    // add user to DB
                    $cryptoDB = new CryptoDB();
                    $cryptoDB->createUser($_POST['username'], $_POST['password'], $_POST['amount']);

                    // save success message to be displayed in the home screen
                    $_SESSION['success'] = ['message' => 'Sign Up Successful'];

                    // redirect to sign-in page (index.php) after successful sign up. Remove PORT in production! (last
                    // portion of the string
                    $url = 'Location: ' . "https://" . $_SERVER['SERVER_NAME'];
                    header($url);
                    exit();
                } else {
                    echo '<div class="mark">Passwords must match!</div>';
                }
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
