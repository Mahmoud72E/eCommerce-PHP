<?php

    

    // Mange Members Page
    // You Can Add | Edit | Delete Members From Here

    session_start();
    if (isset($_SESSION['Username'])){
        
        $pageTitle  = "Members";

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        
        //Start Manage Page
        if ($do == 'Manage') { // Mange Mambers Page
            // Pending Page Trick

            $query = '';

            if (isset($_GET['page']) && $_GET['page'] == 'Pending'){
                $query = 'AND RegStatus = 0';
            }

            // Select All users Except Admin
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
            $stmt->execute();
            $rows = $stmt->fetchAll();
        ?> 
        
        <h1 class="text-center member"><?php echo lang('Manage Mambers')?></h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                <tr>
                    <td><?php echo lang('#ID')?></td>
                    <td>Profile Image</td>
                    <td><?php echo lang('User Name')?></td>
                    <td><?php echo lang('Email')?></td>
                    <td><?php echo lang('Full Name')?></td>
                    <td><?php echo lang('Registerd Date')?></td>
                    <td><?php echo lang('Control')?></td>
                </tr>
                <?php 
                    foreach($rows as $row){
                        echo "<tr>";
                            echo "<td>". $row['UserID'] . "</td>";
                            echo "<td>";
                                if (empty($row['avatar'])) {
                                    echo 'No Image';
                                } else {
                                 echo "<img src='uploads\avatars\\" . $row['avatar'] . "' alt='' />";
                                }
                            echo "</td>";
                            echo "<td>". $row['UserName'] . "</td>";
                            echo "<td>". $row['Email'] . "</td>";
                            echo "<td>". $row['FullName'] . "</td>";
                            echo "<td>". $row['Date'] ."</td>";
                            echo "<td> <a href='members.php?do=Edit&userid=". $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>". lang('Edit') . "</a>
                                       <a href='members.php?do=Delete&userid=". $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-x'></i>" . lang('Delete') . "</a>";
                                        if ($row['RegStatus'] == 0) {
                                           echo "<a href='members.php?do=Activate&userid=". $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-o'></i>" . lang('Activate') . "</a>";
                                        }
                            echo  "</td>";
                        echo "</tr>";
                    }
                ?>
                </table>
            </div>  
            <a href='members.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo lang('New Member')?> </a>
        </div>

    <?php } //End Manage Page 

        //Start Add Page
        elseif ($do == 'Add'){ // Add Members Page ?> 
            
                <h1 class="text-center member"><?php echo lang('New Member')?></h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                        <!-- Start User Name Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('User Name')?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="username" placeholder="UserName" class="form-control" required='required' />
                            </div>
                        </div>
                        <!-- End User Name Failed -->
                        <!-- Start Password Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Password')?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="password" name="password" placeholder="Write Your New Password" class="password form-control" autocomplete="NULL" required='required'/>
                                <i class="show-pass fa fa-eye fa-2x"></i> 
                            </div>
                        </div>
                        <!-- End User Password Failed -->
                        <!-- Start User Email Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Email')?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="email" name="email" placeholder="Your Email" class="form-control" required='required'/>
                            </div>
                        </div>
                        <!-- End User Email Failed -->
                        <!-- Start User Full Name Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Full Name') ?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="full" placeholder="Your Full Name" class="form-control" required='required'/>
                            </div>
                        </div>
                        <!-- End User Full Name Failed -->
                        <!-- Start Profile Img Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Profile Image</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="file" name="avatar" class="form-control" required='required'/>
                            </div>
                        </div>
                        <!-- End Profile Img Failed -->
                        <!-- Start User Submit Failed -->
                        <div class="form-group">
                            <div class="lcol-sm-offset-2 col-sm-10">
                                <input type="submit" value="<?php echo lang('Add Member')?>" class="btn btn-primary btn-block memberbtn"/>
                            </div>
                        </div>
                        <!-- End User Submit Failed -->
                    </form>
                </div>
        

         <?php 
         } elseif ($do == 'Insert') { // Insert Member Page
             if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<h1 class="text-center member">'. lang('Insert Member') . '</h1>';
                echo "<div class='container'>";
    
                 // Upload Var

                 $avatarName = $_FILES['avatar']['name'];
                 $avatarSize = $_FILES['avatar']['size'];
                 $avatarTmp = $_FILES['avatar']['tmp_name'];
                 $avatarType = $_FILES['avatar']['type'];

                 // List Of Allowed File Typed To Upload

                 $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

                 // Get Avater Extension
                 
                 $avatarExtension = strtolower(end(explode(".", $avatarName)));
                 
                 // Get Var From Form

                 $user  = $_POST['username'];
                 $pass  = $_POST['password'];
                 $email = $_POST['email'];
                 $name  = $_POST['full'];
     
                 $hashpass = sha1($_POST['password']);

                 // Validate The form
     
                 $formErrors = array();
     
                 if (empty($user)) {
                     $formErrors[] = 'Username Can\'t Be Empty';
         
                 }
                 if (empty($pass)) {
                    $formErrors[] = 'Password Can\'t Be Empty';
        
                }
                 if (empty($email)) {
                     $formErrors[] = 'Email Can\'t Be Empty';
         
                 }
     
                 if (empty($name)) {
                    $formErrors[] = 'Full Name Can\'t Be Empty';
         
                 }
                 if (!empty($avatarName) && !in_array($avatarExtension, $avatarAllowedExtension)) {
                    $formErrors[] = 'This File Not Allow Here, Uploed Image';
                 }
                 if (empty($avatarName)) {
                    $formErrors[] = 'You Have To Uploed Image';
                 }
                 if ($avatarSize > 4194304) {
                    $formErrors[] = 'Image Can\'t Be Larger Than 4MB';
                 }
                 foreach ($formErrors as $error) {
                     echo $error . '</br>';
                 }
                if (empty($formErrors)) {

                    $avatar = rand(0, 100000000000000) . '_' . $avatarName;
                    move_uploaded_file($avatarTmp, 'uploads\avatars\\' . $avatar);
                    
                    // Check If User Exist in Database
                    $check = checkItem("UserName", "users", $user);
                    if ($check == 1){
                        $theMsg = '<div class="alert alert-danger"><h2>Sorry This <strong>[User Name] </strong>Is Exist Try Antother one</h2></div>';
                        redirectHome($theMsg,'back');
                    }else {
                        // Insert Userinfo in database
            
                        $stmt = $con->prepare("INSERT INTO 
                                                            users(UserName, Password, Email, FullName, RegStatus, Date, avatar)
                                                            VALUES(:user, :pass, :mail, :name, 1, now(), :zavatar) ");
                        $stmt->execute(array(
                            'user' => $user,
                            'pass' => $hashpass,
                            'mail' => $email,
                            'name' => $name,
                            'zavatar' => $avatar
                            ));
            
                        // Echo Done
                        $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Inserted') .'</h1></div>';
                        redirectHome($theMsg,'members.php');
                    }
                }   
            } else {
                 $theMsg = '<div class="alert alert-danger">404 Not Found Or You Are Here Derict</div>';
                 redirectHome($theMsg);
             }
             echo "</div>";     
        
        // End Add Page

        // Start Edit Page 
        } elseif ($do == 'Edit') { // EDit Page 

            $userid = isset( $_GET['userid'] ) && is_numeric( $_GET['userid'] ) ? intval($_GET['userid']) : 0;
            
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0 ) { ?> 

                <h1 class="text-center member"><?php echo lang('Edit Member')?></h1>
        
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <!-- Start User Name Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('User Name')?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="username" placeholder="UserName" value="<?php echo $row['UserName']?>"  class="form-control" />
                            </div>
                        </div>
                        <!-- End User Name Failed -->
                        <!-- Start Password Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Password')?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="hidden"   name="oldpassword" vlue="<?php echo $row['Password']?>"/>
                                <input type="password" name="newpassword" placeholder="Write Your New Password" class="form-control" autocomplete="NULL" />
                            </div>
                        </div>
                        <!-- End User Password Failed -->
                        <!-- Start User Email Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Email')?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="email" name="email" placeholder="Your Email" value="<?php echo $row['Email']?>" class="form-control"/>
                            </div>
                        </div>
                        <!-- End User Email Failed -->
                        <!-- Start User Full Name Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Full Name') ?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="full" placeholder="Your Full Name" value="<?php echo $row['FullName']?>" class="form-control"/>
                            </div>
                        </div>
                        <!-- End User Full Name Failed -->
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
       
       echo '<h1 class="text-center member">'. lang('Update Member') . '</h1> ';
       echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Get Var From Form

            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            // Password Trick

            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            // Validate The form

            $formErrors = array();

            if (empty($user)) {
                $formErrors[] = 'Username Can\'t Be Empty';
    
            }
            if (empty($email)) {
                $formErrors[] = 'Email Can\'t Be Empty';
    
            }

            if (empty($name)) {
                $formErrors[] = 'Full Name Can\'t Be Empty';
    
            }

            foreach ($formErrors as $error) {
                echo $error . '</br>';
            }

            // Update The Database By This Info
            $stmt2 = $con->prepare("SELECT * FROM users WHERE UserName = ? AND UserID != ?");
            $stmt2->execute(array($user, $id));
            $count = $stmt2->rowCount();
            if ($count == 1) {
                $theMsg = "<div class='alert alert-danger'>Sorry This User Name Is Exist</div>";
                redirectHome($theMsg,'back');
            }else {
                $stmt = $con->prepare("UPDATE users SET UserName = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                $stmt->execute(array($user, $email, $name, $pass, $id));
                //Echo Done
                $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Updated') . '</h1></div>';
                redirectHome($theMsg,'back');
            }
        } else {
            $theMsg = '<div class="alert alert-danger">404 Not Found Or You Are Here Direct</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    
    }// End Update Page

    // Start Delete Page 
        elseif ($do == 'Delete') {// Delete Page
            echo '<h1 class="text-center member">'. lang('Delete Member') . '</h1> ';
            echo "<div class='container'>";
                // Check If GET Request  userid Is Numeric & Get The Integer Value Of It
                $userid = isset( $_GET['userid'] ) && is_numeric( $_GET['userid'] ) ? intval($_GET['userid']) : 0;
                // Select All Data Depend on This ID
                $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
                $stmt->execute(array($userid));
                $count = $stmt->rowCount();
                // If There's Such ID Show The Form
                if ($stmt->rowCount() > 0 ) {
                    $stmt= $con->prepare("DELETE FROM users WHERE UserID = :user");
                    $stmt->bindParam(":user", $userid);
                    $stmt->execute();
                    $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Deleted') . '</h1></div>'; 
                    redirectHome($theMsg,'members.php',1);
                } else {
                    $theMsg = '<div class="alert alert-danger"><h1>This ID Not Founed <strong>404</strong></h1></div>';
                    redirectHome($theMsg);
                }
            echo "</div>";
    }// End Delete Page
    // Start Activate Page 
    elseif ($do == 'Activate'){ // Activate Page
        echo '<h1 class="text-center member">'. lang('Activate Member') . '</h1> ';
        echo "<div class='container'>";
            // Check If GET Request  userid Is Numeric & Get The Integer Value Of It
            $userid = isset( $_GET['userid'] ) && is_numeric( $_GET['userid'] ) ? intval($_GET['userid']) : 0; 
            // Select All Data Depend on This ID
            $check = checkItem('userid', 'users', $userid); 
            // If There's Such ID Show The Form
            if ($check > 0 ) { 
                $stmt= $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                $stmt->execute(array($userid));
                $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Activated') . '</h1></div>'; 
                redirectHome($theMsg,'members.php',1);
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