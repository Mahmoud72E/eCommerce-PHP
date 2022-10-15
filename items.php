<?php
    ob_start();
    session_start();
    $pageTitle = "Show Items";
    include 'init.php';
    $itemid = isset( $_GET['itemid'] ) && is_numeric( $_GET['itemid'] ) ? intval($_GET['itemid']) : 0;
    $stmt = $con->prepare("SELECT items.*, categories.Name AS category_name, users.UserName FROM items
                            INNER JOIN categories ON categories.ID = items.Cat_ID
                            INNER JOIN users ON users.UserID = items.Member_ID
                            WHERE Item_ID = ? AND Approve = 1
                        ");
    $stmt->execute(array($itemid));
    $count = $stmt->rowCount();
    if ($count > 0) {
    $item = $stmt->fetch();
?>
<h1 class="text-center"><?php echo $item['Name'] ?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img class="img-fluid img-thumbnail d-block m-auto" src="electron.jpg" alt="" />
        </div>
        <div class="col-md-9 item-info">
            <h2><?php echo $item['Name'] ?></h2>
            <p><?php echo $item['Description'] ?></p>
            <ul class="list-unstyled">
                <li><i class="fa fa-calendar fa-fw"></i> <span>Added Date</span>: <?php echo $item['Add_Date'] ?></li>
                <li><i class="fa fa-credit-card-alt fa-fw"></i> <span>Price</span>: $<?php echo $item['Price'] ?></li>
                <li><i class="fa fa-map fa-fw"></i> <span>Made In</span>: <?php echo $item['Country_Made'] ?></li>
                <li><i class="fa fa-list-alt fa-fw"></i> <span>Category</span>: <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>&pagename=<?php echo str_replace(' ', '-', $item['category_name'] ) ?>"><?php echo $item['category_name'] ?></a></li>
                <li><i class="fa fa-user fa-fw"></i> <span>Added By</span>: <a href="#"><?php echo $item['UserName'] ?></a></li>
                <?php if (!empty($item['tags'])) { ?>
                    <li class="tags-items"><i class="fa fa-tag fa-fw"></i> <span>Tags</span>: 
                        <?php 
                            $allTags = explode(",", $item['tags']);
                            foreach ($allTags as $tag) {
                                $tag = str_replace(' ', '', $tag);
                                $lowertag = strtolower($tag);
                                if (!empty($tag)) {
                                echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
                                }
                            }
                        ?>
                    </li>
                <?php } ?>
            </ul>
        </div>        
    </div>
    <hr>
    <!-- Start Add Comment -->
    <?php if (isset($_SESSION['user'])) { ?>
    <div class="row">
        <div class="offset-md-3">
            <div class="add-comment">
                <h3>Add Your Comment</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
                    <textarea name="comment" required></textarea>
                    <input class="btn btn-primary" type="submit" value="Add Comment">
                </form>
                <?php 
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $comment = $_POST['comment'];
                        $itmeid = $item['Item_ID'];
                        $userid = $_SESSION['uid'];

                        if (! empty($comment)) {
                            $stmt = $con->prepare("INSERT INTO comments(comment, status, comment_date, item_id, user_id)
                                                    VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");
                            $stmt->execute(array(
                                'zcomment' => $comment,
                                'zitemid' => $itemid,
                                'zuserid' => $userid
                            ));
                            if ($stmt) {
                                echo '<div class="alert alert-success">Comment Added</div>';
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <?php } else {echo '<div class="offset-md-3"><h4>You Have To <a href="login.php">Login/Signup</a> To Add Comment</h4></div>';} ?>
    <!-- End Add Comment -->
    <hr>
    <?php
        $stmt = $con->prepare("SELECT comments.*, users.UserName AS Member FROM comments
                                INNER JOIN users ON users.UserID = comments.user_id 
                                WHERE item_id = ?
                                AND status = 1
                                ORDER BY c_id DESC");
        $stmt->execute(array($item['Item_ID']));
        $comments = $stmt->fetchAll(); 
        

        foreach ($comments as $comment) { ?>
            <div class="comment-box">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <img class="img-fluid img-thumbnail rounded-circle d-block m-auto" src="electron.jpg" alt="" />
                        <?php echo $comment['Member'] ?>
                    </div>
                    <div class="col-sm-10">
                        <p class="lead"><?php echo $comment['comment'] ?></p>
                    </div>
                </div>
            </div>
            <hr>
       <?php }?>
    
</div>
<?php
    } else {
        echo '<h3 class="text-center">There\'s No Such ID Or This Item Not Approve Yet</br> Try Again</h3>';
    }
    include $tpl . "footer.php"; 
    ob_end_flush();
?>