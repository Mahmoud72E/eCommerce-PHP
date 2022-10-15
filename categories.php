<?php
    ob_start();
    session_start();
    include 'init.php'; 
?>
<div class="container">
    <h1 class="text-center"><?php echo str_replace('-', ' ', $_GET['pagename'] ) ?></h1>
    <div class="row">
        <?php
            $category = isset( $_GET['pageid'] ) && is_numeric( $_GET['pageid'] ) ? intval($_GET['pageid']) : 0;
            foreach (getItems('Cat_ID', $category) as $item) {
                echo "<div class='col-sm-6 col-md-3'>";
                    echo '<div class="img-thumbnail item-box">';
                        echo '<span class="price-tag">$'.$item['Price'].'</span>';
                        echo '<img class="img-fluid" src="electron.jpg" alt="" />';
                        echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>';
                            echo '<p>'.$item['Description'].'</p>';
                            echo '<div class="date">'.$item['Add_Date'].'</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        ?>
    </div>
</div>
    

<?php include $tpl . "footer.php"; ob_end_flush(); ?> 