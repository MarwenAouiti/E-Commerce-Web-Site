<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 23/12/2018
 * Time: 15:10
 */
ob_start();
session_start();
$pageTitle = 'Comments';
if(isset($_SESSION['Username'])) {


    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page
    if ($do == 'Manage') {//Manage Members Page

        $stmt = $con->prepare("SELECT comments.*, items.Name AS Item_Name, users.Username AS Member
                                        FROM comments
                                        INNER JOIN  items ON items.Item_ID = comments.item_id
                                        INNER JOIN users ON users.UserID = comments.user_id
                                        ORDER BY c_id DESC ");
        $stmt->execute();
        $comments = $stmt->fetchAll();
        if(!empty($comments)){
        ?>

        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>ID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($comments  as $comment) {
                        echo "<tr>";
                        echo "<td>" .$comment['c_id'] . "</td>";
                        echo "<td>" .$comment['comment'] . "</td>";
                        echo "<td>" .$comment['Item_Name'] . "</td>";
                        echo "<td>". $comment['Member'] ."</td>";
                        echo "<td>". $comment['comment_date'] . "</td>";
                        echo "<td> 
                            <a href='comments.php?do=Edit&comid=". $comment['c_id'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a> 
                            <a href='comments.php?do=Delete&comid=". $comment['c_id'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                        if($comment['status'] == 0) {
                            echo "<a href='comments.php?do=Approve&comid=". $comment['c_id'] ."' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        <?php }else {
            echo '<div class="container">';
            echo '<div class="nice-message">You don\'t have comments to show</div>';
            echo '</div>';
        } ?>

   <?php
    } elseif ($do == 'Edit') {


        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']): 0;


        $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
        $stmt->execute(array($comid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count > 0 ) { ?>
            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid ?>" />
                    <!-- Start Comment Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-4">
                            <textarea class="form-control" name="comment">
                                <?php echo $row['comment']?>
                            </textarea>
                        </div>
                    </div>
                    <!-- End Comment Field -->

                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>
                    <!-- End Submit Field -->
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

        echo "<h1 class='text-center'>Update Comment</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Get variables from the form
            $comid     = $_POST['comid'];
            $comment   = $_POST['comment'];

            $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
            $stmt->execute(array($comment,$comid));

            $Msg ='<div class="alert alert-success">'. $stmt->rowCount() . ' Record Updated</div>';
            redirectHome($Msg);


        } else {
            $Msg =  "<div class='alert alert-danger'>Your are Not allowed</div>";
            redirectHome($Msg);


        }
        echo "</div>";
    } elseif ($do == 'Delete') {

        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']): 0;

        $check = checkItem('c_id','comments',$comid);



        if($check > 0 ) {

            $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
            $stmt->bindParam("zid",$comid);
            $stmt->execute();
            $Msg = '<div class="alert alert-success">'. $stmt->rowCount() . ' Comment(s) Deleted Sucessfully</div>';
            redirectHome($Msg,'back');
        } else {
            $Msg =  "<div class='alert alert-danger'>oops!! No ID</div>";
            redirectHome($Msg);
        }
        echo '</div>';

    } elseif ($do == 'Approve') {

        echo "<h1 class='text-center'>Approve Comments</h1>";
        echo "<div class='container'>";

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']): 0;

        $check = checkItem('c_id','comments',$comid);

        if($check > 0 ) {

            $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
            $stmt->execute(array($comid));
            $Msg = '<div class="alert alert-success">'. $stmt->rowCount() . 'Comment(s) approved sucessfully</div>';
            redirectHome($Msg,'back');
        } else {
            $Msg =  "<div class='alert alert-danger'>oops!! No corresponding Comment</div>";
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