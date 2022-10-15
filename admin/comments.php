<?php

    

    // Mange Comments Page
    // You Can Edit | Delete | Approve Comments From Here

    session_start();
    if (isset($_SESSION['Username'])){
        
        $pageTitle  = "Comments";

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        
        //Start Manage Page
        if ($do == 'Manage') { // Mange Mambers Page
            // Select All users Except Admin
            $stmt = $con->prepare("SELECT comments.*, items.Name AS item_name, users.UserName FROM comments
                                        INNER JOIN items ON items.Item_ID = comments.item_id
                                        INNER JOIN users ON users.UserID = comments.user_id ORDER BY c_id DESC");
            $stmt->execute();
            $rows = $stmt->fetchAll();
        ?> 
        
        <h1 class="text-center member"><?php echo lang('Manage Comments')?></h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                <tr>
                    <td><?php echo lang('#ID')?></td>
                    <td><?php echo lang('Comment')?></td>
                    <td><?php echo lang('Item Name')?></td>
                    <td><?php echo lang('User Name')?></td>
                    <td><?php echo lang('Add Date')?></td>
                    <td><?php echo lang('Control')?></td>
                </tr>
                <?php 
                    foreach($rows as $row){
                        echo "<tr>";
                            echo "<td>". $row['c_id'] . "</td>";
                            echo "<td>". $row['comment'] . "</td>";
                            echo "<td>". $row['item_name'] . "</td>";
                            echo "<td>". $row['UserName'] . "</td>";
                            echo "<td>". $row['comment_date'] . "</td>";
                            echo "<td> <a href='comments.php?do=Edit&comid=". $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>". lang('Edit') . "</a>
                                       <a href='comments.php?do=Delete&comid=". $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-x'></i>" . lang('Delete') . "</a>";
                                        if ($row['status'] == 0) {
                                           echo "<a href='comments.php?do=Approve&comid=". $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-o'></i>" . lang('Approve') . "</a>";
                                        }
                            echo  "</td>";
                        echo "</tr>";
                    }
                ?>
                </table>
            </div>
        </div>

    <?php  //End Manage Page 
        // Start Edit Page 
        } elseif ($do == 'Edit') { // EDit Page 

            $comid = isset( $_GET['comid'] ) && is_numeric( $_GET['comid'] ) ? intval($_GET['comid']) : 0;
            
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
            $stmt->execute(array($comid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0 ) { ?> 

                <h1 class="text-center member"><?php echo lang('Edit Comment')?></h1>
        
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="comid" value="<?php echo $comid ?>">
                        <!-- Start comment Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Comment')?></label>
                            <div class="col-sm-10 col-md-6">
                                <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
                            </div>
                        </div>
                        <!-- End comment Failed -->
                        <!-- Start User Submit Failed -->
                        <div class="form-group">
                            <div class="lcol-sm-offset-2 col-sm-10">
                                <input type="submit" value="<?php echo lang('Save')?>" class="btn btn-primary btn-block memberbtn"/>
                            </div>
                        </div>
                        <!-- End User Submit Failed -->
                    </form>
                </div>
        <?php 
            } else { // Error Masage If ID Not Exisit
                $theMsg = '<div class="alert alert-danger"><h1>Erorr 404 Not Found This ID<h1></div>';
                redirectHome($theMsg,null, 5);
            };
    }   // End Edit Page 
        // Start Update Page
    elseif ($do == 'Update') { // Update Page
       
       echo '<h1 class="text-center member">'. lang('Update Comment') . '</h1> ';
       echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Get Var From Form

            $id = $_POST['comid'];
            $comment = $_POST['comment'];

            // Update The Database By This Info

            $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
            $stmt->execute(array($comment, $id));

            //Echo Done

            $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Updated') . '</h1></div>';
            redirectHome($theMsg,'back', 1);
        } else {
            $theMsg = '<div class="alert alert-danger">404 Not Found Or You Are Here Direct</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    
    }// End Update Page

    // Start Delete Page 
        elseif ($do == 'Delete') {// Delete Page
            echo '<h1 class="text-center member">'. lang('Delete Comment') . '</h1> ';
            echo "<div class='container'>";
                // Check If GET Request comid Is Numeric & Get The Integer Value Of It
                $comid = isset( $_GET['comid'] ) && is_numeric( $_GET['comid'] ) ? intval($_GET['comid']) : 0;
                // Select All Data Depend on This ID
                $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
                $stmt->execute(array($comid));
                $count = $stmt->rowCount();
                // If There's Such ID Show The Form
                if ($stmt->rowCount() > 0 ) {
                    $stmt= $con->prepare("DELETE FROM comments WHERE c_id = :zcomid");
                    $stmt->bindParam(":zcomid", $comid);
                    $stmt->execute();
                    $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Deleted') . '</h1></div>'; 
                    redirectHome($theMsg,'back',1);
                } else {
                    $theMsg = '<div class="alert alert-danger"><h1>This ID Not Founed <strong>404</strong></h1></div>';
                    redirectHome($theMsg);
                }
            echo "</div>";
    }// End Delete Page
    // Start Activate Page 
    elseif ($do == 'Approve'){ // Activate Page
        echo '<h1 class="text-center member">'. lang('Approve Comment') . '</h1> ';
        echo "<div class='container'>";
            // Check If GET Request comid Is Numeric & Get The Integer Value Of It
            $comid = isset( $_GET['comid'] ) && is_numeric( $_GET['comid'] ) ? intval($_GET['comid']) : 0; 
            // Select All Data Depend on This ID
            $check = checkItem('c_id', 'comments', $comid); 
            // If There's Such ID Show The Form
            if ($check > 0 ) { 
                $stmt= $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
                $stmt->execute(array($comid));
                $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Approved') . '</h1></div>'; 
                redirectHome($theMsg,'back',1);
            } else {
                $theMsg = '<div class="alert alert-danger"><h1>This ID Not Founed <strong>404</strong></h1></div>';
                redirectHome($theMsg);
            }
        echo "</div>";
        
    }
    // End Activate Page 
        include $tpl . "footer.php";
    } else {
        header('Location: index.php');
        exit;


    }; ?>