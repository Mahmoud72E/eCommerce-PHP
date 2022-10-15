<?php

    /* 
    ================================================
    ==  Category Page
    ================================================
    */


    ob_start(); // Output Buffering Start

    session_start();

    $pageTitle  = 'Categories';

    if (isset($_SESSION['Username'])){
        
        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        
        if ($do == 'Manage') {

            $sort = 'ASC';
            $sort_array = array('ASC', 'DESC');
            if( isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
                $sort = $_GET['sort']; 
            }
            $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");
            $stmt2->execute();
            $cats = $stmt2->fetchAll(); ?>
            
            <h1 class="text-center manage-categories"><?php echo lang('Manage Categories')?></h1> 
            <div class="container categories">
                <div class="card card-default">
                    <div class="card-header">
                        <i class='fa fa-edit'></i><?php echo lang('Manage Categories')?>
                        <div class = "option float-end">
                           <i class='fa fa-star'></i><?php echo lang('Ordering:')?> [
                            <a class="<?php echo ($sort == 'ASC')?'active':''?>" href="?sort=ASC"><?php echo lang('Asc')?></a> |
                            <a class="<?php echo ($sort == 'DESC')?'active':''?>" href="?sort=DESC"></i><?php echo lang('Desc')?></a> ]
                            <i class='fa fa-eye'></i><?php echo lang('View:')?> [ <span date-view="full" class="active"><?php echo lang('Full')?></span> | <span date-view="classic"><?php echo lang('Classic')?></span> ]
                        </div>
                    </div>
                    <div class="card-body">
                        <?php 
                            foreach ($cats as $cat) { // $describe = 
                                echo "<div class='cat'>";
                                    echo "<div class='hidden-buttons'>";
                                        echo "<a href='categories.php?do=Edit&catid=". $cat['ID'] ."' class='btn btn-primary'><i class='fa fa-edit'></i> " . lang('Edit') . "</a>";
                                        echo "<a href='categories.php?do=Delete&catid=". $cat['ID'] ."' class='confirm btn btn-danger'><i class='fa fa-x'></i> " . lang('Delete') . "</a>";
                                    echo "</div>";
                                    echo "<h3>" . $cat['Name'] . '</h3>';
                                    echo "<div class='full-view'>";
                                        echo "<p>" . ($cat['Description'] == ''? lang('There\'s No Description Here'):$cat['Description']) . '</p>';
                                        echo ($cat['Visibility'] == 1 ? '<span class="visibility"><i class="fa fa-eye"></i>'.lang('Hidden').'</span>': '');
                                        echo ($cat['Allow_Comment'] == 1 ? '<span class="commenting"><i class="fa fa-x"></i>' .lang('Comment Disabled'). '</span>': '');
                                        echo ($cat['Allow_Ads'] == 1 ? '<span class="advertieses"><i class="fa fa-x"></i>' . lang('Ads Disabled') .'</span>': '');
                                    echo "</div>";
                                echo "</div>";
                                // Get Child Cats
                                $childCats = getCat('WHERE parent ="' . $cat['ID'] .'"');
                                if (!empty($childCats)) {
                                echo "<h4 class='child-head'>Child Categories</h4>";
                                echo "<ul class='list-unstyled child-cats'>";
                                    foreach ($childCats as $c) {
                                        echo "<li><a href='categories.php?do=Edit&catid=". $c['ID'] ."'>". $c['Name'] ."</a>
                                                <a href='categories.php?do=Delete&catid=". $c['ID'] ."' class='confirm btn btn-danger btn-sm'><i class='fa fa-x'></i> " . lang('Delete') . "</a>
                                        </li>";
                                    }
                                echo "</ul>";
                                }
                                echo "<hr>";
                            }
                        ?>
                    </div>
                </div>
                <a href="categories.php?do=Add" class="add-category btn btn-primary"><i class="fa fa-plus"></i> <?php echo lang('Add Category')?></a>
            </div>

            <?php
        } elseif ($do == 'Add'){ ?>

            <h1 class="text-center member"><?php echo lang('New Category')?></h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- Start Name Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Name')?></label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" placeholder="Name Of Category" class="form-control" required='required' />
                        </div>
                    </div>
                    <!-- End Name Failed -->
                    <!-- Start Description Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Description')?></label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" placeholder="Describe The Category" class="form-control"/>
                        </div>
                    </div>
                    <!-- End Description Failed -->
                    <!-- Start Ordering Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Ordering')?></label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="ordering" placeholder="Number To Arrange The Categories" class="form-control"/>
                        </div>
                    </div>
                    <!-- End User Ordering  Failed -->
                    <!-- Start Category Type -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Parent ?</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
                                    $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
                                    foreach ($cats as $cat) {
                                        echo "<option value='". $cat['ID'] ."'>" . $cat['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Catgory Type -->
                    <!-- Start Visibility Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Visible') ?></label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                                <label for="vis-yes"><?php echo lang('Yes')?></label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" />
                                <label for="vis-no"><?php echo lang('No')?></label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visibility Failed -->
                    <!-- Start Commenting Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Allow Commenting') ?></label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked />
                                <label for="com-yes"><?php echo lang('Yes')?></label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1" />
                                <label for="com-no"><?php echo lang('No')?></label>
                            </div>
                        </div>
                    </div>
                    <!-- End Commenting Failed -->
                    <!-- Start Ads Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Allow Ads') ?></label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="ad-yes" type="radio" name="ads" value="0" checked />
                                <label for="ad-yes"><?php echo lang('Yes')?></label>
                            </div>
                            <div>
                                <input id="ad-no" type="radio" name="ads" value="1" />
                                <label for="ad-no"><?php echo lang('No')?></label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads Failed -->
                    <!-- Start Submit Failed -->
                    <div class="form-group">
                        <div class="lcol-sm-offset-2 col-sm-10">
                            <input type="submit" value="<?php echo lang('Add Category')?>" class="btn btn-primary btn-block memberbtn"/>
                        </div>
                    </div>
                    <!-- End Submit Failed -->
                </form>
            </div>

            <?php
        } elseif ($do == 'Insert') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<h1 class="text-center member">'. lang('Insert Category') . '</h1>';
                echo "<div class='container'>";
    
                 // Get Var From Form

                 $name    = $_POST['name'];
                 $desc    = $_POST['description'];
                 $order   = $_POST['ordering'];
                 $parent  = $_POST['parent'];
                 $visible = $_POST['visibility'];
                 $comment = $_POST['commenting'];
                 $ads     = $_POST['ads'];

                 // Validate The form
     
                 $formErrors = array();
     
                 if (empty($name)) {
                     $formErrors[] = lang('The Name Of Category Can\'t Be Empty');
                 }
                 foreach ($formErrors as $error) {
                     echo $error . '</br>';
                 }
                 // Check If Category Exist in Database
                 $check = checkItem('Name','categories', $name);

                 if ($check == 1){
                    $theMsg = '<div class="alert alert-danger"><h2>'. lang('Sorry This [Category Name] Is Exist Try Antother one') . '</h2></div>';
                    redirectHome($theMsg,'back');
                 }else {
                    // Insert Category info in database
        
                    $stmt = $con->prepare("INSERT INTO 
                                                        categories(Name, Description, parent, Ordering, Visibility, Allow_Comment, Allow_Ads)
                                                        VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads) ");
                    $stmt->execute(array(
                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zparent'   => $parent,
                        'zorder'    => $order,
                        'zvisible'  => $visible,
                        'zcomment'  => $comment,
                        'zads'      => $ads

                        
                        ));
        
                    // Echo Done
        
                    $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Inserted') .'</h1></div>';
                    redirectHome($theMsg,'categories.php');
                 }
             } else {
                 $theMsg = '<div class="alert alert-danger">404 Not Found Or You Are Here Derict</div>';
                 redirectHome($theMsg);
             }
             echo "</div>";

        } elseif ($do == 'Edit') { 

            $catid = isset( $_GET['catid'] ) && is_numeric( $_GET['catid'] ) ? intval($_GET['catid']) : 0;
            
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
            $stmt->execute(array($catid));
            $cat = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0 ) { ?> 

            <h1 class="text-center member"><?php echo lang('Edit Category')?></h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="catid" value="<?php echo $catid ?>">
                    <!-- Start Name Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Name')?></label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" placeholder="Edit Your Category Name" class="form-control" required='required'  value="<?php echo $cat['Name'] ?>"/>
                        </div>
                    </div>
                    <!-- End Name Failed -->
                    <!-- Start Description Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Description')?></label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" placeholder="Edit Your Description" class="form-control" value="<?php echo $cat['Description'] ?>"/>
                        </div>
                    </div>
                    <!-- End Description Failed -->
                    <!-- Start Ordering Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Ordering')?></label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="ordering" placeholder="Edit Your Arrange To Sort Categories" class="form-control" value="<?php echo $cat['Ordering'] ?>"/>
                        </div>
                    </div>
                    <!-- End User Ordering  Failed -->
                    <!-- Start Category Type -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Parent?</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
                                    $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0");
                                    $stmt2->execute();
                                    $allCats = $stmt2->fetchAll();
                                    foreach ($allCats as $c) {
                                        echo "<option value='". $c['ID'] ."'";
                                            if ($cat['parent'] == $c['ID']) { echo 'selected';}
                                        echo ">" . $c['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Catgory Type -->
                    <!-- Start Visibility Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Visible') ?></label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" <?php echo $cat['Visibility'] == 0 ? 'Checked' : '' ?> />
                                <label for="vis-yes"><?php echo lang('Yes')?></label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" <?php echo $cat['Visibility'] == 1 ? 'Checked' : '' ?>/>
                                <label for="vis-no"><?php echo lang('No')?></label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visibility Failed -->
                    <!-- Start Commenting Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Allow Commenting') ?></label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" <?php echo $cat['Allow_Comment'] == 0 ? 'Checked' : '' ?>/>
                                <label for="com-yes"><?php echo lang('Yes')?></label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1" <?php echo $cat['Allow_Comment'] == 1 ? 'Checked' : '' ?>/>
                                <label for="com-no"><?php echo lang('No')?></label>
                            </div>
                        </div>
                    </div>
                    <!-- End Commenting Failed -->
                    <!-- Start Ads Failed -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo lang('Allow Ads') ?></label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="ad-yes" type="radio" name="ads" value="0" <?php echo $cat['Allow_Ads'] == 0 ? 'Checked' : '' ?>/>
                                <label for="ad-yes"><?php echo lang('Yes')?></label>
                            </div>
                            <div>
                                <input id="ad-no" type="radio" name="ads" value="1" <?php echo $cat['Allow_Ads'] == 1 ? 'Checked' : '' ?> />
                                <label for="ad-no"><?php echo lang('No')?></label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads Failed -->
                    <!-- Start Submit Failed -->
                    <div class="form-group">
                        <div class="lcol-sm-offset-2 col-sm-10">
                            <input type="submit" value="<?php echo lang('Update')?>" class="btn btn-primary btn-block memberbtn"/>
                        </div>
                    </div>
                    <!-- End Submit Failed -->
                </form>
            </div>
            <?php 
                } else { // Error Masage If ID Not Exisit
                    $theMsg = '<div class="alert alert-danger"><h1>Erorr 404 Not Found This ID<h1></div>';
                    redirectHome($theMsg,null, 5);
                };
        // End Edit Page 
        } elseif ($do == 'Update') {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<h1 class="text-center member">'. lang('Update Category') . '</h1>';
                echo "<div class='container'>";
    
                 // Get Var From Form
                 $id      = $_POST['catid'];
                 $name    = $_POST['name'];
                 $desc    = $_POST['description'];
                 $order   = $_POST['ordering'];
                 $parent  = $_POST['parent'];
                 $visible = $_POST['visibility'];
                 $comment = $_POST['commenting'];
                 $ads     = $_POST['ads'];

                 // Validate The form
     
                 $formErrors = array();
     
                 if (empty($name)) {
                     $formErrors[] = lang('The Name Of Category Can\'t Be Empty');
                 }
                 foreach ($formErrors as $error) {
                     echo $error . '</br>';
                 }
                 // Check If Category Exist in Database


                // Update Category info in database
        
                $stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?, parent = ?, Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads =? WHERE ID = ?");
                $stmt->execute(array($name, $desc, $parent, $order, $visible, $comment, $ads, $id));
    
                // Echo Done
    
                $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Updated') .'</h1></div>';
                redirectHome($theMsg,'back');
            } else {
                 $theMsg = '<div class="alert alert-danger">404 Not Found Or You Are Here Derict</div>';
                 redirectHome($theMsg);
            }
             echo "</div>";


        } elseif ($do == 'Delete') {

            echo "<div class='container'>";
                // Check If GET Request  catid Is Numeric & Get The Integer Value Of It
                $catid = isset( $_GET['catid'] ) && is_numeric( $_GET['catid'] ) ? intval($_GET['catid']) : 0;
                // Select All Data Depend on This ID
                $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ");
                $stmt->execute(array($catid));
                $count = $stmt->rowCount();
                // If There's Such ID Show The Form
                if ($stmt->rowCount() > 0 ) {
                    $stmt= $con->prepare("DELETE FROM categories WHERE ID = :zid");
                    $stmt->bindParam(":zid", $catid);
                    $stmt->execute();
                    $theMsg = '<div class="alert alert-success"><h1>' . $stmt->rowCount() . ' ' . lang('Record Deleted') . '</h1></div>'; 
                    redirectHome($theMsg,'back',1);
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