<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 22/11/2018
 * Time: 00:15
 */


    ini_set('display_errors','On');
    error_reporting(E_ALL);

    include 'admin/connect.php';

    $sessionUser = '';
    if(isset($_SESSION['Username'])){
        $sessionUser = $_SESSION['Username'];
    }

    $tpl = 'includes/templates/';  // Template directory
    $lang = 'includes/languages/';            // Language directory
    $func = 'includes/functions/'; //Functions directory
    $css = 'layout/style/';         // CSS directory
    $js = 'layout/Js/';          //  JS directory


    //Include the important files
    include  $func . 'functions.php';
    include $lang . 'english.php';
    include $tpl . 'header.php';


