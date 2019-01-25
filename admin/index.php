<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 21/11/2018
 * Time: 18:18
 */
    session_start();
    $noNavbar = '';
    $pageTitle= 'Login';
    if(isset($_SESSION['Username'])) {
        header('Location:dashboard.php'); //Redirect to dashboard page
    }
    include 'init.php';


    //Check if user is coming from HTTP POST request
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedpass = sha1($password);

        // Check if user exist in Database

        $stmt = $con->prepare("SELECT UserID,Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1");
        $stmt->execute(array($username,$hashedpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // If count > 0 the data base contain data about this user
        if($count > 0 ) {

            $_SESSION['Username'] = $username; //Register Session name
            $_SESSION['ID'] = $row['UserID']; //Register session ID
            header('Location:dashboard.php'); //Redirect to dashboard page
            exit();
        }
    }
?>
        <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
            <h4 class="text-center">Admin Login</h4>
            <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
            <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
            <input class="btn btn-primary btn-block" type="submit" value="Login" />
        </form>
<?php include $tpl. 'footer.php'; ?>
