<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 28/12/2018
 * Time: 18:04
 */
    ob_start();
    session_start();
    $pageTitle = "Show Items";
    include 'init.php';
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']): 0;


    $stmt = $con->prepare("SELECT items.*, categories.Name as category_name,
                                      users.Username
                                     FROM items 
                                     INNER JOIN categories 
                                     ON categories.ID = items.Cat_ID
                                     INNER JOIN users
                                     ON users.UserID = items.Member_ID
                                     WHERE Item_ID = ? AND Approve = 1");
    $stmt->execute(array($itemid));
    $count = $stmt->rowCount();

    if($count > 0) {

        $item = $stmt->fetch();

?>
    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <img class="img-responsive img-thumbnail center-block" src="Eren.jpg" alt="" />
                </div>
                <div class="col-md-9 item-info">
                    <h2><?php echo $item['Name']?></h2>
                    <p><?php echo $item['Description']?></p>
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Added Date</span>: <?php echo $item['Add_Date']?>
                        </li>
                        <li>
                            <i class="fa fa-money fa-fw"></i>
                            <span>Price</span>: <?php echo $item['Price']?>
                        </li>
                        <li>
                            <i class="fa fa-building fa-fw"></i>
                            <span>Made In</span>: <?php echo $item['Country_Made']?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Category</span>:<a href="categories.php?pageid=<?php echo $item['Cat_ID']?>"> <?php echo $item['category_name']?></a>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>Added by</span>: <a href="#"><?php echo $item['Username']?></a>
                        </li>
                        <li class="tags-items">
                            <i class="fa fa-user fa-fw"></i>
                            <span>Tags</span>:
                            <?php
                                $allTags = explode(",",$item['tags']);
                            foreach ($allTags as $tag) {
                                $tag = str_replace(' ','',$tag);
                                $lowerTag = strtolower($tag);
                                if(!empty($tag)) {
                                    echo "<a href='tags.php?name={$lowerTag}'>" . $tag . "</a>";
                                }
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="custom-hr">
            <?php if(isset($_SESSION['Username'])) { ?>
            <!-- Start Add comment -->
                <div class="row">
                    <div class="col-md-offset-3">
                        <div class="add-comment">
                            <h3>Add your comment</h3>
                            <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid='. $item['Item_ID']?>" method="POST">
                                <textarea name="comment" required></textarea>
                                <input type="submit" class="btn btn-primary" value="Add Comment">
                            </form>
                            <?php
                                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                                    $comment = filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
                                    $userid  = $_SESSION['uid'];
                                    $itemid  = $item['Item_ID'];

                                    if(!empty($comment)){
                                        $stmt = $con->prepare("INSERT INTO 
                                                comments(comment, status,comment_date,item_id,user_id)
                                                VALUES(:zcomment,0,NOW(),:zitemid, :zuserid)");
                                        $stmt->execute(array(
                                                'zcomment' => $comment,
                                                'zitemid'  => $itemid,
                                                'zuserid'  => $userid
                                        ));
                                        if($stmt){
                                            echo '<div class="alert alert-success">Comment Added</div>';
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            <!-- End Add comment -->
            <?php } else  {
                echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> to add a comment';
            }?>
            <hr class="custom-hr">
                <?php
                $stmt = $con->prepare("SELECT comments.*, users.Username AS Member
                                                FROM comments
                                                INNER JOIN users ON users.UserID = comments.user_id
                                                WHERE item_id = ?
                                                AND status = 1
                                                ORDER BY c_id DESC ");
                $stmt->execute(array($item['Item_ID']));
                $comments = $stmt->fetchAll();
                ?>
              <?php
              foreach ($comments as $comment) {?>
                  <div class="comment-box">
                      <div class="row">
                          <div class="col-sm-2 text-center">
                              <img class="img-responsive img-thumbnail img-circle center-block" src="avatar.png" alt=""/>
                              <?php echo $comment['Member']?>
                          </div>
                          <div class=col-sm-10">
                              <span class="mycom"><?php echo $comment['comment']?></span>
                          </div>
                      </div>
                  </div>
                  <hr class="custom-hr">
            <?php  } ?>

        </div>

<?php
    } else {
        echo '<div class="alert alert-danger">This item is not approved to be publicly posted yet !!</div>';
    }
    include $tpl. 'footer.php';
    ob_end_flush();
?>