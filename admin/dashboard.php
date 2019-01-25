<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 22/11/2018
 * Time: 19:44
 */
ob_start(); //Output buffering start :: Store all your output except the header

session_start();

if(isset($_SESSION['Username'])) {

    $pageTitle = 'Dashboard';
    include 'init.php';

    /* Start Dashnoard page */
    $numUsers = 5;
    $latestUsers = getLatest("*", "users","UserID",$numUsers); //latest users array

    $numItems = 5;
    $latestItems = getLatest("*","items","Item_ID",$numItems);

    $numComments = 2;

    ?>
        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                      <i class="fa fa-users"></i>
                      <div class="info">
                          Total Members
                          <span><a href="members.php"><?php echo countItems('UserID','users')?></a></span>
                      </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending members
                            <span><a href="members.php?do=Manage&page=Pending">
                                <?php echo checkItem("RegStatus","users",0);?>
                            </a>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                       <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span>
                            <a href="items.php"><?php echo countItems('Item_ID','items')?></a>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comment"></i>
                        <div class="info">
                            Total Comments
                            <span>
                                <a href="comments.php"><?php echo countItems('c_id','comments')?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i> Latest <?php echo $numUsers;?> Registered users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                if(!empty($latestUsers)) {
                                    foreach ($latestUsers as $user) {
                                            echo '<li>';
                                                echo $user['Username'];
                                                echo '<a href="members.php?do=Edit&userid='.$user['UserID'].'">';
                                                echo '<span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit';
                                                if($user['RegStatus'] == 0) {
                                                    echo "<a href='members.php?do=Activate&userid=". $user['UserID'] ."' class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Activate</a>";
                                                }
                                                echo '</span>';
                                                echo '</a>';
                                            echo '</li>';
                                        }
                                }
                                else{
                                        echo 'There is no Record';
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest <?php echo $numItems;?> Added Items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                if(!empty($latestItems)){
                                    foreach ($latestItems as $item) {
                                        echo '<li>';
                                        echo $item['Name'];
                                        echo '<a href="items.php?do=Edit&itemid='.$item['Item_ID'].'">';
                                        echo '<span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit';
                                        if($item['Approve'] == 0) {
                                            echo "<a href='items.php?do=Approve&itemid=". $item['Item_ID'] ."' class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Approve</a>";
                                        }
                                        echo '</span>';
                                        echo '</a>';
                                        echo '</li>';
                                    }
                                }else{
                                    echo 'There is no Items to show';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Start Latest Comments Page -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-comments-o"></i> Latest <?php echo $numComments?> Comments
                                <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                            </div>
                            <div class="panel-body">
                                <?php
                                $stmt = $con->prepare("SELECT comments.*, users.Username AS Member
                                                                FROM comments
                                                                INNER JOIN users ON users.UserID = comments.user_id 
                                                                ORDER BY c_id DESC 
                                                                LIMIT $numComments");
                                $stmt->execute();
                                $comments = $stmt->fetchAll();
                            if(!empty($comments)){
                                foreach ($comments as $comment) {
                                    echo '<div class="comment-box">';
                                        echo '<span class="member-n">'. $comment['Member'].'</span>';
                                        echo '<p class="member-c">'. $comment['comment'].'</p>';
                                    echo '</div>';
                                }
                            }else {
                                echo 'There\'s no comments to show';
                            }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- End Comments Page -->
            </div>
        </div>

    <?php

    /* End Dashnoard page */

    include $tpl . 'footer.php';

} else {

    header('Location: index.php');
    exit();
}

ob_end_flush();
?>