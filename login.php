<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 25/12/2018
 * Time: 22:36
 */
ob_start();
session_start();
$pageTitle = 'Login';

if(isset($_SESSION['user'])){
    header('Location: index.php');
}

include 'init.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['login'])) {
            $user = $_POST['username'];
            $pass = $_POST['password'];

            $hashedpass = sha1($pass);

            $stmt = $con->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ?");
            $stmt->execute(array($user, $hashedpass));
            $get = $stmt->fetch();
            $count = $stmt->rowCount();

            // If count > 0 the data base contain data about this user
            if ($count > 0) {

                $_SESSION['Username'] = $user; //Register Session name
                $_SESSION['uid'] = $get['UserID']; //Register User ID
                header('Location:index.php');
                exit();
            }
        } else {
            $formErrors = array();
            $username    = $_POST['username'];
            $password    = $_POST['password'];
            $password2   = $_POST['password2'];
            $email       = $_POST['email'];
            $name        = $_POST['fullname'];
            if(isset($username)){
                $filterUser = filter_var($username,FILTER_SANITIZE_STRING);
                if(strlen($filterUser) < 4){
                    $formErrors[] = 'Username can\'t be less than 4 characters';
                }
            }
            if(isset($password) && isset($password2)){
                if( sha1($password) !== sha1($password2)){
                    $formErrors[] = 'Passwords does not match';
                }
            }
            if(isset($email)){
                $filterEmail = filter_var($email,FILTER_SANITIZE_EMAIL);
                if(filter_var($filterEmail,FILTER_VALIDATE_EMAIL) != true){
                    $formErrors[] = "Your e-mail is not valid";
                }
            }
            if(empty($formErrors)) {

               $check = checkItem("Username", "users", $username);

                if ($check == 1) {
                    $formErrors[] = "Sorry this username is taken";
                } else {
                    $stmt = $con->prepare("INSERT INTO users(Username,Password,Email,FullName,RegStatus,Date) 
                                                VALUES(:zuser,:zpass,:zmail,:zname, 0, now())");
                    $stmt->execute(array(

                        'zuser' => $username,
                        'zpass' => sha1($password),
                        'zmail' => $email,
                        'zname' => $name

                    ));

                    $succMsg = "Your account was created successfully";
                }
            }
        }
    }
?>

    <div class="container login-page">
        <h1 class="text-center">
            <span class="selected" data-class="login">Login</span> |
            <span data-class="signup">SignUp</span>
        </h1>
        <!-- Start Login form -->
        <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="input-container">
                <input  class="form-control" type="text" name="username"
                        autocomplete="off" placeholder="Type your username" required />
            </div>
            <div class="input-container">
                <input class="form-control" type="password" name="password"
                autocomplete="new-password" placeholder="Type your password" required/>
            </div>
            <input class="btn btn-primary btn-block" name="login" type="submit" value="Login"/>
        </form>
        <!-- End Login form -->

        <!-- Start SignUp form -->
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="input-container">
                <input class="form-control" type="text" name="fullname" autocomplete="off" placeholder="Type your Full Name" required/>
            </div>
            <div class="input-container">
                   <input pattern=".{4,8}" title="Username must be > 4 characters" class="form-control"
                          type="text" name="username" autocomplete="off" placeholder="Type your username" required/>
            </div>
            <div class="input-container">
                <input minlength="4" class="form-control" type="password" name="password"
                 autocomplete="off" placeholder="Choose a strong password" required/>
            </div>
            <div class="input-container">
                <input minlength="4" class="form-control" type="password" name="password2"
                autocomplete="off" placeholder="Type your password again" required/>
            </div>
            <div class="input-container">
                <input class="form-control" type="email" name="email" placeholder="Type a valid e-mail" required/>
            </div>
            <input class="btn btn-success btn-block" name="signup" type="submit" value="SignUp"/>
        </form>
        <!-- End SignUp form -->
        <div class="the-errors text-center">
            <?php
                if(!empty($formErrors)) {
                    foreach ($formErrors as $error) {
                        echo '<div class="msg error">'.$error . '</div>';
                    }
                }
                if(isset($succMsg)){
                    echo '<div class="nice-message">'. $succMsg . '</div>';
                }
            ?>
        </div>
    </div>

<?php
    include $tpl .'footer.php';
    ob_end_flush();
?>
