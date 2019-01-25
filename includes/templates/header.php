<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title><?php getTitle() ?></title>
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css"/>
        <link rel="stylesheet" href="<?php echo $css; ?>fontawesome.min.css"/>
        <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css"/>
        <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css"/>
        <link rel="stylesheet" href="<?php echo $css; ?>front.css"/>
        <script src="<?php echo $js; ?>usefontawesome.js"></script>
    </head>
    <body>
    <div class="upper-bar">
        <div class="container">
            <?php
                if(isset($_SESSION['Username'])){ ?>
                    <img class="my-avatar img-thumbnail img-circle" src="avatar.png" alt=""/>
                    <div class="btn-group">
                        <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <?php echo $sessionUser?>
                            <span class="caret"></span>
                        </span>
                            <ul class="dropdown-menu">
                                <li><a href="profile.php">My Profile</a></li>
                                <li><a href="newad.php">New Item</a></li>
                                <li><a href="profile.php#my-ad">Activities</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                    </div>
                    <?php

                } else  {
            ?>
                    <a href="login.php">
                        <span class="pull-right">Login/Signup</span>
                    </a>
            <?php } ?>
        </div>
    </div>
    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Homepage</a>
            </div>
            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    $allCats = getAllFrom("*","categories","where parent = 0","","ID","ASC");
                    foreach ($allCats as $cat) {
                        echo '<li>
                                <a href="categories.php?pageid='.$cat['ID'].'">'.$cat['Name']. '</a>
                              </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 21/11/2018
 * Time: 18:12
 */