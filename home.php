<?php
   session_start();
   $pageTitle = 'Home' ;
   include 'init.php';

   


   if(isset($_SESSION['customer'])){


    $selectprod = $con->prepare("
    SELECT product, MIN(cost) AS min_cost
    FROM product
    GROUP BY product;
        ");
$selectprod->execute();
$prods = $selectprod->fetchAll();




?>
<div class="container-fluid con-home ">
    <div class="row">
        <div class="col-sm-8">
            <h5></h5>
            <div class="row">
                <div class="product mb-3 col-sm">
                    <h3></h3>
                    <div class=" input-box ">
                        <input class="form-control" id="filterInput" type="text" placeholder=" ">
                        <span for="filterInput">Search</span>
                    </div>
                    <br>
                    <div class="list-group prodlist" id="prodlist">
                        <?php foreach ($prods as $prod){  ?>
                        <div id="product" class="product">
                            <button class="d-flex justify-content-between select_country" id="" >
                                <div>
                                    <img src="admin/images/<?php echo $prod['product'].'.jpg';?>" style="width: 20px;"
                                        alt="">
                                    <span name=<?php echo $prod['product'];?>
                                        id=""><?php echo $prod['product'];?></span>
                                </div>
                                <div class=" animate__animated animate__flash pric_icon" data-text="">
                                <span class='maxcost'><p>min</p><?php echo $prod['min_cost'] ; ?> $</span> 
                                </div>
                            </button>

                        </div>
                        <?php }?>
                    </div>
                </div>
                <div class=" col-sm">
                    <h3>choose country</h3>
                    <div class="country">

                    </div>

                </div>
            </div>
        </div>

        <div class="col-sm-4 note">
            <h1>easy to make number</h1>
            
        </div>
    </div>
</div>


<?php
      


      include  $tpl . 'footer.php';  
      
   } else{
            header('location: index.php');
   }
   ob_end_flush();