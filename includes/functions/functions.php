<?php
/**
 * Created by PhpStorm.
 * User: Marwen Aouiti
 * Date: 23/11/2018
 * Time: 19:46
 */


function getAllFrom($field, $table, $where = NULL,$and=NULL, $orderfield, $ordering = "DESC"){

    global $con;
    $where  == NULL ? '' : $where;

    $getAll = $con->prepare("SELECT $field  from $table $where $and ORDER BY $orderfield $ordering");
    $getAll->execute();
    $all = $getAll->fetchAll();

    return $all;
}

/*
 * Function to Get Categories from data base
 * */

    function getCat(){

        global $con;

        $getCat = $con->prepare("SELECT *  from categories ORDER BY ID DESC");
        $getCat->execute();
        $cats = $getCat->fetchAll();

        return $cats;
    }

    /*
     * Function to Get Items from data base
     * */

    function getItems($where, $value, $approve = NULL){

        global $con;

        if($approve == NULL){
            $sql = 'AND Approve = 1';
        } else {
            $sql = NULL;
        }
        $getItems = $con->prepare("SELECT *  from items WHERE $where = ? $sql ORDER BY Item_ID DESC");
        $getItems->execute(array($value));
        $items = $getItems->fetchAll();

        return $items;
    }
    /*
     * Check if user is not activated
     * */
    function checkUserStatus($user){

        global $con;

        $stmtx = $con->prepare("SELECT Username, RegStatus FROM users WHERE Username = ? AND RegStatus = 0");
        $stmtx->execute(array($user));
        $status = $stmtx->rowCount();

        return $status;
    }



    function getTitle() {

        global $pageTitle;

        if(isset($pageTitle)) {
            echo $pageTitle;
        } else {

            echo 'Default';
        }

    }

    // Redirect function

        function redirectHome($Msg, $url=null,$seconds = 3) {

          if($url === null) {
              $url = 'index.php';
              $link = 'Hompage';
          } else {
              if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){

                $url = $_SERVER['HTTP_REFERER']; //the page where you came from
                  $link = 'Previous page';
              } else {

                  $url = 'index.php';
                  $link = 'Hompage';
              }
          }
          echo $Msg;
          echo "<div class='alert alert-info'> You will be redirected to $link page after $seconds seconds.</div>";
          header("refresh:$seconds;url=$url");
          exit();
        }

    /*
     * Function to check items in database
     * $select = The item to select [Example : user, item, category]
     * $from   = Table to select from
     * $value  = The value of select
     */

    function checkItem($select,$from, $value) {
        global  $con;
        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statement->execute(array($value));
        $count = $statement->rowCount();

        return $count;
    }

    /*
     * This function count the number of the items
     * it will count the nubmer of rows
     * Input : items to be counted ($item = name in the database)
     *         table where the item exist ($table)
     * Output: Number of items
     * */
    function countItems($item,$table) {

        global $con;

        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

        $stmt2->execute();

        return $stmt2->fetchColumn();

    }

    /*
     * Function to Get latest items from Data base [Users, Items, Comments]
     * $select = field to select
     * $table = table to select from
     * $order = the ordering of the items
     * $limit = number of records to get
     * */

    function getLatest($select,$table, $order, $limit = 5){

        global $con;

        $getStmt = $con->prepare("SELECT $select from $table ORDER BY $order DESC LIMIT $limit");
        $getStmt->execute();
        $rows = $getStmt->fetchAll();

        return $rows;
    }