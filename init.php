<?php

// Connect File To MySQL Server

include 'admin/connect.php';

$sessionUser = '';
if(isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}

// Routes

$tpl    = 'includes/templates/';    // Template path dir
$lang   = 'includes/languages/';    // Languages Path
$func   = 'includes/functions/';     // Function Path
$css    = 'layout/css/';            // Css path
$js     = 'layout/js/';             // js path


// Include Very Important Files

include $func . 'functions.php';
include $lang . 'english.php';
include $tpl . "header.php";
