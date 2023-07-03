<?php
   session_start();
   $pageTitle = 'Home' ;
   include 'init.php';

   if(isset($_SESSION['customer'])){
    $gethistory = $con->prepare("SELECT * FROM sms WHERE userid = '$userid' ORDER BY date DESC");
    $gethistory->execute();
    $historis = $gethistory->fetchAll();

        ?>
<div class="container">
    <div class="table-responsive table-striped">
        <table class="table">
            <thead>
                <tr>
                    <th>orderID</th>
                    <th>product</th>
                    <th>country</th>
                    <th>price</th>
                    <th>phone</th>
                    <th>sms</th>
                    <th>status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historis as $history) { ?>
                <tr>
                    <a href="active.php?orderid=<?php echo $history['orderid']; ?>">

                        <td><?php echo $history['orderid']; ?></td>
                        <td><?php echo $history['product']; ?></td>
                        <td><?php echo $history['country']; ?></td>
                        <td><?php echo $history['price']; ?></td>
                        <td><?php echo $history['phone']; ?></td>
                        <td><?php echo $history['sms']; ?></td>
                            <?php  $status = $history['status']==='canceled' ?  '<i class="fas  fa-xmark"></i> cancel' : 
                            ($history['status']==='pendding' ? '<i class="fas fa-solid fa-hourglass-half"></i> pendding': '<i class="fa-solid fa-circle-check" style="color: #008a02;"></i> complet') ; ?>
                        <td style="    font-size: 0.8rem;"><?php echo $status ;?></td>
                    </a>
                </tr>


                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php



      


   include  $tpl . 'footer.php';  
   
} else{
         header('location: index.php');
}
ob_end_flush();