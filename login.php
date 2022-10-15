<?php
    ob_start();
    session_start();
    $pageTitle  = "Login";
    if (isset($_SESSION['user'])){
        header('Location: index.php'); // Redirect To Dashboard Page
    }
    include 'init.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['login'])) {
            $user = $_POST['username'];
            $pass  = $_POST['password'];
            $hashedPass = sha1($pass);
            // Check If User Exist in Database
            $stmt = $con->prepare("SELECT 
                                        UserID, UserName, Password 
                                    FROM 
                                        users 
                                    WHERE 
                                        UserName = ? 
                                    AND 
                                        Password = ?");
            $stmt->execute(array($user, $hashedPass));
            $get = $stmt->fetch();
            $count = $stmt->rowCount();
            //If Count > 0 --> User Name Exist
            if ($count > 0) {
                $_SESSION['user'] = $user; // Register Session Name
                $_SESSION['uid'] = $get['UserID']; // Regisetr UserID In Session 
                header('Location: index.php'); // Redirect To Dashboard Page
                exit();
            }
        } else {
            $formErrors = array();
            $username   = $_POST['username'];
            $password   = $_POST['password'];
            $password2  = $_POST['password2'];
            $email      = $_POST['email'];

            if (isset($username)) {
                $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);
                if (strlen($filterdUser) < 4) {
                    $formErrors[] = 'Your User Name Have To Be More Then 4 Charavters';

                }
            }
            if (isset($password) && isset($password2)) {
                if (empty($password)) {
                    $formErrors[] = 'Password Can\'t Be Empty';
                }
                if (sha1($password) !== sha1($password2)) {
                    $formErrors[] = 'Password Is Not Match';
                }
            }
            if (isset($email)) {
                $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true){
                    $formErrors[] = 'This Email Not Valid';
                }
            }
            if (empty($formErrors)) {  
                // Check If User Exist in Database
                $check = checkItem("UserName", "users", $username);
                if ($check == 1){
                    $formErrors[] = 'Sorry This Username Is Exists';
                }else {
                   // Insert Userinfo in database
       
                   $stmt = $con->prepare("INSERT INTO 
                                                       users(UserName, Password, Email, RegStatus, Date)
                                                       VALUES(:user, :pass, :mail, 0, now()) ");
                   $stmt->execute(array(
                       'user' => $username,
                       'pass' => sha1($password),
                       'mail' => $email
                    ));
       
                   // Echo Done
       
                    $succesMsg = 'Congrats You Are Now Registerd User';
                 }
            }
        }
    }
?>

    <div class="container login-page">
        <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>
        <!-- Start Login Form -->
        <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your User Name" required="required"/>
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password" required/>
            <input class="btn btn-primary" name="login" type="submit" value="Login" />
        </form>
        <!-- End Login Form -->
        <!-- Start Signup Form -->
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <input class="form-control" pattern=".{4,}" title="UserName Must 4 Char Or More" type="text" name="username" autocomplete="off" placeholder="Type Your User Name" required/>
            <input class="form-control" minlength="4" type="password" name="password" autocomplete="new-password" placeholder="Type a Complex Password" required/>
            <input class="form-control" minlength="4" type="password" name="password2" autocomplete="new-password" placeholder="Type Again The Password" required/>
            <input class="form-control" type="email" name="email" placeholder="Type a Vailed Email" required/>
            <input class="btn btn-success" name="sginup" type="submit" value="Signup" />
        </form>
        <!-- End Signup Form -->
        <div class="the-errors text-center">
            <?php 
                if (!empty($formErrors)){
                    echo '<div class="container">';
                    foreach ($formErrors as $error) {
                        echo '<div class="msg error">'.$error. '</div>';
                    }
                    echo '</div>';
                }
                if (isset($succesMsg)) {
                    echo '<div class="msg success">'. $succesMsg .'</div>';
                }
            ?>
        </div>
    </div>
<?php
    include $tpl .'footer.php';
    ob_end_flush();
?>