<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 23/11/2018
 * Time: 13:48
 */
   /* session_start(); //Start the session

    session_unset(); //Unset the Data
    session_destroy();*/
// Initialize the session.
// If you are using session_name("something"), don't forget it now!
session_start();

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
//Move the the Login Page
    header('Location:index.php');

    exit();