<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 27/12/2018
 * Time: 16:20
 */

    session_start();
    $pageTitle = "Create New Item";
    include 'init.php';
    if(isset($_SESSION['Username'])) {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $formErrors = array();
            $name       = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
            $desc       = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
            $price      = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
            $status     = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
            $category   = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
            $tags       = filter_var($_POST['tags'],FILTER_SANITIZE_STRING);

            if(strlen($name) < 4){
                $formErrors[] = 'Item name should be more than 4 chars';
            }
            if(strlen($country) < 2 ){
                $formErrors[] = 'Please enter a valid country name';
            }
            if(empty($price)){
                $formErrors[] = 'Please enter a value!!1';
            }
            if(empty($status)){
                $formErrors[] = 'Please enter a value!!1';
            }
            if(empty($category)){
                $formErrors[] = 'Please enter a value!!1';
            }
            if(empty($formErrors)) {



                $stmt = $con->prepare("INSERT INTO 
                                  items(Name,Description ,Price,Country_Made,Status,Add_Date,Cat_ID,Member_ID,tags)
                                  VALUES(:zname,:zdesc,:zprice,:zcountry, :zstatus, now(),:zcat, :zmember,:ztags)");
                $stmt->execute(array(

                    'zname'     => $name,
                    'zdesc'     => $desc,
                    'zprice'    => $price,
                    'zcountry'  => $country,
                    'zstatus'   => $status,
                    'zcat'      => $category,
                    'zmember'   => $_SESSION['uid'],
                    'ztags'     => $tags
                ));

                if($stmt) {
                    $succMsg = 'Item Added';
                }
            }
        }
?>
        <h1 class="text-center"><?php echo $pageTitle?></h1>
        <div class="create-ad block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo $pageTitle?></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

                                    <!-- Start Name Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input pattern=".{4,}" title="This field requires at least 4 characters"
                                                   type="text" name="name" class="form-control live"
                                                   required="required" placeholder="Name of the Item"
                                                    data-class=".live-title"/>
                                        </div>
                                    </div>
                                    <!-- End Name Field -->

                                    <!-- Start Description Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Description</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input type="text" name="description" class="form-control live"
                                                   required="required" placeholder="Description of the Item"
                                                   data-class=".live-desc"/>
                                        </div>
                                    </div>
                                    <!-- End Description Field -->

                                    <!-- Start Price Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Price</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input type="text" name="price" class="form-control live"
                                                   required="required" placeholder="Price of the Item"
                                                   data-class=".live-price"/>
                                        </div>
                                    </div>
                                    <!-- End Price Field -->

                                    <!-- Start Country Made Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Country</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input type="text" name="country" class="form-control"
                                                   required="required" placeholder="Country of manufacture"/>
                                        </div>
                                    </div>
                                    <!-- End Country Made Field -->

                                    <!-- Start Status Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Status</label>
                                        <div class="col-sm-10 col-md-9">
                                            <select name="status" required>
                                                <option value="0">...</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Old</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- End Status Field -->

                                    <!-- Start Categories Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Category</label>
                                        <div class="col-sm-10 col-md-9">
                                            <select name="category" required>
                                                <option value="0">...</option>
                                                <?php
                                                $cats = getAllFrom('*','categories','','','ID');
                                                foreach ($cats as $cat) {
                                                    echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- End Categories Field -->
                                    <!-- Start Tags Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Tags</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input type="text" name="tags" class="form-control"
                                                   placeholder="Seperate Tags with comma (,)"/>
                                        </div>
                                    </div>
                                    <!-- End Tags Field -->
                                    <!-- Start Submit Field -->
                                    <div class="form-group form-group-lg">

                                        <div class="col-sm-offset-3 col-sm-9">
                                            <input type="submit" value="Add Item" class="btn btn-primary btn-sm"/>
                                        </div>
                                    </div>
                                    <!-- End Submit Field -->

                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="thumbnail item-box live-preview">
                                    <span class="price-tag">
                                        $<span class="live-price"></span>
                                    </span>
                                    <img class="img-responsive" src="Eren.jpg" alt="" />
                                    <div class="caption">
                                        <h3 class="live-title">nom</h3>
                                        <p  class="live-desc">write any thing</p>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <!-- Error -->
                        <?php
                            if(!empty($formErrors)){
                                foreach ($formErrors as $error) {
                                    echo '<div class="alert alert-danger">'. $error . '</div>';
                                }
                            }
                        if(isset($succMsg)){
                            echo '<div class="alert alert-success">'. $succMsg . '</div>';
                        }
                        ?>
                        <!-- End Error -->
                    </div>
                </div>
            </div>
        </div>

<?php
    } else {
        header('Location:login.php');
        exit();
    }
    include $tpl. 'footer.php';
?>