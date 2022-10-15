<?php
    // Tittle Function v1.0
    function getTitle(){global $pageTitle;if(isset($pageTitle)){echo $pageTitle;}else{echo "Default";}}

    /*
    ** Get Categories Function v1.0
    ** Function To Get Categories From Database 
    */

    function getCat($where = NULL) {
        global $con;
        $getCat = $con->prepare("SELECT * FROM categories $where ORDER BY ID ASC ");
        $getCat->execute();
        $cats = $getCat->fetchAll();

        return $cats;
    }

    /*
    ** Home Redirect Function v2.0
    ** [ This Function Acept Parameters ] 
    ** $theMsg  = Echo The Massage [Error | Success | Warring ]
    ** $seconds = Seconds Before Redirecting
    ** $url    = The Link You Want To Redirect To
    */
    function redirectHome($theMsg, $url = null, $seconds = 3){

        if ($url === null) {
            $url = 'index.php';
            $link = 'Home Page';
        }elseif ($url === 'back') {
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                $url = $_SERVER['HTTP_REFERER'];
                $link = 'Pervious Page';
            } else {
                $url = 'index.php';
                $link = 'Home Page';
            }  
        } else {
            $link = 'Back';
        }

        echo $theMsg;

        echo "<div class='alert alert-info'>You Will Be Go To $link After $seconds Sec </div>";
        header("refresh:$seconds;url=$url");

        exit();

    }

    /*
    ** Check Item Function v1.0
    ** Functuon To Check Item In Database [ This Function Acept Parameters ]
    ** $select = The Item To Select [ EX: user, item, category]
    ** $from = The Table To Select From [ EX: users, items, categorys ]
    ** $value = The Value Of Select [ EX: osama, box, electronics]
    */

    function checkItem($select, $from, $value){
        
        global $con;
        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statement->execute(array($value));
        $count = $statement->rowCount();
        return $count;
    }

    /*
    ** Count Number Of Items Function v1.0
    ** Function To Count Number Of Items Row
    ** $item  = The Item To Count
    ** $table = The Table To Choose From
    */
    
    function countItems($item, $table){
        
        global $con;

        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt2->execute();
        return $stmt2->fetchColumn();

    }
    /*
    ** Get Latest Records Function v1.0
    ** Function To Get Latest Items From Database [ Users, Items, Comments ]
    ** $select = Field To Select From DB [ EX: user, item, category]
    ** $table  = The Table To Select From [ EX: users, items, categorys ]
    ** $order  = The Desc Ordering
    ** $limit  = The limit You Want To Show In Page [Number]
    */

    function getLatest($select, $table, $order, $limit = 5) {
        global $con;

        $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC  LIMIT $limit");
        $getStmt->execute();
        $rows = $getStmt->fetchAll();

        return $rows;
    }