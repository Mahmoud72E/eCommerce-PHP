<?php

    ob_start(); // Output Buffering Start

    session_start();
    if (isset($_SESSION['Username'])){
        $pageTitle  = "Dashboard";
        include 'init.php';
        /* Start Dashboard Page */
            // Get Latest User 
            $numUsers = 4; // Number of Latest Users
            $latestUsers = getLatest('*', 'users', 'UserID', $numUsers); // Latest Users Array
            $numItems = 4; // Number Of Latest Items
            $latestItems = getLatest('*', 'items', 'Item_ID', $numItems ); // Latest Items Array   
            $numComments = 4; 
        ?>
        <h1 class="dashboard text-center"><?php echo lang('Dashboard')?></h1>
        <div class="container home-stats text-center">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-user-circle"></i>
                        <div class="info">
                            <?php echo lang('Total Members')?>
                            <span><a href="members.php" ><?php echo countItems('UserID','users') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-address-book"></i>
                        <div class="info">
                            <?php echo lang('Pending Members')?>
                            <span><a href="members.php?do=Manage&page=Pending">
                                <?php echo checkItem('RegStatus', 'users', 0); ?>
                            </a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-check-square"></i>
                        <div class="info">
                            <?php echo lang('Total Items')?>
                            <span><a href="Items.php" ><?php echo countItems('Item_ID', 'items') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            <?php echo lang('Total Comments')?>
                            <span><a href="comments.php" ><?php echo countItems('c_id', 'comments') ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <i class="fa fa-user"></i> <?php echo lang('Latest Registerd Users') . ' ' . $numUsers ?>
                            <span class="toggle-info float-end">
                                <i class="fa fa-plus-square fa-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled latest-users">
                            <?php 
                                foreach($latestUsers as $user){
                                    echo  '<li>'; 
                                        echo $user['UserName'];  
                                        echo '<a href="members.php?do=Edit&userid='. $user['UserID'] . '">';
                                            echo '<span class="btn btn-success float-end">';
                                                echo '<i class="fa fa-edit"></i>';
                                                echo lang('Edit');
                                                if ($user['RegStatus'] == 0) {
                                                    echo "<a href='members.php?do=Activate&userid=". $user['UserID'] . "' class='btn btn-info float-end activate'><i class='fa fa-o'></i>" . lang('Activate') . "</a>";
                                                 }         
                                            echo '</span>';
                                        echo '</a>';
                                    echo '</li>';
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <i class="fa fa-list-alt"></i> <?php echo lang('Latest Items') . ' ' . $numItems?>
                            <span class="toggle-info float-end">
                                <i class="fa fa-plus-square fa-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                        <ul class="list-unstyled latest-users">
                            <?php
                                if (! empty($latestItems)) {
                                    foreach($latestItems as $item){
                                        echo  '<li>'; 
                                            echo $item['Name'];  
                                            echo '<a href="Items.php?do=Edit&itemid='. $item['Item_ID'] . '">';
                                                echo '<span class="btn btn-success float-end">';
                                                    echo '<i class="fa fa-edit"></i>';
                                                    echo lang('Edit');
                                                    if ($item['Approve'] == 0) {
                                                        echo "<a href='Items.php?do=Approve&itemid=". $item['Item_ID'] . "' class='btn btn-info float-end activate'><i class='fa fa-o'></i>" . lang('Approve') . "</a>";
                                                    }         
                                                echo '</span>';
                                            echo '</a>';
                                        echo '</li>';
                                    }
                                } else {echo 'There\'s No Items Here';}
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Latest Comments Row -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <i class="fa fa-comment-dots"></i> <?php echo lang('Latest Comments') . ' ' . $numComments ?>
                            <span class="toggle-info float-end">
                                <i class="fa fa-plus-square fa-lg"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <?php
                                $stmt = $con->prepare("SELECT comments.*, users.UserName FROM comments
                                                        INNER JOIN users ON users.UserID = comments.user_id ORDER BY c_id DESC LIMIT $numComments");
                                $stmt->execute();
                                $comments = $stmt->fetchAll();
                                if (! empty($comments)) {
                                    foreach ($comments as $comment) {
                                        echo '<div class="comment-box">';
                                            echo '<span class="member-n">' . $comment['UserName'] . '</span>';
                                            echo '<p class="member-c">' . $comment['comment'];
                                                echo '<a href="comments.php?do=Edit&comid='. $comment['c_id'] . '">';
                                                    echo '<span class="btn btn-success" float-end>';
                                                        echo '<i class="fa fa-edit"></i>';
                                                        echo lang('Edit');
                                                        if ($comment['status'] == 0) {
                                                            echo "<a href='comments.php?do=Approve&comid=". $comment['c_id'] . "' class='btn btn-info float-end activate'><i class='fa fa-o'></i>" . lang('Approve') . "</a>";
                                                        }         
                                                    echo '</span>';
                                                echo '</a>';
                                            echo '</p>';;
                                        echo '</div>';
                                    } 
                                } else {
                                        echo "There's No Comments Here To Show";
                                
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Latest Comments Row -->
        </div>


        <?php
        /* End Dashboard Page */
        include $tpl . "footer.php";
    } else {
        header('Location: index.php');
        exit;
    };

    ob_end_flush();
    ?>