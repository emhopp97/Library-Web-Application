<?php 
    $title = "Login";
    include("header.php");
    
    $mode = "";
    if (isset($_GET["mode"])) {
        $mode = $_GET["mode"];
    }

    if ($mode == "user") {
    ?>
        <script>document.getElementById("userlogin-nav").classList.add("active");</script>
    <?php
    }
    else if ($mode == "admin") {
    ?>
        <script>document.getElementById("adminlogin-nav").classList.add("active");</script>
    <?php
    }
?>

<script>
    var hideItems = document.getElementsByClassName('hide');
    for (var i = 0; i < hideItems.length; i++) {
        hideItems[i].style='display:none';
    }
</script>

<style>
    .form-signin {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
        color: #017572;
    }
    .button-row {
        margin-top: 10px;
    }
</style>
   
<div class="container">

    <form method="post" action="index.php?mode=login" class="form-signin">
        <h3>Sign in</h3>
        <input type = "text" class = "form-control" name="username" placeholder="username" required autofocus></br>
        <input type="password" class="form-control" name="password" placeholder="password" required>
        <p class="button-row">
            <?php
            if ($mode == "user") {
            ?>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="login" value="user">Login</button>
            <?php
            }
            else if ($mode == "admin") {
            ?>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="login" value="admin">Login</button>
            <?php
            }
            ?>
        </p>
    </form>
    <?php
    if ($mode == "user") {
    ?>
    <div style="text-align:center">
        <form method="post" action="index.php?mode=displaynewuserform">
            <h5>New User?</h5>
            <p class="button-row">
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="registerbutton" style="background-color: red">Register</button>
            </p>
        </form>
    </div>
    <?php
    }
    ?>
    
</div>

<?php 
    include "footer.html";
?>