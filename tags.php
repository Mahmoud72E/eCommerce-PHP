<?php
    ob_start();
    session_start();
    include 'init.php'; 
?>
<div class="container">
    <div class="row">
        <?php
            if (isset($_GET['name'])) {
                $tag = $_GET['name'];
                echo "<h1 class='text-center'>" . strtoupper($tag) . "</h1>";
                $getItems = $con->prepare("SELECT * FROM items WHERE tags LIKE '%$tag%' AND Approve = 1 ORDER BY Item_ID DESC");
                $getItems->execute();
                $tagItems = $getItems->fetchAll();
                foreach ($tagItems as $item) {
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
            }
        ?>
    </div>
</div>
    

<?php include $tpl . "footer.php"; ob_end_flush(); ?> 