<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 23/11/2018
 * Time: 20:05
 *  ========================================================
 *  == Manage Members page                                ==
 *  == You can Add | Edit | Delete Members from Here      ==
 *  ========================================================
 */
    ob_start();
    session_start();
    $pageTitle = 'Members';
    if(isset($_SESSION['Username'])) {


        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        // Start Manage Page
        if ($do == 'Manage') {//Manage Members Page

            $query = '';
            if(isset($_GET['page']) && $_GET['page'] == 'Pending') {
                $query = 'AND RegStatus = 0';
            }
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC ");
            $stmt->execute();
            $rows = $stmt->fetchAll();
        if(!empty($rows)){
         ?>
            <h1 class="text-center">Manage Members</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-members text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registration Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                            foreach ($rows as $row) {
                                echo "<tr>";
                                    echo "<td>" .$row['UserID'] . "</td>";
                                    echo "<td>";
                                        if(empty($row['avatar'])){
                                            echo "<img src='uploads/avatars/default.png' alt=''/>";
                                        }else {
                                            echo "<img src='uploads/avatars/" .$row['avatar'] . "' alt=''/>";
                                        }
                                    echo "</td>";
                                    echo "<td>" .$row['Username'] . "</td>";
                                    echo "<td>" .$row['Email'] . "</td>";
                                    echo "<td>". $row['FullName'] ."</td>";
                                    echo "<td>". $row['Date'] . "</td>";
                                    echo "<td> 
                                        <a href='members.php?do=Edit&userid=". $row['UserID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a> 
                                        <a href='members.php?do=Delete&userid=". $row['UserID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";

                                    if($row['RegStatus'] == 0) {
                                      echo "<a href='members.php?do=Activate&userid=". $row['UserID'] ."' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
                                    }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
                <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New member</a>
            </div>
         <?php }else {
            echo '<div class="container">';
                echo '<div class="nice-message">You don\'t have members to show</div>';
            echo '</div>';
        } ?>

        <?php } elseif ($do == 'Add') { ?>

            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">

                    <!-- Start Username Field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="username" class="form-control" autocomplete="off"
                                   required="required" placeholder="Username to Login"/>
                        </div>
                    </div>
                    <!-- End Username Field -->

                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="password" name="password" class=" password form-control" autocomplete="new-password"
                                   required="required" placeholder="Choose hard one"/>
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>
                    <!-- End Password Field -->

                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="email" name="email" class="form-control" required="required"
                                   placeholder="name@example.com"/>
                        </div>
                    </div>
                    <!-- End Email Field -->

                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="full" class="form-control" required="required"
                                   placeholder="Your First name and Last name"/>
                        </div>
                    </div>
                    <!-- End Full Name Field -->
                    <!-- Start Avatar Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">User Avatar</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="file" name="avatar" class="form-control" required="required"/>
                        </div>
                    </div>
                    <!-- End Avatar Field -->
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">

                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Member" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>
                    <!-- End Username Field -->

                </form>
            </div>

        <?php

            } elseif($do == 'Insert') {

                //Insert member page
            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Member</h1>";
                echo "<div class='container'>";

                //Upload variable

                $avatarName = $_FILES['avatar']['name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarTmp = $_FILES['avatar']['tmp_name'];
                $avatarType = $_FILES['avatar']['type'];

                // List of allowed files
                $avatarAllowedExtension = array("jpeg","jpg","png","gif");

                //Get Avatar Extension
                $tmp = explode('.',$avatarName);
                $avatarExtension = strtolower(end($tmp));


                //Get variables from the form
                $user   = $_POST['username'];
                $pass   = $_POST['password'];
                $email  = $_POST['email'];
                $name   = $_POST['full'];

                $hashPass = sha1($_POST['password']);

                //$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                // Validate the Form
                $formErrors = array();

                if(strlen($user) < 4 ) {
                    $formErrors[] =  'Username Cant be less than <strong>4 characters</strong>';
                }
                if(strlen($user) > 20 ) {
                    $formErrors[] =  'Username Cant be more than <strong>20 characters</strong>';
                }
                if(empty($user)) {
                    $formErrors[] =  'Username Cant be <strong>empty</strong>';
                }
                if(empty($name)) {
                    $formErrors[] ='Full Name Cant be <strong>empty</strong>';
                }if(empty($pass)) {
                    $formErrors[] ='Password Cant be <strong>empty</strong>';
                }
                if(empty($email)) {
                    $formErrors[] = 'Email Cant be <strong>empty</strong>';
                }
                if(!empty($avatarName) && !in_array($avatarExtension,$avatarAllowedExtension)){
                    $formErrors[] = 'This extension is not <strong>Allowed</strong>';
                }
                if(empty($avatarName)){
                    $formErrors[] = 'Avatar is <strong>required</strong>';
                }
                if($avatarSize > 4194304){
                    $formErrors[] = 'Avatar size isToo Large than <strong>4MB</strong>';
                }
                foreach ($formErrors as $error) {

                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                // If there is no errors Update database with this info
                if(empty($formErrors)) {

                    $avatar = rand(0,100000). '_'. $avatarName;
                    move_uploaded_file($avatarTmp,"uploads\avatars\\" . $avatar);
                    //Check if user exist in the Database
                    $check = checkItem("Username", "users", $user);

                    if ($check == 1) {
                        $theMsg =  '<div class="alert alert-danger">Sorry this username is taken</div>';
                        redirectHome($theMsg,'back');

                    } else {
                        //Insert user info in the database

                        $stmt = $con->prepare("INSERT INTO users(Username,Password,Email,FullName,RegStatus,Date,avatar) 
                                                VALUES(:zuser,:zpass,:zmail,:zname, 1, now(),:zavatar)");
                        $stmt->execute(array(

                            'zuser' => $user,
                            'zpass' => $hashPass,
                            'zmail' => $email,
                            'zname' => $name,
                            'zavatar' => $avatar

                        ));

                        $Msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Inserted</div>';
                        redirectHome($Msg,'back');
                    }
                }
            } else {

                echo "<div class='container'>";

                    $Msg = '<div class="alert alert-danger">Your are not Authorized to be here!!</div>';
                    redirectHome($Msg,'back');

                echo "</div>";
            }
            echo "</div>";



            } elseif ($do == 'Edit') { //Edit page

                // Check if Get userid is numeric and get its value
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']): 0;

                // Select Data from database based on the id
                $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
                $stmt->execute(array($userid));
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

            if($stmt->rowCount() > 0 ) { ?>
                <h1 class="text-center">Edit Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                        <!-- Start Username Field -->

                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="username" class="form-control" value="<?php echo $row['Username']?>" autocomplete="off" required="required"/>
                            </div>
                        </div>
                        <!-- End Username Field -->

                        <!-- Start Password Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="hidden" name="oldpassword" class="form-control" value="<?php echo $row['Password']?>"/>
                                <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave blank if you don't want to change"/>
                            </div>
                        </div>
                        <!-- End Password Field -->

                        <!-- Start Email Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="email" name="email" class="form-control" value="<?php echo $row['Email']?>" required="required"/>
                            </div>
                        </div>
                        <!-- End Email Field -->

                        <!-- Start Full Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Full Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="full" class="form-control" value="<?php echo $row['FullName']?>" required="required"/>
                            </div>
                        </div>
                        <!-- End Full Name Field -->

                        <!-- Start Username Field -->
                        <div class="form-group form-group-lg">

                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save" class="btn btn-primary btn-lg"/>
                            </div>
                        </div>
                        <!-- End Username Field -->

                    </form>
                </div>

       <?php
            } else {
                echo "<div class='container'>";
                
                $Msg = "<div class='alert alert-danger'>There is no such ID</div>";
                redirectHome($Msg);
                echo "</div>";

            }
        } elseif ($do == 'Update') {

            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                //Get variables from the form
                $id     = $_POST['userid'];
                $user   = $_POST['username'];
                $email  = $_POST['email'];
                $name   = $_POST['full'];

                $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                // Validate the Form
                $formErrors = array();

                if(strlen($user) < 4 ) {
                    $formErrors[] =  'Username Cant be less than <strong>4 characters</strong>';
                }
                if(strlen($user) > 20 ) {
                    $formErrors[] =  'Username Cant be more than <strong>20 characters</strong>';
                }
                if(empty($user)) {
                    $formErrors[] =  'Username Cant be <strong>empty</strong>';
                }
                if(empty($name)) {
                    $formErrors[] ='Full Name Cant be <strong>empty</strong>';
                }
                if(empty($email)) {
                    $formErrors[] = 'Email Cant be <strong>empty</strong>';
                }
                foreach ($formErrors as $error) {

                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                // If there is no errors Update database with this info
                if(empty($formErrors)) {

                    $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                    $stmt2->execute(array($user, $id));

                    $count = $stmt2->rowCount();
                    if($count == 1){
                        $theMsg =  '<div class="alert alert-danger">Sorry this username is taken</div>';
                        redirectHome($theMsg,'back');
                    }else{
                        $stmt = $con->prepare("UPDATE users SET Username = ?,Email = ?, FullName = ?,Password = ? WHERE UserID = ?");
                        $stmt->execute(array($user,$email,$name,$pass,$id));

                        $Msg ='<div class="alert alert-success">'. $stmt->rowCount() . ' Record Updated</div>';
                        redirectHome($Msg,'back');
                    }
                }

            } else {
                $Msg =  "<div class='alert alert-danger'>Your are Not allowed</div>";
                redirectHome($Msg);


            }
                echo "</div>";
        } elseif ($do == 'Delete') { //Delete Member Page

            echo "<h1 class='text-center'>Delete Member</h1>";
            echo "<div class='container'>";
            // Check if Get userid is numeric and get its value
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']): 0;

            // Select Data from database based on the id
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
            $stmt->execute(array($userid));

            $count = $stmt->rowCount();



            if($stmt->rowCount() > 0 ) {

                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
                $stmt->bindParam("zuser",$userid);
                $stmt->execute();
                $Msg = '<div class="alert alert-success">'. $stmt->rowCount() . ' User was Deleted Sucessfully</div>';
                redirectHome($Msg,'back');
            } else {
                $Msg =  "<div class='alert alert-danger'>oops!! No ID</div>";
                redirectHome($Msg);
            }
                echo '</div>';

        } elseif ($do == 'Activate') {

            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";
            // Check if Get userid is numeric and get its value
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']): 0;

            // Select Data from database based on the id
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
            $stmt->execute(array($userid));

            $count = $stmt->rowCount();



            if($stmt->rowCount() > 0 ) {

                $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                $stmt->execute(array($userid));
                $Msg = '<div class="alert alert-success">'. $stmt->rowCount() . ' user(s) Activated Sucessfully</div>';
                redirectHome($Msg);
            } else {
                $Msg =  "<div class='alert alert-danger'>oops!! No corresponding user</div>";
                redirectHome($Msg);
            }
            echo '</div>';
        }

        include $tpl . 'footer.php';

    } else {

        header('Location: index.php');
        exit();
    }
ob_end_flush();