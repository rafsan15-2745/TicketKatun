
<?php
session_start();
require_once '../dbconnection.php';
require_once '../constants.php';
$class = "reg";
?>
<?php
$cur_page = 'signup';
include 'includes/inc-header.php';
include 'includes/inc-nav.php';
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $file = 'file';
    $address = $_POST['address'];
    $nid = $_POST['nid'];
    $cpassword = $_POST['cpassword'];
    $password = $_POST['password'];
    
    if (!isset($name, $phone, $email, $address, $nid, $password, $cpassword) || ($password != $cpassword)) { ?>
<script>
alert("Ensure you fill the form properly.");
</script>

<?php

        } elseif ($cpassword != $password) { ?>
<script>
alert("Password does not match.");
</script>
<?php
        } else {
            
            $password = md5($password);
            $can = 1;
            $loc = uploadFile('file');
            if ($loc == -1) {
                echo "<script>alert('We could not complete your registration, try again later!')</script>";
                exit;
            }
            $stmt = $conn->prepare("INSERT INTO users (`name`, email, phn, loc, address, nid, `password`) VALUES (?,?,?,?,?,?,?);");
            $stmt->bind_param("sssssss", $name, $email, $phone, $loc, $address, $nid, $password); 
            if ($stmt->execute()) {
            ?>
<script>
alert("Congratulations.\nYou are registered.");
window.location = 'signin.php';
</script>
<?php
            } else {
            ?>
<script>
alert("We could not register you!.");
</script>
<?php
            }
        }
    }
//}
?>

<div class="signup-page">
    <div class="form">
        <h2>Create Account </h2>
        <br>
        <form class="login-form" method="post" role="form" enctype="multipart/form-data" id="signup-form"
            autocomplete="off">
            <div id="errorDiv"></div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" required minlength="1" name="name" placeholder="Enter your name" aria-describedby="namelHelp">
                    <small id="nameHelp" class="form-text text-muted">Please enter name as per NID.</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" required name="email" placeholder="Enter your email" aria-describedby="emaillHelp">
                    <small id="emailHelp" class="form-text text-muted">Please enter your email.</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" minlength="11" maxlength="14" required name="phone" placeholder="Enter your phone number" aria-describedby="phonelHelp">
                    <small id="phoneHelp" class="form-text text-muted">Please enter your active phone number.</small>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Insert Picture</label>
                    <input type="file" name='file' required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Address</label>
                    <input type='text' name="address" class="form-group" required placeholder="Enter your address" aria-describedby="addresslHelp">
                    <small id="addressHelp" class="form-text text-muted">Please enter current address.</small>
                    <span class="help-block" id="error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>NID Number</label>
                    <input type='text' minlength="10" maxlength="20" name="nid" class="form-group" required name="nid" placeholder="Enter your nid" aria-describedby="nidHelp">
                    <small id="passwordHelp" class="form-text text-muted">Please enter valid NID or Birth Reg.</small>
                    <span class="help-block" id="error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your phone password" aria-describedby="passwordHelp">
                    <small id="nidHelp" class="form-text text-muted">Please enter strong password.</small>
                    <span class="help-block" id="error"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="cpassword" id="cpassword" placeholder="Reenter password" aria-describedby="cpasswordlHelp">
                    <small id="cpasswordHelp" class="form-text text-muted">Please reenter password.</small>
                    <span class="help-block" id="error"></span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" id="btn-signup">
                        Submit
                    </button>
                </div>
            </div>
            <p class="message">
                <a href="#">Before submit please ensure your data is correct.</a><br>
            </p>
        </form>
    </div>
</div>
</div>
<script src="assets/js/jquery-1.12.4-jquery.min.js"></script>
</body>
</html>