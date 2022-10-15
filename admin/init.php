<?php

// Connect File To MySQL Server

include 'connect.php';

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
if (!isset($noNavbar)) { include $tpl . "navbar.php"; }; // Invlude Navbar But Some Pages NO