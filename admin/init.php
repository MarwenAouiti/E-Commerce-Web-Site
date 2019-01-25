<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 22/11/2018
 * Time: 00:15
 */

include 'connect.php';

$tpl = 'includes/templates/';  // Template directory
$lang = 'includes/languages/';            // Language directory
$func = 'includes/functions/'; //Functions directory
$css = 'layout/style/';         // CSS directory
$js = 'layout/Js/';          //  JS directory


//Include the important files
include  $func . 'functions.php';
include $lang . 'english.php';
include $tpl . 'header.php';

//Include navbar on all pages except the one with $noNavbar variable

if(!isset($noNavbar)) {include $tpl . 'navbar.php';}
