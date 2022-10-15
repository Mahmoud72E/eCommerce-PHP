<?php

    /* 
    ================================================
    ==  Items Page
    ================================================
    */


    ob_start(); // Output Buffering Start

    session_start();

    $pageTitle  = 'Items';

    if (isset($_SESSION['Username'])){
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        
        if ($do == 'Manage') {
        // Select All users Except Admin
        $stmt = $con->prepare("SELECT items.*, categories.Name AS category_name, users.UserName FROM items
                               INNER JOIN categories ON categories.ID = items.Cat_ID
                               INNER JOIN users ON users.UserID = items.Member_ID ORDER BY Item_ID DESC");
        $stmt->execute();
        $items = $stmt->fetchAll();
    ?> 
    
    <h1 class="text-center member"><?php echo lang('Manage Items')?></h1>
    <div class="container">
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
            <tr>
                <td><?php echo lang('#ID')?></td>
                <td><?php echo lang('Name')?></td>
                <td><?php echo lang('Description')?></td>
                <td><?php echo lang('Price')?></td>
                <td><?php echo lang('Adding Date')?></td>
                <td><?php echo lang('Country')?></td>
                <td><?php echo lang('Category')?></td>
                <td><?php echo lang('User Name')?></td>
                <td><?php echo lang('Control')?></td>
            </tr>
            <?php 
                foreach($items as $item){
                    echo "<tr>";
                        echo "<td>". $item['Item_ID'] . "</td>";
                        echo "<td>". $item['Name'] . "</td>";
                        echo "<td>". $item['Description'] . "</td>";
                        echo "<td>". $item['Price'] . "</td>";
                        echo "<td>". $item['Add_Date'] ."</td>";                        
                        echo "<td>". $item['Country_Made'] ."</td>";
                        echo "<td>". $item['category_name'] ."</td>";
                        echo "<td>". $item['UserName'] ."</td>";
                        echo "<td> <a href='Items.php?do=Edit&itemid=". $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>". lang('Edit') . "</a>
                                   <a href='Items.php?do=Delete&itemid=". $item['Item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-x'></i>" . lang('Delete') . "</a>";
                                   if ($item['Approve'] == 0) {
                                    echo "<a href='Items.php?do=Approve&itemid=". $item['Item_ID'] . "' class='btn btn-info activate'><i class='fa fa-o'></i>" . lang('Approve') . "</a>";
                                 }                        
                        echo  "</td>";
                    echo "</tr>";
                }
            ?>
            </table>
        </div>  
        <a href='Items.php?do=Add' class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> <?php echo lang('New Item')?> </a>
    </div>

        <?php } 
         elseif ($do == 'Add'){ ?>
            <h1 class="text-center member"><?php echo lang('New Item')?></h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- Start Name Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Name')?></label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" placeholder="The Name of Item" class="form-control" required='required' />
                        </div>
                    </div>
                    <!-- End Name Failed -->
                    <!-- Start Description Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Description')?></label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" placeholder="Descripe The Item" class="form-control" required='required'/>
                        </div>
                    </div>
                    <!-- End Description Failed -->
                    <!-- Start Price Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Price')?></label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="price" placeholder="What is The Price ?" class="form-control" required='required'/>
                        </div>
                    </div>
                    <!-- End Price Failed -->
                    <!-- Start Country Made Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Country Made')?></label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="country" placeholder="Where Item Made?" class="form-control" required='required'/>
                        </div>
                    </div>
                    <!-- End Country Made Failed -->
                    <!-- Start Status Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Status')?></label>
                        <div class="col-sm-10 col-md-4">
                            <select name='status'>
                                <option value='0'>...</option>
                                <option value='1'>New</option>
                                <option value='2'>Like New</option>
                                <option value='3'>Used</option>
                                <option value='4'>Old</option>
                                <option value='5'>Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Failed -->
                    <!-- Start Members Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Member')?></label>
                        <div class="col-sm-10 col-md-4">
                            <select name='member'>
                                <option value='0'>...</option>
                                <?php
                                    $stmt= $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach ($users as $user) {
                                        echo "<option value='". $user['UserID'] ."'>" . $user['UserName'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Failed -->    
                    <!-- Start Categories Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Category')?></label>
                        <div class="col-sm-10 col-md-4">
                            <select name='category'>
                                <option value='0'>...</option>
                                <?php
                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
                                    foreach ($cats as $cat) {
                                        echo "<option value='". $cat['ID'] ."'>" . $cat['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Failed -->  
                    <!-- Start Tags Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="tags" placeholder="Separate Tags With Comma (,)" class="form-control"/>
                        </div>
                    </div>
                    <!-- End Tags Failed -->               
                    <!-- Start Submit Failed -->
                    <div class="form-group">
                        <div class="lcol-sm-offset-2 col-sm-10">
                            <input type="submit" value="<?php echo lang('Add Item')?>" class="btn btn-primary btn-block btn-sm memberbtn"/>
                        </div>
                    </div>
                    <!-- End Submit Failed -->
                </form>
            </div>

            <?php
        } elseif ($do == 'Insert') { // Start Insert Page
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<h1 class="text-center member">'. lang('Insert Item') . '</h1>';
                echo "<div class='container'>";
    
                 // Get Var From Form

                 $name     = $_POST['name'];
                 $desc     = $_POST['description'];
                 $price    = $_POST['price'];
                 $country  = $_POST['country'];
                 $status   = $_POST['status'];
                 $member   = $_POST['member'];
                 $cat      = $_POST['category'];
                 $tags     = $_POST['tags'];

                 // Validate The form
     
                 $formErrors = array();
     
                 if (empty($name)) {
                     $formErrors[] = 'Name Can\'t Be Empty';
         
                 }
                 if (empty($desc)) {
                    $formErrors[] = 'Description Can\'t Be Empty';
        
                }
                 if (empty($price)) {
                     $formErrors[] = 'Price Can\'t Be Empty';
         
                 }
     
                 if (empty($country)) {
                     $formErrors[] = 'Made Country Can\'t Be Empty';
         
                 }
                 if ($status == 0) {
                    $formErrors[] = 'You Must Chosse The Status';
        
                } 
                if ($member == 0) {
                    $formErrors[] = 'You Must Chosse The Member';
        
                }  
                if ($cat == 0) {
                    $formErrors[] = 'You Must Chosse The Category';
        
                }      
                 foreach ($formErrors as $error) {
                     echo $error . '</br>';
                 }
                 
                // Insert Userinfo in database
    
                $stmt = $con->prepare("INSERT INTO 
                                                    items(Name, Description, Price, Country_Made,  Status, Add_Date, Cat_ID, Member_ID, Approve, tags)
                                                    VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, 1, :ztags)");
                $stmt->execute(array(
                    'zname'      => $name,
                    'zdesc'      => $desc,
                    'zprice'     => $price,
                    'zcountry'   => $country,
                    'zstatus'    => $status,
                    'zcat'       => $cat,
                    'zmember'    => $member,
                    'ztags'      => $tags
                ));
    
                // Echo Done
    
                $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Inserted') .'</h1></div>';
                redirectHome($theMsg,'Items.php');
             } else {
                 $theMsg = '<div class="alert alert-danger">404 Not Found Or You Are Here Derict</div>';
                 redirectHome($theMsg);
             }
             echo "</div>";
             // End Insert Page

        } elseif ($do == 'Edit') {  
            $itemid = isset( $_GET['itemid'] ) && is_numeric( $_GET['itemid'] ) ? intval($_GET['itemid']) : 0;

            $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
            $stmt->execute(array($itemid));
            $item = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0 ) { ?> 
                <h1 class="text-center member"><?php echo lang('Edit Item')?></h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
                        <!-- Start Name Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Name')?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="name" placeholder="The Name of Item" class="form-control" required='required' value='<?php echo $item['Name'] ?>'/>
                            </div>
                        </div>
                        <!-- End Name Failed -->
                        <!-- Start Description Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Description')?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="description" placeholder="Descripe The Item" class="form-control" required='required' value="<?php echo $item['Description'] ?>"/>
                            </div>
                        </div>
                        <!-- End Description Failed -->
                        <!-- Start Price Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Price')?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="price" placeholder="What is The Price ?" class="form-control" required='required' value="<?php echo $item['Price'] ?>"/>
                            </div>
                        </div>
                        <!-- End Price Failed -->
                        <!-- Start Country Made Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Country Made')?></label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="country" placeholder="Where Item Made?" class="form-control" required='required' value="<?php echo $item['Country_Made'] ?>"/>
                            </div>
                        </div>
                        <!-- End Country Made Failed -->
                        <!-- Start Status Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Status')?></label>
                            <div class="col-sm-10 col-md-4">
                                <select name='status'>
                                    <option value='1' <?php echo $item['Status'] == 1 ? 'selected' : '' ?>>New</option>
                                    <option value='2' <?php echo $item['Status'] == 2 ? 'selected' : '' ?>>Like New</option>
                                    <option value='3' <?php echo $item['Status'] == 3 ? 'selected' : '' ?>>Used</option>
                                    <option value='4' <?php echo $item['Status'] == 4 ? 'selected' : '' ?>>Old</option>
                                    <option value='5' <?php echo $item['Status'] == 5 ? 'selected' : '' ?>>Very Old</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Status Failed -->
                        <!-- Start Members Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Member')?></label>
                            <div class="col-sm-10 col-md-4">
                                <select name='member'>
                                    <?php
                                        $stmt= $con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users = $stmt->fetchAll();
                                        foreach ($users as $user) {
                                            echo "<option value='". $user['UserID'] . "'"; echo $item['Member_ID'] == $user['UserID'] ? 'selected' : ''; echo ">" . $user['UserName'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Members Failed -->    
                        <!-- Start Categories Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><?php echo lang('Category')?></label>
                            <div class="col-sm-10 col-md-4">
                                <select name='category'>
                                    <?php
                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                        $stmt2->execute();
                                        $cats = $stmt2->fetchAll();
                                        foreach ($cats as $cat) {
                                            echo "<option value='". $cat['ID'] ."'"; echo $item['Cat_ID'] == $cat['ID'] ? 'selected' : ''; echo ">" . $cat['Name'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Categories Failed -->  
                        <!-- Start Tags Failed -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tags</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="tags" placeholder="Separate Tags With Comma (,)" class="form-control"  value="<?php echo $item['tags'] ?>"/>
                            </div>
                        </div>
                        <!-- End Tags Failed -->                
                        <!-- Start Submit Failed -->
                        <div class="form-group">
                            <div class="lcol-sm-offset-2 col-sm-10">
                                <input type="submit" value="<?php echo lang('Update')?>" class="btn btn-primary btn-block memberbtn"/>
                            </div>
                        </div>
                        <!-- End Submit Failed -->
                    </form>
            <?php
             // Select All users Except Admin
            $stmt = $con->prepare("SELECT comments.*, users.UserName FROM comments
                                        INNER JOIN users ON users.UserID = comments.user_id
                                        WHERE item_id = ?");
            $stmt->execute(array($itemid));
            $rows = $stmt->fetchAll();
            if (! empty($rows)) {
        ?> 
        
        <h1 class="text-center member"><?php echo lang('Manage Comments') . ' [ ' . $item['Name'] . ' ] '?></h1>
        <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
                <tr>
                    <td><?php echo lang('Comment')?></td>
                    <td><?php echo lang('User Name')?></td>
                    <td><?php echo lang('Add Date')?></td>
                    <td><?php echo lang('Control')?></td>
                </tr>
                <?php 
                    foreach($rows as $row){
                        echo "<tr>";
                            echo "<td>". $row['comment'] . "</td>";
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
        <?php } ?>
        </div>
        <?php 
            } else { // Error Masage If ID Not Exisit
                $theMsg = '<div class="alert alert-danger"><h1>Erorr 404 Not Found This ID<h1></div>';
                redirectHome($theMsg,null, 5);
            };
       // End Edit Page 
        // Start Update Page
        } elseif ($do == 'Update') {

            echo '<h1 class="text-center member">'. lang('Update Item') . '</h1> ';
            echo "<div class='container'>";
             if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                 
                $id       = $_POST['itemid'];
                $name     = $_POST['name'];
                $desc     = $_POST['description'];
                $price    = $_POST['price'];
                $country  = $_POST['country'];
                $status   = $_POST['status'];
                $cat      = $_POST['category'];
                $member   = $_POST['member'];
                $tags     = $_POST['tags'];


                // Validate The form
    
                $formErrors = array();
    
                if (empty($name)) {
                    $formErrors[] = 'Name Can\'t Be Empty';
        
                }
                if (empty($desc)) {
                   $formErrors[] = 'Description Can\'t Be Empty';
       
               }
                if (empty($price)) {
                    $formErrors[] = 'Price Can\'t Be Empty';
        
                }
    
                if (empty($country)) {
                    $formErrors[] = 'Made Country Can\'t Be Empty';
        
                }
                foreach ($formErrors as $error) {
                    echo $error . '</br>';
                }
                 // Update The Database By This Info
     
                 $stmt = $con->prepare("UPDATE 
                                                items 
                                        SET
                                                Name = ?, 
                                                Description = ?,
                                                Price = ?, 
                                                Country_Made = ?, 
                                                Status = ?, 
                                                Cat_ID = ?, 
                                                Member_ID = ?,
                                                tags = ?
                                        WHERE 
                                                Item_ID = ?");
                 $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member,  $tags, $id));
     
                 //Echo Done
     
                 $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Updated') . '</h1></div>';
                 redirectHome($theMsg,'back');
             } else {
                 $theMsg = '<div class="alert alert-danger">404 Not Found Or You Are Here Direct</div>';
                 redirectHome($theMsg);
             }
             echo "</div>";
         
    // End Update Page

        } elseif ($do == 'Delete') {
            echo '<h1 class="text-center member">'. lang('Delete Item') . '</h1> ';
            echo "<div class='container'>";
                // Check If GET Request itemid Is Numeric & Get The Integer Value Of It
                $itemid = isset( $_GET['itemid'] ) && is_numeric( $_GET['itemid'] ) ? intval($_GET['itemid']) : 0;
                // Select All Data Depend on This ID
                $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
                $stmt->execute(array($itemid));
                $count = $stmt->rowCount();
                // If There's Such ID Show The Form
                if ($stmt->rowCount() > 0 ) {
                    $stmt= $con->prepare("DELETE FROM items WHERE Item_ID = :item");
                    $stmt->bindParam(":item", $itemid);
                    $stmt->execute();
                    $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Deleted') . '</h1></div>'; 
                    redirectHome($theMsg,'Items.php',1);
                } else {
                    $theMsg = '<div class="alert alert-danger"><h1>This ID Not Founed <strong>404</strong></h1></div>';
                    redirectHome($theMsg);
                }
        } elseif ($do == 'Approve'){
            echo '<h1 class="text-center member">'. lang('Approve Item') . '</h1> ';
            echo "<div class='container'>";
                // Check If GET Request itemid Is Numeric & Get The Integer Value Of It
                $itemid = isset( $_GET['itemid'] ) && is_numeric( $_GET['itemid'] ) ? intval($_GET['itemid']) : 0; 
                // Select All Data Depend on This ID
                $check = checkItem('Item_ID', 'items', $itemid); 
                // If There's Such ID Show The Form
                if ($check > 0 ) { 
                    $stmt= $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
                    $stmt->execute(array($itemid));
                    $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Approved') . '</h1></div>'; 
                    redirectHome($theMsg,'Items.php',6);
                } else {
                    $theMsg = '<div class="alert alert-danger"><h1>This ID Not Founed <strong>404</strong></h1></div>';
                    redirectHome($theMsg);
                }
            echo "</div>";

        }

        include $tpl . "footer.php";
        
    } else {
            header('Location: index.php');
            exit;
        }
        ob_end_flush(); //Release The Output

?>