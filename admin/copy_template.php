<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 19/12/2018
 * Time: 20:49
 */
ob_start();

session_start();

$pageTitle = '';

if(isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == 'Manage'){

        echo 'Welcome';

    }else if ($do == 'Add'){

    }else if ($do == 'Insert'){

    }else if ($do == 'Edit'){

    }else if ($do == 'Update'){

    }else if($do == 'Delete') {

    }else if ($do == 'Activate') {

    }

    include $tpl . 'footer.php';
} else {

    header('Location: index.php');

    exit();
}

ob_end_flush();

?>