<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 21/12/2018
 * Time: 14:28
 *  ========================================================
 *  == Manage Items page                                  ==
 *  == You can Add | Edit | Delete Items from Here        ==
 *  ========================================================
 */
ob_start();

session_start();

$pageTitle = 'Items';

if(isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){


        $stmt = $con->prepare("SELECT items.*, categories.Name AS category_name, users.Username FROM items
                                         INNER JOIN categories ON categories.ID = items.Cat_ID
                                         INNER JOIN users ON users.UserID = items.Member_ID
                                         ORDER BY Item_ID DESC");
        $stmt->execute();
        $items = $stmt->fetchAll();
        if(!empty($items)) {
        ?>

    <h1 class="text-center">Manage Items</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                    <td>#ID</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Adding Date</td>
                    <td>Category</td>
                    <td>Username</td>
                    <td>Control</td>
                </tr>
                <?php
                foreach ($items as $item) {
                    echo "<tr>";
                    echo "<td>" .$item['Item_ID'] . "</td>";
                    echo "<td>" .$item['Name'] . "</td>";
                    echo "<td>" .$item['Description'] . "</td>";
                    echo "<td>". $item['Price'] ."</td>";
                    echo "<td>". $item['Add_Date'] . "</td>";
                    echo "<td>". $item['category_name'] . "</td>";
                    echo "<td>". $item['Username'] . "</td>";
                    echo "<td> 
                        <a href='items.php?do=Edit&itemid=". $item['Item_ID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a> 
                        <a href='items.php?do=Delete&itemid=". $item['Item_ID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                    if($item['Approve'] == 0) {
                        echo "<a 
                                href='items.php?do=Approve&itemid=". $item['Item_ID'] ."'
                                 class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
        <a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>
    </div>
        <?php }else {
            echo '<div class="container">';
            echo '<div class="nice-message">You don\'t have members to show</div>';
            echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary">';
                    echo '<i class="fa fa-plus"></i> New Item';
            echo '</a>';
            echo '</div>';
        } ?>

      <?php  }else if ($do == 'Add'){ ?>

        <h1 class="text-center">Add New Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">

                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="name" class="form-control"
                               required="required" placeholder="Name of the Item"/>
                    </div>
                </div>
                <!-- End Name Field -->

                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="description" class="form-control"
                               required="required" placeholder="Description of the Item"/>
                    </div>
                </div>
                <!-- End Description Field -->

                <!-- Start Price Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="price" class="form-control"
                               required="required" placeholder="Price of the Item"/>
                    </div>
                </div>
                <!-- End Price Field -->

                <!-- Start Country Made Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="country" class="form-control"
                               required="required" placeholder="Country of manufacture"/>
                    </div>
                </div>
                <!-- End Country Made Field -->

                <!-- Start Status Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-4">
                        <select name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Old</option>
                        </select>
                    </div>
                </div>
                <!-- End Status Field -->

                <!-- Start Members Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-4">
                        <select name="member">
                            <option value="0">...</option>
                            <?php
                                $allMembers = getAllFrom("*","users","","","UserID");
                                foreach ($allMembers as $user) {
                                    echo "<option value='".$user['UserID']."'>".$user['Username']."</option>";
                                }
                            ?>
                         </select>
                    </div>
                </div>
                <!-- End Members Field -->

                <!-- Start Categories Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-4">
                        <select name="category">
                            <option value="0">...</option>
                            <?php
                                $allCats = getAllFrom("*","categories","where parent = 0","","ID");
                                foreach ($allCats as $cat) {
                                    echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                                    $childCats = getAllFrom("*","categories","where parent ={$cat['ID']}","","ID");
                                    foreach ($childCats as $child) {
                                        echo "<option value='".$child['ID']."'>--- ".$child['Name']."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Categories Field -->
                <!-- Start Tags Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="tags" class="form-control"
                            placeholder="Seperate Tags with comma (,)"/>
                    </div>
                </div>
                <!-- End Tags Field -->
                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">

                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Item" class="btn btn-primary btn-sm"/>
                    </div>
                </div>
                <!-- End Submit Field -->

            </form>
        </div>

        <?php


    }else if ($do == 'Insert'){

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Insert Item</h1>";
            echo "<div class='container'>";
            //Get variables from the form

            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $status     = $_POST['status'];
            $member     = $_POST['member'];
            $cat        = $_POST['category'];
            $tags       = $_POST['tags'];


            // Validate the Form
            $formErrors = array();

            if(empty($name)) {
                $formErrors[] =  'Item name Can\'t be <strong>empty</strong>';
            }
            if(empty($desc)) {
                $formErrors[] =  'Item Description Can\'t be <strong>empty</strong>';
            }
            if(empty($price)) {
                $formErrors[] =  'Price  Can\'t be <strong>empty</strong>';
            }
            if(empty($country)) {
                $formErrors[] =  'Country Can\'t be <strong>empty</strong>';
            }
            if($status == 0) {
                $formErrors[] =  'You must choose the <strong>Status</strong>';
            }
            if($member == 0) {
                $formErrors[] =  'You must choose the <strong>member</strong>';
            }
            if($cat == 0) {
                $formErrors[] =  'You must choose the <strong>category</strong>';
            }

            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            if(empty($formErrors)) {



            $stmt = $con->prepare("INSERT INTO items(Name,Description ,Price,Country_Made,Status,Add_Date,Cat_ID,Member_ID,tags)
                                  VALUES(:zname,:zdesc,:zprice,:zcountry, :zstatus, now(),:zcat, :zmember,:ztags)");
            $stmt->execute(array(

                'zname'     => $name,
                'zdesc'     => $desc,
                'zprice'    => $price,
                'zcountry'  => $country,
                'zstatus'   => $status,
                'zcat'      => $cat,
                'zmember'   => $member,
                'ztags'     => $tags
            ));

            $Msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' is the number of Items Inserted</div>';
            redirectHome($Msg,'back');
         }
        } else {

            echo "<div class='container'>";

            $Msg = '<div class="alert alert-danger">Your are not Authorized to be here!!</div>';
            redirectHome($Msg);

            echo "</div>";
        }
        echo "</div>";


    }else if ($do == 'Edit'){

        // Check if Get itemid is numeric and get its value
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']): 0;

        // Select Data from database based on the id
        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
        $stmt->execute(array($itemid));
        $item = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count > 0 ) { ?>
            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="itemid" value="<?php echo $itemid?>"/>
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control"
                                   required="required" placeholder="Name of the Item" value="<?php echo $item['Name']?>"/>
                        </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control"
                                   required="required" placeholder="Description of the Item" value="<?php echo $item['Description']?>"/>
                        </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="price" class="form-control"
                                   required="required" placeholder="Price of the Item" value="<?php echo $item['Price']?>"/>
                        </div>
                    </div>
                    <!-- End Price Field -->

                    <!-- Start Country Made Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="country" class="form-control"
                                   required="required" placeholder="Country of manufacture" value="<?php echo $item['Country_Made']?>"/>
                        </div>
                    </div>
                    <!-- End Country Made Field -->

                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="status">
                                <option value="0">...</option>
                                <option value="1" <?php if($item['Status'] == 1) {echo 'selected';}?>>New</option>
                                <option value="2" <?php if($item['Status'] == 2) {echo 'selected';}?>>Like New</option>
                                <option value="3" <?php if($item['Status'] == 3) {echo 'selected';}?>>Used</option>
                                <option value="4" <?php if($item['Status'] == 4) {echo 'selected';}?>>Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->

                    <!-- Start Members Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="member">
                                <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {
                                    echo "<option value='".$user['UserID']."'";
                                    if($item['Member_ID'] == $user['UserID']) {echo 'selected';}
                                    echo ">".$user['Username']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->

                    <!-- Start Categories Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="category">
                                <?php
                                $stmt = $con->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach ($cats as $cat) {
                                    echo "<option value='".$cat['ID']."'";
                                    if($item['Cat_ID'] == $cat['ID']) {echo 'selected';}
                                    echo ">".$cat['Name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field -->
                    <!-- Start Tags Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="tags" class="form-control"
                                   placeholder="Seperate Tags with comma (,)"
                                   value="<?php echo $item['tags']?>"/>
                        </div>
                    </div>
                    <!-- End Tags Field -->
                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">

                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save Item" class="btn btn-primary btn-sm"/>
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
                <?php
                $stmt = $con->prepare("SELECT comments.*, users.Username AS Member
                FROM comments
                INNER JOIN users ON users.UserID = comments.user_id
                WHERE item_id = ?");
                $stmt->execute(array($itemid));
                $rows = $stmt->fetchAll();

                if(!empty($rows)) {
                ?>

                <h1 class="text-center">Manage [ <?php echo $item['Name']?> ] Comments</h1>
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>Comment</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" .$row['comment'] . "</td>";
                            echo "<td>". $row['Member'] ."</td>";
                            echo "<td>". $row['comment_date'] . "</td>";
                            echo "<td> 
                        <a href='comments.php?do=Edit&comid=". $row['c_id'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a> 
                        <a href='comments.php?do=Delete&comid=". $row['c_id'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                            if($row['status'] == 0) {
                                echo "<a href='comments.php?do=Approve&comid=". $row['c_id'] ."' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
                <?php } ?>
            </div>
            <?php
        } else {
            echo "<div class='container'>";

            $Msg = "<div class='alert alert-danger'>There is no such ID</div>";
            redirectHome($Msg);
            echo "</div>";

        }

    }else if ($do == 'Update'){

        echo "<h1 class='text-center'>Update Item</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Get variables from the form
            $id       = $_POST['itemid'];
            $name     = $_POST['name'];
            $desc     = $_POST['description'];
            $price    = $_POST['price'];
            $country  = $_POST['country'];
            $status   = $_POST['status'];
            $cat      = $_POST['category'];
            $member   = $_POST['member'];
            $tags     = $_POST['tags'];

            // Validate the Form
            $formErrors = array();

            $formErrors = array();

            if(empty($name)) {
                $formErrors[] =  'Item name Can\'t be <strong>empty</strong>';
            }
            if(empty($desc)) {
                $formErrors[] =  'Item Description Can\'t be <strong>empty</strong>';
            }
            if(empty($price)) {
                $formErrors[] =  'Price  Can\'t be <strong>empty</strong>';
            }
            if(empty($country)) {
                $formErrors[] =  'Country Can\'t be <strong>empty</strong>';
            }
            if($status == 0) {
                $formErrors[] =  'You must choose the <strong>Status</strong>';
            }
            if($member == 0) {
                $formErrors[] =  'You must choose the <strong>member</strong>';
            }
            if($cat == 0) {
                $formErrors[] =  'You must choose the <strong>category</strong>';
            }

            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger">' . $error . '</div>';
            }


            // If there is no errors Update database with this info
            if(empty($formErrors)) {
                $stmt = $con->prepare("UPDATE items SET Name= ?,Description = ?, Price = ?,Country_Made = ?,
            Status = ?, Cat_ID = ?, Member_ID = ?, tags = ? WHERE Item_ID = ?");
                $stmt->execute(array($name,$desc,$price,$country,$status,$cat,$member,$tags,$id));

                $Msg ='<div class="alert alert-success">'. $stmt->rowCount() . ' Item(s) Updated</div>';
                redirectHome($Msg,'back');
            }

        } else {
            $Msg =  "<div class='alert alert-danger'>Your are Not allowed</div>";
            redirectHome($Msg);


        }
        echo "</div>";

    }else if($do == 'Delete') {

        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";
        // Check if Get Item ID is numeric and get its value
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']): 0;

        $check = checkItem('Item_ID','items',$itemid);

        if($check > 0 ) {

            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
            $stmt->bindParam("zid",$itemid);
            $stmt->execute();
            $Msg = '<div class="alert alert-success">'. $stmt->rowCount() . ' Item(s) Deleted Sucessfully</div>';
            redirectHome($Msg,'back');
        } else {
            $Msg =  "<div class='alert alert-danger'>oops!! No ID</div>";
            redirectHome($Msg);
        }
        echo '</div>';


    }else if ($do == 'Approve') {

        echo "<h1 class='text-center'>Approve Items</h1>";
        echo "<div class='container'>";
        // Check if Get userid is numeric and get its value
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']): 0;

        $check = checkItem('Item_ID','items',$itemid);


        if($check > 0 ) {

            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
            $stmt->execute(array($itemid));
            $Msg = '<div class="alert alert-success">'. $stmt->rowCount() . ' Item(s) Approved Sucessfully</div>';
            redirectHome($Msg,'back');
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

?>