<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 19/12/2018
 * Time: 20:47
 */
/* ====================
     Categories Page
   ====================
 * */
ob_start();

session_start();

$pageTitle = 'Categories';

if(isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){

        $sort = "ASC";
        $sort_array = array('ASC','DESC');

        if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){

            $sort = $_GET['sort'];
        }

        $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");
        $stmt2->execute();

        $cats = $stmt2->fetchAll(); ?>

        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i> Manage Categories
                    <div class="option pull-right">
                        <i class="fa fa-sort"></i> Ordering:[
                        <a class="<?php if($sort == 'ASC') {echo 'active';}?>" href="?sort=ASC">Asc</a> |
                        <a class="<?php if($sort == 'DESC') {echo 'active';}?>" href="?sort=DESC">Desc</a> ]
                        <i class="fa fa-eye"></i> View: [
                        <span class="active" data-view="full">Full</span> |
                        <span data-view="classic">Classic</span> ]
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($cats as $cat) {
                            echo "<div class='cat'>";
                                echo "<div class='hidden-buttons'>";
                                    echo "<a href='categories.php?do=Edit&catid=". $cat['ID'] ."' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                    echo "<a href='categories.php?do=Delete&catid=". $cat['ID'] ."' class=' confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                                echo "</div>";
                                echo '<h3>'. $cat['Name'] . '</h3>';
                                echo "<div class='full-view'>";
                                    echo '<p>'; if($cat['Description'] == ''){echo "No available description";} else {echo $cat['Description'];} echo '</p>';
                                    if($cat['Visibility'] == 1){echo '<span class="visibility"><i class="fa fa-eye"></i>Hidden</span>';}
                                    if($cat['Allow_Comment'] == 1){echo '<span class="commenting"><i class="fa fa-close"></i>Comments Disabled</span>';}
                                    if($cat['Allow_Ads'] == 1){echo '<span class="advertises"><i class="fa fa-close"></i>Ads Disabled</span>';}
                                    $childCats = getAllFrom("*","categories","where parent ={$cat['ID']}","","ID","ASC");
                                    if(!empty($childCats)){
                                        echo '<h4 class="child-head">Sub categories</h4>';
                                        echo "<ul class='list-unstyled child-cats'>";
                                        foreach ($childCats as $c) {
                                            echo "<li class='child-link'>
                                                    <a href='categories.php?do=Edit&catid=". $c['ID'] ."' class='child-link'>". $c['Name']. "</a>
                                                    <a href='categories.php?do=Delete&catid=". $c['ID'] ."' class='show-delete confirm'>Delete</a>
                                                  </li>";
                                        }
                                        echo "</ul>";
                                    }
                                echo "</div>";
                            echo "</div>";
                            echo "<hr>";
                        }
                    ?>
                </div>
            </div>
            <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
        </div>

        <?php
    }else if ($do == 'Add'){ ?>

        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">

                <!-- Start Name Field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="name" class="form-control" autocomplete="off"
                               required="required" placeholder="Name of the category"/>
                    </div>
                </div>
                <!-- End Name Field -->

                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="description" class="form-control"
                               placeholder="Describe the category"/>
                    </div>
                </div>
                <!-- End Description Field -->

                <!-- Start Ordering Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="ordering" class="form-control"
                               placeholder="Number to classify the category"/>
                    </div>
                </div>
                <!-- End Ordering Field -->

                <!-- Start Parent Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category type</label>
                    <div class="col-sm-10 col-md-4">
                      <select name="parent">
                          <option value="0">None</option>
                          <?php
                            $allCats = getAllFrom("*","categories","where parent = 0","","ID","ASC");
                          foreach ($allCats as $cat) {
                              echo "<option value='" . $cat['ID']. "'>" .$cat['Name'] . "</option>";
                            }
                          ?>
                      </select>
                    </div>
                </div>
                <!-- End Parent Field -->

                <!-- Start Visibility Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visible</label>
                    <div class="col-sm-10 col-md-4">
                       <div>
                           <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                            <label for="vis-yes">Yes</label>
                       </div>
                        <div>
                           <input id="vis-no" type="radio" name="visibility" value="1" />
                            <label for="vis-no">No</label>
                       </div>
                    </div>
                </div>
                <!-- End Visibility Field -->

                <!-- Start Commenting Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Commenting</label>
                    <div class="col-sm-10 col-md-4">
                        <div>
                            <input id="com-yes" type="radio" name="commenting" value="0" checked />
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="commenting" value="1" />
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Commenting Field -->

                <!-- Start Ads Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10 col-md-4">
                        <div>
                            <input id="ads-yes" type="radio" name="ads" value="0" checked />
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ads-no" type="radio" name="ads" value="1" />
                            <label for="ads-no">No</label>
                        </div>
                    </div>
                </div>
                <!-- End Ads Field -->

                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">

                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-lg"/>
                    </div>
                </div>
                <!-- End Submit Field -->

            </form>
        </div>



        <?php

    }else if ($do == 'Insert'){

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Insert Category</h1>";
            echo "<div class='container'>";
            //Get variables from the form

            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $parent     = $_POST['parent'];
            $order      = $_POST['ordering'];
            $visible    = $_POST['visibility'];
            $comment    = $_POST['commenting'];
            $ads        = $_POST['ads'];

            //Check if Category exist in the Database

            $check = checkItem("Name", "categories", $name);

            if ($check == 1) {
                $theMsg =  '<div class="alert alert-danger">Sorry this category already exists!!</div>';
                redirectHome($theMsg,'back');
            } else {
                //Insert category info in the database

                $stmt = $con->prepare("INSERT INTO categories(Name,Description,parent,Ordering,Visibility,Allow_Comment,Allow_Ads) 
                                            VALUES(:zname,:zdesc,:zparent,:zorder,:zvisible, :zcomment, :zads)");
                $stmt->execute(array(

                    'zname'    => $name,
                    'zdesc'    => $desc,
                    'zparent'  => $parent,
                    'zorder'   => $order,
                    'zvisible' => $visible,
                    'zcomment' => $comment,
                    'zads'     => $ads

                ));

                $Msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' New Category Inserted</div>';
                redirectHome($Msg,'back');
            }

        } else {

            echo "<div class='container'>";

            $Msg = '<div class="alert alert-danger">Your are not Authorized to be here!!</div>';
            redirectHome($Msg,'back');

            echo "</div>";
        }
        echo "</div>";


    }else if ($do == 'Edit'){

        // Check if Get catid is numeric and get its value

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']): 0;

        // Select Data from database based on the id
        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
        $stmt->execute(array($catid));
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();

        if($stmt->rowCount() > 0 ) { ?>

            <h1 class="text-center">Edit Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="catid" value="<?php echo $catid;?>"/>
                    <!-- Start Name Field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control"
                                   required="required" placeholder="Name of the category" value="<?php echo $cat['Name'];?>"/>
                        </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control"
                                   placeholder="Describe the category" value="<?php echo $cat['Description'];?>"/>
                        </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Ordering Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="ordering" class="form-control"
                                   placeholder="Number to classify the category" value="<?php echo $cat['Ordering'];?>"/>
                        </div>
                    </div>
                    <!-- End Ordering Field -->
                    <!-- Start Parent Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category type</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
                                $allCats = getAllFrom("*","categories","where parent = 0","","ID","ASC");
                                foreach ($allCats as $c) {
                                    echo "<option value='" . $c['ID']. "'";
                                    if($cat['parent'] == $c['ID']) {echo 'selected';}
                                    echo">" .$c['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Parent Field -->
                    <!-- Start Visibility Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visible</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0"
                                <?php if($cat['Visibility'] == 0) echo "checked";?> />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1"
                                    <?php if($cat['Visibility'] == 1) echo "checked";?>  />
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visibility Field -->

                    <!-- Start Commenting Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0"
                                    <?php if($cat['Allow_Comment'] == 0) echo "checked";?>/>
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1"
                                    <?php if($cat['Allow_Comment'] == 1) echo "checked";?>/>
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Commenting Field -->

                    <!-- Start Ads Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0"
                                    <?php if($cat['Allow_Ads'] == 0) echo "checked";?>/>
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1"
                                    <?php if($cat['Allow_Ads'] == 1) echo "checked";?>/>
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads Field -->

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

    }else if ($do == 'Update'){

        echo "<h1 class='text-center'>Update Category</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Get variables from the form
            $id         = $_POST['catid'];
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $order      = $_POST['ordering'];
            $parent     = $_POST['parent'];
            $visible    = $_POST['visibility'];
            $comment    = $_POST['commenting'];
            $ads        = $_POST['ads'];

            $stmt = $con->prepare("UPDATE categories SET name = ?,description = ?, ordering = ?,parent = ?,
                                              Visibility = ?,Allow_Comment = ?,
                                              Allow_Ads = ? WHERE ID = ?");
            $stmt->execute(array($name,$desc,$order,$parent,$visible,$comment,$ads,$id));

            $Msg ='<div class="alert alert-success">'. $stmt->rowCount() . ' is the number of categories Updated</div>';
            redirectHome($Msg,'back');

        } else {
            $Msg =  "<div class='alert alert-danger'>Your are Not allowed</div>";
            redirectHome($Msg);


        }
        echo "</div>";


    }else if($do == 'Delete') {

        echo "<h1 class='text-center'>Delete Category</h1>";
        echo "<div class='container'>";
        // Check if Get userid is numeric and get its value
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']): 0;

        $check = checkItem('ID','categories',$catid);



        if($check > 0 ) {

            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
            $stmt->bindParam(":zid",$catid);
            $stmt->execute();
            $Msg = '<div class="alert alert-success">'. $stmt->rowCount() . ' Category was Deleted Sucessfully</div>';
            redirectHome($Msg,'back');
        } else {
            $Msg =  "<div class='alert alert-danger'>oops!! No ID</div>";
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