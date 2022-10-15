<?php
    ob_start();
    session_start();
    $pageTitle = "Creat New Item";
    include 'init.php';
    if (isset($_SESSION['user'])) {

     if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
        $formErrors = array();

        $name       = filter_var( $_POST['name'], FILTER_SANITIZE_STRING);
        $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

        if (strlen($name) < 4) {
            $formErrors[] = 'Item Name Have to Be 4 Char Try Again';
        }
        if (strlen($desc) < 10) {
            $formErrors[] = 'Item Description Have to Be 10 Char Try Again';
        }
        if (strlen($country) < 2) {
            $formErrors[] = 'Item Country Have to Be 3 Char Try Again';
        }
        if (empty($price)) {
            $formErrors[] = 'Item Price Cant\'t Be Empty';
        }
        if (empty($status)) {
            $formErrors[] = 'Item Status Cant\'t Be Empty';
        }
        if (empty($category)) {
            $formErrors[] = 'Item Category Cant\'t Be Empty';
        }
        
        if (empty($formErrors)) {
           
           $stmt = $con->prepare("INSERT INTO 
                                        items(Name, Description, tags, Price, Country_Made,  Status, Add_Date, Cat_ID, Member_ID)
                                        VALUES(:zname, :zdesc, :ztags, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember)");
            $stmt->execute(array(
            'zname'      => $name,
            'zdesc'      => $desc,
            'ztags'      => $tags,
            'zprice'     => $price,
            'zcountry'   => $country,
            'zstatus'    => $status,
            'zcat'       => $category,
            'zmember'    => $_SESSION['uid']
            ));
            if ($stmt) {
                $succesMsg = 'Item Addeed Successfuly';
            }
        }
     }
?>
<h1 class="text-center"><?php echo $pageTitle ?></h1>
<div class="create-ad block">
    <div class="container">
        <div class="card card-primary">
            <div class="card-header"><?php echo $pageTitle ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <!-- Start Name Failed -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo lang('Name')?></label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="name" placeholder="The Name of Item" class="form-control live-name" required='required' />
                                </div>
                            </div>
                            <!-- End Name Failed -->
                            <!-- Start Description Failed -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="description" placeholder="Descripe The Item" class="form-control live-desc" required='required'/>
                                </div>
                            </div>
                            <!-- End Description Failed -->
                            <!-- Start Price Failed -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo lang('Price')?></label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="price" placeholder="What is The Price ?" class="form-control live-price" required='required'/>
                                </div>
                            </div>
                            <!-- End Price Failed -->
                            <!-- Start Country Made Failed -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo lang('Country Made')?></label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="country" placeholder="Where Item Made?" class="form-control" required='required'/>
                                </div>
                            </div>
                            <!-- End Country Made Failed -->
                            <!-- Start Status Failed -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo lang('Status')?></label>
                                <div class="col-sm-10 col-md-9">
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
                            <!-- Start Categories Failed -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label"><?php echo lang('Category')?></label>
                                <div class="col-sm-10 col-md-9">
                                    <select name='category'>
                                        <option value='0'>...</option>
                                        <?php
                                            $cats = getAllFrom('categories', 'ID');
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
                                <label class="col-sm-3 control-label">Tags</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="tags" placeholder="Separate Tags With Comma (,)" class="form-control"/>
                                </div>
                            </div>
                            <!-- End Tags Failed -->                  
                            <!-- Start Submit Failed -->
                            <div class="form-group form-group-lg">
                                <div class="lcol-sm-offset-2 col-sm-10">
                                    <input type="submit" value="<?php echo lang('Add Item')?>" class="btn btn-primary btn-block btn-sm memberbtn"/>
                                </div>
                            </div>
                            <!-- End Submit Failed -->
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="img-thumbnail item-box live-preview">
                            <span class="price-tag">$0</span>
                            <img class="img-fluid" src="electron.jpg" alt="" />
                            <div class="caption">
                                <h3>Title</h3>
                                <p>Description</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start Looping Errors -->
            <div>
                <?php 
                    if (! empty($formErrors)) {
                        foreach ($formErrors as $error) {
                            echo '<div class="alert alert-danger">'. $error . '</div>';
                        }
                    }
                    if (isset($succesMsg)) {
                        echo '<div class="alert alert-success">'. $succesMsg .'</div>';
                    }
                ?>
            </div>
            <!-- END Looping Errors -->
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