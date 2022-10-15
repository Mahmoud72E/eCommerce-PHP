<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php getTitle() ?></title>
        <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo $css ?>fontawesome.min.css">
        <link rel="stylesheet" href="<?php echo $css ?>fontawesome.css">
        <link rel="stylesheet" href="<?php echo $css ?>front.css">
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-light edi-nav" style="background-color: #131921; height: 60px;">
            <div class="container">
                <span class="navbar-brand mb-0 h1">
                    <img src="electron.jpg" width="30" height="30" class="d-inline-block align-top rounded-circle" alt="">
                    Electron Market
                </span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse edit-nav-div" id="app-nav">
                    <ul class="navbar-nav">
                        <?php 
                        if (isset($_SESSION['user'])){ ?>
                            <li class="nav-item"><a class="nav-link" href="profile.php">
                                <?php 
                                    $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  ORDER BY UserID DESC");
                                    $stmt->execute(array($_SESSION['uid']));
                                    $avatars = $stmt->fetchAll();
                                    foreach ($avatars as $avatar) {
                                        if (empty($avatar['avatar'])) {
                                            echo '<img src="electron.jpg" width="30" height="30" class="d-inline-block align-top rounded-circle" alt="">';
                                        } else {
                                            echo '<img src="..\..\\eCommerce\admin\uploads\avatars\\'.$avatar['avatar'].'" width="30" height="30" class="d-inline-block align-top rounded-circle" alt="">';
                                            //echo '<img src="..\..\\eCommerce\admin\uploads\avatars\\'.$avatar['avatar'].'" alt=""/>';
                                        }
                                    }
                                 ?>
                            </a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle drop-a" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                  Hello <?php echo $sessionUser ?></br>
                                  Account & Lists
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                                    <li><a class="dropdown-item" href="newad.php">New Item</a></li>
                                    <li><a class="dropdown-item" href="profile.php#my-ads">My Items</a></li>
                                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link delveriy-nav">Deliver to</br>
                                <i class="fa fa-flag"></i>
                                <strong>Egypt</strong></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Orders</a>
                            </li>
                            <li class="nav-item">
                                <?php $userStatus = checkUserStatus($sessionUser);
                                    if($userStatus == 1){echo '<span class="nav-link disabled no-active">Not Acitve!</span>';} ?>
                            </li>
                        <?php }else { ?>
                            <li class="nav-item btn-li-nav">
                                <a class="btn btn-primary btn-login-signup" href="login.php">
                                    <span class="">LogIn/SginUp</span>
                                </a>
                            </li>
                        <?php } ?>    
                    </ul>  
                </div>
            </div>
        </nav>                    
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #232f3e;height: 39px; font-size: 15px;">
            <div class="container">
                <a class="navbar-brand" href="index.php" style="font-size: 15px; font-weight: normal;">All</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="app-nav">
                    <ul class="navbar-nav">
                        <?php
                            foreach (getCat('WHERE parent = 0') as $cat) {
                                echo '<li class="nav-item">';
                                    echo '<a class="nav-link active" aria-current="page" href="categories.php?pageid='.$cat['ID'].'&pagename='. str_replace(' ', '-', $cat['Name']) .'">'
                                            .$cat['Name'].
                                        '</a>';
                                echo '</li>';
                            }
                        ?>
                    </ul>  
                </div>
            </div>
        </nav>                    
