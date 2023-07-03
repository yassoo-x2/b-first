<?php
   ob_start();
   session_start();
   if(isset($_SESSION['adm'])){
      header('location: admin/home.php');  //if user name rigester befor go to adminHome.php
      exit();
   }
   elseif(isset($_SESSION['customer'])){
      header('location: home.php');  //if user name rigester befor go to adminHome.php
      exit();
   }
   $pageTitle = 'log in' ;
   $nonavbar = '';
   include 'init.php';
   // Save login information as a cookie for 1 year
if (isset($_POST['remember'])) {
   $loginData = [
       'username' => $_POST['user'],
       'password' => $_POST['pass']
   ];
   $cookieValue = json_encode($loginData);
   $expirationTime = time() + (86400 * 365); // 1 year
   setcookie('login_info', $cookieValue, $expirationTime, '/');
}




  //regster post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['reg_form'])) {
        // Handle registration form submission
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        if ($password !== $confirmPassword) {
            echo "Passwords do not match.";
        } else {
            // Check if the username, email, or phone number is already registered
            $stmt = $con->prepare("SELECT * FROM login WHERE username = '$username' OR email = '$email' OR phone = '$phone'");
            $stmt->execute();
            $rowCount = $stmt->rowCount();
    
            if ($rowCount > 0) {
                header('location:index.php?alreadyreg=true');
                exit();
              
            } else {
                // Insert the data into the database
                $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    
                $insertStmt = $con->prepare("INSERT INTO login (username, email, phone, password,groupID) VALUES (?, ?, ?, ?,'0')");
                $insertStmt->execute([$username, $email, $phone, $hashPassword]);
                
                $_SESSION['customer'] = $username;
                header('location: home.php?welcom=true');  
                exit();
            }
        }

    } elseif (isset($_POST['loginForm'])) {
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Check if username exists in the database
        $stmt = $con->prepare("SELECT password, groupID FROM login WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            $storedPassword = $row['password'];
            $groupid = $row['groupID'];
    
            if ($hashedPassword = $storedPassword) {
                echo 'Password is valid.';
                if ($groupid == 0) {
                    $_SESSION['customer'] = $username;
                    header('location: home.php');
                    exit();
                } elseif ($groupid == 1) {
                    $_SESSION['adm'] = $username;
                    header('location: admin/home.php');
                    exit();
                }
            } 
        }else{
            echo 'dont fund';
        }

    } 
}
?>
<div class="container-fluid ind-header">
    <div class="row d-flex">
        <div class="col-sm">
            <h1>Get Your services</h1>
            <h5>It's easy to make it</h5>
            <button id="reg_btn" class="btn btn-success">sign up</button>
            <button id="login_btn" class="btn btn-primary">login</button>
        </div>
        <div class="col-sm">
            <img src="admin/images/site/head-img.png" alt="">
        </div>
    </div>
</div>
<div class="container index-body">
    <div class="row about">
        <div class="col-sm">
            <img src="admin/images/site/about.png" alt="">
        </div>
        <div class="col-sm">
            <h2>About us</h2>
            <h6>"Welcome to Be-First!
                We offer active numbers for popular platforms like WhatsApp, Facebook,
                Telegram, and more.

                Recharge your favorite games quickly and easily through our platform. We support Steam, PlayStation, and
                more.

                Choose Be-First for reliable services that enhance your digital experiences. Explore our
                offerings and reach out to our support team for assistance.
                "</h6>
        </div>
    </div>
    <div class="servic">
        <div class="row">
            <div class="col">
                <h2 class=text-center>
                    Our services
                </h2>
            </div>

        </div>
        <div class="row">
            <div class="col">
                <div class="card ">
                    <img class="card-img-top" src="./admin/images/site/sim.png" alt="Card image cap">
                    <h5 class="card-title">sim card</h5>
                    <p class=card-text>number from alot of country ,Get code for any platform within second</p>
                </div>
            </div>
            <div class="col">
                <div class="card ">
                    <img class="card-img-top" src="./admin/images/site/payment.png" alt="Card image cap">
                    <h5 class="card-title">Payment Card</h5>
                    <p class=card-text>Recharge cards for many platforms, such as the Playstation, in many categories
                        and with quick steps</p>
                </div>
            </div>

        </div>
    </div>
</div>









<?php include  $tpl . 'footer.php';

ob_end_flush()
?>