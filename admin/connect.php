<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 22/11/2018
 * Time: 01:15
 */
    $dsn = 'mysql:host=localhost;dbname=shop';
    $user = 'root';
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );

    try {
        $con = new PDO($dsn,$user,$pass,$option);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        //echo ' You are connected Welcome to Database ';
    }
    catch (PDOException $e) {
        echo 'Failed to connect ' . $e->getMessage();
    }