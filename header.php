<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="style.css" rel="stylesheet">
    </head>
    <body>
        <div class='container-fluid'>
            <style>
                .menu-link > a { color: #fff; font-weight: 500; padding-left: 20px; }
                .menu-bar { background-color: maroon; }
            </style>
            <div class="row">
                <div class="col-sm-12">
                    <img src="books.jpg" alt="Books" height="100px">
                </div>
                <nav class="navbar navbar-expand-lg navbar-dark"  style="background-color: #6c757d; margin-bottom: 10px">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link login-nav" id="userlogin-nav" href="loginform.php?mode=user">User Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link login-nav" id="adminlogin-nav" href="loginform.php?mode=admin">Admin Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hide" id="home-nav" href="index.php?mode=home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hide" id="browse-nav" href="index.php?mode=browse">Browse</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav hide" id="checkedout-nav" href="index.php?mode=displaycheckedoutbooks">Checked Out</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav hide" id="users-nav" href="index.php?mode=displayuserlist">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav hide" id="records-nav" href="index.php?mode=displayrecordlist">Records</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav hide" id="addbook-nav" href="index.php?mode=displayaddbookform">Add Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-nav hide" id="adduser-nav" href="index.php?mode=displaynewuserform">Add User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link user-nav hide" id="profile-nav" href="index.php?mode=displayuserinfo">View Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hide" id="logout-nav" href="index.php?mode=logout">Sign Out</a>
                        </li>
                    </ul>
                </nav>
            </div>