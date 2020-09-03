<?php
// script to process transaction
session_start();
include 'CryptoDB.php';
include 'TxnDB.php';

$cryptoDB = new CryptoDB();
$txnDB = new TxnDB();

/**
 * function to check if account number matches user.
 * Used to compare receiver to current user.
 *
 * @param $acc - account number to compare
 * @return bool - true account number matches current user
 */
function receiverIsUser($acc)
{
    return $_SESSION['user']['acc'] === $acc;
}

/**
 * check to see if the user has less than or equal to desired amount to send
 *
 * @param $transferAmount - the amount the user inputted to send
 * @param $balance - the current balance on the user's account
 * @return bool - if the user has the required balance to send
 */
function hasEnoughFunds($transferAmount, $balance)
{
    return $transferAmount <= $balance;
}

function transferBalance($user, $receiver, $amount, $cryptoDB, $txnDB)
{
    // user information
    $newUserBal = $user['bal'] - $amount;
    $userAcc = $user['acc'];

    // receiver information
    $newReceiverBal = $receiver['bal'] + $amount;
    $receiverAcc = $receiver['acc'];
    $receiverUsername = $receiver['un'];

    if ($cryptoDB->updateBalance($userAcc, $newUserBal) && $cryptoDB->updateBalance($receiverAcc, $newReceiverBal)) {
        // Add message to indicate successful transfer
        $_SESSION['success'] = ['message' => 'Successfully sent $' . $amount . ' to ' . $receiverUsername];

        // Add transaction to txn table
        $txnDB->createTxn($userAcc, $receiverAcc, $amount);
    }

    // update user information client side
    $cryptoDB->getUser($userAcc, FALSE);
}

if (isset($_POST['sendButton'])) {
    // input from transaction form
    $account = $_POST['account'];
    $amount = $_POST['amount'];

    // user balance to check if there is enough funds
    $userBal = $_SESSION['user']['bal'];


    switch(TRUE) {

        // Check if account number is the current user's own
        case receiverIsUser($account):
            $_SESSION['error'] = ['message' => "Cannot transfer to own account. Use a different account number!"];

                // re-route to home after setting error
            $homeUrl = 'Location: ' . "http:s//" . $_SERVER['SERVER_NAME'] . '/home.php';
            header($homeUrl);

            break;

        // Validate that the user has enough to transfer
        case !hasEnoughFunds($amount, $_SESSION['user']['bal']):
            $_SESSION['error'] = ['message' => 'You do not have enough to transfer the input amount.'];

            // re-route to home after setting error
            $homeUrl = 'Location: ' . "https://" . $_SERVER['SERVER_NAME'] . '/home.php';
            header($homeUrl);

            break;

        // Validate if user account exists and retrieve user with the same method
        case !$cryptoDB->getUser($_POST['account'], TRUE):
            $_SESSION['error'] = ['message' => 'User cannot be found. Try a different account number.'];

            // send user back to home OR login page when getUser cannot be found
            $homeUrl = 'Location: ' . "https://" . $_SERVER['SERVER_NAME'] . '/home.php';
            header($homeUrl);

            break;

        default:
            //all validation checks if code has reached this point... Transfer
            transferBalance($_SESSION['user'], $_SESSION['receiver'], $amount, $cryptoDB, $txnDB);
    }

    // route to home after storing receiver information
    $homeUrl = 'Location: ' . "https://" . $_SERVER['SERVER_NAME'] . '/home.php';
    header($homeUrl);
}
