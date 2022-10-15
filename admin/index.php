<?php
    session_start();
    $noNavbar   = '';
    $pageTitle  = "Login";

    #session
    if (isset($_SESSION['Username'])){
        header('Location: dashboard.php'); // Redirect To Dashboard Page
    };
    # Files 
   include 'init.php';
   //include 'includes/languages/arabic.php';
   // include "includes/templates/header.php";
    
    # SginIn 
    // Check If User Comming From HTTP POST Request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);
        
        // Check If User Exist in Database
        $stmt = $con->prepare("SELECT 
                                    UserID, UserName, Password 
                                FROM 
                                    users 
                                WHERE 
                                    UserName = ? 
                                AND 
                                    Password = ? 
                                AND 
                                    GroupID = 1
                                LIMIT 1");
        $stmt->execute(array($username, $hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        
        //If Count > 0 --> User Name Exist
        if ($count > 0) {
            $_SESSION['Username'] = $username; // Register Session Name
            $_SESSION['ID']   = $row['UserID']; // Register Session ID
            header('Location: dashboard.php'); // Redirect To Dashboard Page
            exit();
        }
    }
?>
    <div class="continer">
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h1 class="text-center"><?php echo lang('Admin Login')?></h1>
        <input class="form-control" type="text" name="user" placeholder="User Name" autocomplete="FALSE"/>
        <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password"/>
        <!-- <input type="email" name="email" placeholder="Email" autocomplete="FALSE" /> -->
        <input class="btn btn-primary btn-block" type="submit" value="<?php echo lang('Login')?>"/>
    </form>
    </div>
 <?php include $tpl . "footer.php"; ?> 