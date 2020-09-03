<?php
// script to log out
session_start();

// unset all session variables
session_unset();

$_SESSION['success'] = ['message' => 'Logged Out Successfully'];
