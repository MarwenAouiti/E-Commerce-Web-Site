<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 22/11/2018
 * Time: 21:44
 */
/*
    Categories => [Manage | Edit | Update | Add | Insert | Delete | Stats ]
    Condition ? True : False
 */
    $do = '';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    //If we're in the main page

    if($do == 'Manage') {

        echo 'Welcome you are in Manage categories page';
        echo '<a href="page.php?do=Insert">Add new categorie +</a>';
    } elseif ($do == 'Add') {

        echo 'Your are in Add Categories';
    } elseif ($do == 'Insert') {
        echo 'Your are in Insert Categories';

    }
    else {

        echo 'Error: Page Not Found';
    }