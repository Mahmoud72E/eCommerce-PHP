<?php
    ob_start();
    session_start();
    $pageTitle = "Profile";
    include 'init.php';
    if (isset($_SESSION['user'])) {
        $getUser = $con->prepare("SELECT * FROM users WHERE UserName = ?");
        $getUser->execute(array($sessionUser));
        $info = $getUser->fetch();

?>
<h1 class="text-center">My Profile</h1>
<div class="infotmation block">
    <div class="container">
        <div class="card card-primary">
            <div class="card-header">My Information</div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Login Name</span>: <?php echo $info['UserName']; ?> 
                    </li>
                    <li>
                        <i class="fa fa-envelope fa-fw"></i>
                        <span>Email</span> : <?php echo $info['Email']; ?> 
                    </li>
                    <li>
                        <i class="fa fa-address-book fa-fw"></i>
                        <span>Full Name</span> : <?php echo $info['FullName']; ?> 
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Date</span> : <?php echo $info['Date']; ?> 
                    </li>
                    <li>
                        <i class="fa fa-heart fa-fw"></i>
                        <span>Fav Category</span> :
                    </li>
                </ul>
                <a href="#" class="btn btn-success float-end">Edit Info</a>
            </div>
        </div>
    </div>
</div>
<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="card card-primary">
            <div class="card-header">My Items</div>
            <div class="card-body">
                <?php
                    if (! empty(getItems('Member_ID', $info['UserID']))) {
                        echo '<div class="row">';
                            foreach (getItems('Member_ID', $info['UserID'], 1) as $item) {
                                echo "<div class='col-sm-6 col-md-3'>";
                                    echo '<div class="img-thumbnail item-box">';
                                        if ($item['Approve'] == 0) { echo '<span class="approve-status">Wating Approve!</span>';}
                                        echo '<span class="price-tag">$'.$item['Price'].'</span>';
                                        echo '<img class="img-fluid" src="electron.jpg" alt="" />';
                                        echo '<div class="caption">';
                                            echo '<h3><a class="item-name" href="items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>';
                                            echo '<p>'.$item['Description'].'</p>';
                                            echo '<div class="date">'.$item['Add_Date'].'</div>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            }
                        echo '</div>';
                    } else {
                        echo 'There\'s No Items to Show, Create <a style="color: #a5a537; text-decoration: none;" href="newad.php">New Item</a>';
                    }

                ?>
            </div>
        </div>
    </div>
</div>
<div class="my-comments block">
    <div class="container">
        <div class="card card-primary">
            <div class="card-header">Latest Comments</div>
            <div class="card-body">
                <?php
                    $stmt = $con->prepare("SELECT comment FROM comments WHERE user_id = ?");
                    $stmt->execute(array($info['UserID']));
                    $comments = $stmt->fetchAll();
                    if (! empty($comments)) {
                        foreach ($comments as $comment) {
                            echo '<p>' . $comment['comment'] . '</p>';
                        }
                    } else {
                        echo 'There\'s No Comments to Show';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
    } else {
        header('Location: login.php');
        exit();
    }
    include $tpl . "footer.php"; 
    ob_end_flush();
?>