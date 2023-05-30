<?php
    session_start();
    $title = "Library";
    include("header.php");

    if ((isset($_POST["login"]) && $_POST["login"] == "user") || (isset($_SESSION["admin"]) && !$_SESSION["admin"])) {
    ?>
        <script>
            var hideAdminItems = document.getElementsByClassName('admin-nav');
            for (var i = 0; i < hideAdminItems.length; i++) {
                hideAdminItems[i].style='display:none';
            }
            var hideLoginItems = document.getElementsByClassName('login-nav');
            for (var i = 0; i < hideLoginItems.length; i++) {
                hideLoginItems[i].style='display:none';
            }
        </script>
    <?php
    }
    else if ((isset($_POST["login"]) && $_POST["login"] == "admin") || (isset($_SESSION["admin"]) && $_SESSION["admin"])) {
    ?>
        <script>
            var hideUserItems = document.getElementsByClassName('user-nav');
            for (var i = 0; i < hideUserItems.length; i++) {
                hideUserItems[i].style='display:none';
            }
            var hideLoginItems = document.getElementsByClassName('login-nav');
            for (var i = 0; i < hideLoginItems.length; i++) {
                hideLoginItems[i].style='display:none';
            }
        </script>
    <?php
    }
    else {
    ?>
        <script>
            var hideItems = document.getElementsByClassName('hide');
            for (var i = 0; i < hideItems.length; i++) {
                hideItems[i].style='display:none';
            }
        </script>
    <?php
    }

    include("pdo_connect.php");

    if (!$db) {
        echo "Could not connect to the database";
        exit();
    }

    $mode = isset($_GET['mode'])? $_GET['mode'] : "";
    
    try {
        if (!isset($_POST["username"]) && !isset($_SESSION["username"]) && $mode != 'displaynewuserform') {

            header("Location: loginform.php?mode=user");

            exit();
        }

        switch ($mode) {
            case "login":
                $username = (isset($_POST["username"])) ? $_POST["username"] : "-1";
                $password = (isset($_POST["password"])) ? $_POST["password"] : "-1";

                if ($_POST["login"] == "user") {
                    $sql = "SELECT `username` from `user` where `username` = :username and `password` = :password;";
                    $parameters = array(":username" => $username, ":password" => md5($password));
                }
                else if ($_POST["login"] == "admin") {
                    $sql = "SELECT `username` from `admin` where `username` = :username and `password` = :password;";
                    $parameters = array(":username" => $username, ":password" => md5($password));
                }

                $stm = $db->prepare($sql);
                $stm->execute($parameters);
                $result = $stm->fetch();

                if (isset($result["username"])) {
                    $_SESSION["username"] = $result["username"];
                    if ($_POST["login"] == "admin") {
                        $_SESSION["admin"] = true;
                    }
                    else {
                        $_SESSION["admin"] = false;

                        $parameterValues = array(
                            ":username" => $_SESSION["username"]
                        );
                        $resultSet = getAll("CALL getDueDates(:username)", $db, $parameterValues);
                        if (!empty($resultSet)) {
                            $currentDate = date('Y-m-d');
                            $lateFees = $resultSet[0]["lateFees"];
                            foreach ($resultSet as $book) {
                                $dueDate = $book["dueDate"];
                                if ($currentDate > $dueDate && $lateFees < 10) {
                                    $lateFees += 1;
                                }
                            }
                            $sql3 = "UPDATE `user` SET `lateFees` = :fee WHERE `username` = :username;";
                            $parameterValues3 = array(
                                ":fee" => $lateFees,
                                ":username" => $_SESSION["username"]
                            );
                            $stm = $db->prepare($sql3);
                            $stm->execute($parameterValues3);
                        }
                    }

                    header("Location: index.php?mode=home");
                }
                else {
                    header("Location: loginform.php?mode=user");

                    exit();
                }

                break;
                
            case "displaynewuserform":
                ?>
                <script>document.getElementById("adduser-nav").classList.add("active");</script>
                <?php
                
                include('newuserform.php');

                break;

            case "addnewuser":
                $firstName = "";
                if (isset($_POST['firstName'])) {
                    $firstName = $_POST['firstName'];
                }
                $lastName = "";
                if (isset($_POST['lastName'])) {
                    $lastName = $_POST['lastName'];
                }
                $phone = "";
                if (isset($_POST['phone'])) {
                    $phone = $_POST['phone'];
                }
                $email = "";
                if (isset($_POST['email'])) {
                    $email = $_POST['email'];
                }
                $address = "";
                if (isset($_POST['address'])) {
                    $address = $_POST['address'];
                }
                $memberType = "";
                if (isset($_POST['memberType'])) {
                    $memberType = $_POST['memberType'];
                }
                $username = "";
                if (isset($_POST['username'])) {
                    $username = $_POST['username'];
                }
                $password = "";
                if (isset($_POST['password'])) {
                    $password = $_POST['password'];
                }

                $parameterValues1 = array(":username" => $username);
                $dataList = getAll("CALL getUserByUserID(:username)", $db, $parameterValues1);

                if (count($dataList) > 0) {
                    echo "Username unavailable";
                }
                else if (empty($firstName) || empty($lastName) || empty($username)) {
                    echo "Invalid data";
                }
                else {
                    $parameterValues2 = array(
                        ":firstName" => $firstName,
                        ":lastName" => $lastName,
                        ":phone" => $phone,
                        ":email" => $email,
                        ":address" => $address,
                        ":username" => $username,
                        ":password" => md5($password)
                    );
                    $stm = $db->prepare("CALL addNewUser(:firstName, :lastName, :phone, :email, :address, :username, :password)");
                    $stm->execute($parameterValues2);

                    if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
                        $sql3 = "INSERT INTO `manage` (userID, adminID) VALUES (:userID, :adminID);";
                        $parameterValues3 = array(
                            ":userID" => $username,
                            ":adminID" => $_SESSION["username"]
                        );
                        $stm = $db->prepare($sql3);
                        $stm->execute($parameterValues3);
                        
                        echo "New user added!";
                    }
                    else {
                        $_SESSION["username"] = $username;
                        $_SESSION["admin"] = false;
                        header("Location: index.php?mode=home");
                    }
                }
                
                break;

            case "home":
                ?>
                <script>document.getElementById("home-nav").classList.add("active");</script>
                <?php

                $text = "";
                if(isset($_GET["search"])) {
                    $text = $_GET["search"];
                }

                $type = "title";
                if (isset($_GET["type"])) {
                    $type = $_GET["type"];
                }

                echo "<h3 style='margin-top: 10px'>Welcome to the Library Catalog!</h3>";
                include('displaySearchBar.php');

                break;

            case "search":
                $type = "title";
                if (isset($_POST["type"])) {
                    $type = $_POST["type"];
                }
                
                $text = "";
                if (isset($_POST['searchbar'])) {
                    $text = $_POST['searchbar'];
                }

                if (empty(trim($text))) {
                    echo "No results found";
                    exit();
                }
                
                $parameterValues = array(
                    ":text" => $text
                );

                if ($type == "title") {
                    $sql = "CALL searchByTitle(:text)";
                }
                else if ($type == "author") {
                    $sql = "CALL searchByAuthor(:text)";
                }
                else if ($type == "isbn") {
                    $sql = "CALL searchByISBN(:text)";
                }
                
                $pageTitle = "Selected Books";
                $resultSet = getAll($sql, $db, $parameterValues);
                $columns = array("Title", "Authors", "Number of Pages", "Language Code", "Average Rating", "Number of Ratings");

                echo "<br><p>Displaying results for \"$text\"</p>";
                echo "<p><a href='index.php?mode=home&search=$text&type=$type'>Refine search</a></p>";
                displayResultSet($pageTitle, $resultSet, $columns, "book");

                break;

            case "browse":
                ?>
                <script>document.getElementById("browse-nav").classList.add("active");</script>
                <?php
                
                $resultSet = getAll("CALL getAllBooks()", $db);
                $pageTitle = "Browse All Books";
                $columns = array("Title", "Authors", "Number of Pages", "Language Code", "Average Rating", "Number of Ratings");
                displayResultSet($pageTitle, $resultSet, $columns, "book", "browse");

                break;

            case "displaybook":
                $key = "";
                if (isset($_GET["key"])) {
                    $key = $_GET["key"];
                }

                $parameterValues1 = array(":isbn" => $key);
                $dataList1 = getAll("CALL getBookByISBN(:isbn)", $db, $parameterValues1);

                $dataList2 = getAll("CALL isCheckedOutBy(:isbn)", $db, $parameterValues1);

                if (!$_SESSION["admin"]) {
                    $parameterValues2 = array(
                        ":isbn" => $key,
                        ":userID" => $_SESSION["username"]
                    );
                    $dataList3 = getAll("CALL isCheckedOutByUser(:isbn, :userID)", $db, $parameterValues2);

                    $dataList4 = getAll("CALL getUserRating(:isbn, :userID)", $db, $parameterValues2);
                }
                else {
                    $parameterValues = array(
                        ":isbn" => $key
                    );
                    $dataList5 = getAll("CALL getBookAddedBy(:isbn)", $db, $parameterValues);
                }

                $pageTitle = "Book Information";

                include('displayBook.php');

                break;

            case "checkOut":
                $rentalDate = date('Y-m-d');
                $dueDate = date('Y-m-d', strtotime($rentalDate."+2 week"));
                $isbn = $_POST["check_out"];
                $username = $_SESSION["username"];

                $parameterValues = array(
                    ":rentalDate" => $rentalDate,
                    ":dueDate" => $dueDate,
                    ":isbn" => $isbn,
                    ":userID" => $username
                );
                $stm = $db->prepare("CALL checkOutBook(:rentalDate, :dueDate, :isbn, :userID)");
                $stm->execute($parameterValues);

                echo "Book checked out successfully!";

                break;

            case "checkIn":
                $isbn = $_POST["check_in"];
                $returnDate = date('Y-m-d');

                $parameterValues = array(
                    ":isbn" => $isbn,
                    ":returnDate" => $returnDate
                );
                $stm = $db->prepare("CALL checkInBook(:isbn, :returnDate)");
                $stm->execute($parameterValues);

                echo "Book returned successfully!";
                
                break;

            case "displayeditbookform":
                if (isset($_POST["edit"])) {
                    $isbn = $_POST["edit"];

                    $parameterValues = array(":isbn" => $isbn);
                    $data = getAll("CALL getBookByISBN(:isbn)", $db, $parameterValues);

                    include("editBookForm.php");
                }
                else {
                    echo "<p>Book not found!</p>";
                }

                break;

            case "updatebook":
                $title = "";
                if (isset($_POST['title'])) {
                    $title = $_POST['title'];
                }
                $authors = "";
                if (isset($_POST['authors'])) {
                    $authors = $_POST['authors'];
                }
                $publisher = "";
                if (isset($_POST['publisher'])) {
                    $publisher = $_POST['publisher'];
                }
                $publicationDate = "";
                if (isset($_POST['publicationDate'])) {
                    $publicationDate = $_POST['publicationDate'];
                }
                $numPages = 0;
                if (isset($_POST['numPages'])) {
                    $numPages = $_POST['numPages'];
                }
                $language = "";
                if (isset($_POST['language'])) {
                    $language = $_POST['language'];
                }
                $numRatings = 0;
                if (isset($_POST["numRatings"])) {
                    $numRatings = $_POST["numRatings"];
                }
                $avgRating = 0;
                if (isset($_POST["avgRating"])) {
                    $avgRating = $_POST["avgRating"];
                }
                $isbn = 0;
                if (isset($_POST['update'])) {
                    $isbn = $_POST['update'];
                }

                $sql = "UPDATE `book` SET `title` = :title, `authors` = :authors, `publisher` = :publisher, `publicationDate` = :publicationDate, `numPages` = :numPages, `language` = :language, `numRatings` = :numRatings, `avgRating` = :avgRating WHERE `isbn` = :isbn;";
                $parameterValues = array(
                    ":title" => $title,
                    ":authors" => $authors,
                    ":publisher" => $publisher,
                    ":publicationDate" => $publicationDate,
                    ":numPages" => $numPages,
                    ":language" => $language,
                    ":numRatings" => $numRatings,
                    ":avgRating" => $avgRating,
                    ":isbn" => $isbn
                );
                $stm = $db->prepare($sql);
                $stm->execute($parameterValues);

                header("Location: index.php?mode=displaybook&key=$isbn");

                break;

            case "displaytopratedbooks":
                ?>
                <script>document.getElementById("toprated-nav").classList.add("active");</script>
                <?php

                $resultSet = getAll("CALL getTopRatedBooks()", $db);
                $pageTitle = "Top 100 Rated Books with at least 1000 ratings";
                $columns = array("Title", "Authors", "Number of Pages", "Language Code", "Average Rating", "Number of Ratings");
                displayResultSet($pageTitle, $resultSet, $columns, "book");

                break;
            
            case "displayuserinfo":
                ?>
                <script>document.getElementById("profile-nav").classList.add("active");</script>
                <?php
                
                if (!$_SESSION["admin"]) {
                    $parameterValues = array(":username" => $_SESSION["username"]);

                    $dataList1 = getAll("CALL getUserByUserID(:username)", $db, $parameterValues);
                    $dataList2 = getAll("CALL getBooksCheckedOutByUser(:username)", $db, $parameterValues);

                    $pageTitle = "Account Information";

                    include('displayUserInfo.php');
                }
                else {
                    echo "Page not available";
                }

                break;

            case "updateuserinfo":
                $username = "";
                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                }
                
                $phone = "";
                if (isset($_POST['phone'])) {
                    $phone = $_POST['phone'];
                }

                $email = "";
                if (isset($_POST['email'])) {
                    $email = $_POST['email'];
                }

                $address = "";
                if (isset($_POST['address'])) {
                    $address = $_POST['address'];
                }
                
                if ($phone === '' || $email === '' || $address === '') {
                    echo "Invalid data";
                    exit(); 
                } 

                $sql = "UPDATE `user` SET `phone` = :phone, `email` = :email, `address` = :address WHERE `username` = :username;";
                
                $parameterValues = array(
                    ":phone" => $phone,
                    ":email" => $email,
                    ":address" => $address,
                    ":username" => $username
                );

                $stm = $db->prepare($sql);
                $stm->execute($parameterValues);
                echo "Successfully updated account information.";
    
                break;

            case "displayfeeform":
                $parameterValues = array(":username" => $_SESSION["username"]);
                $dataList = getAll("CALL getUserByUserID(:username)", $db, $parameterValues);
                $pageTitle = "Payment Form";
                
                include('feepaymentform.php');

                break;

            case "payfees":
                $username = $_SESSION['username'];

                $lateFees = 0.0;
                if (isset($_POST['lateFees'])) {
                    $lateFees = floatval($_POST['lateFees']);
                }

                $payAmount = 0.0;
                if (isset($_POST['payAmount'])) {
                    $payAmount = floatval($_POST['payAmount']);
                }

                $newLateFees = $lateFees - $payAmount;

                $sql = "UPDATE `user` SET `lateFees` = :lateFees WHERE `username` = :username;";
                $parameterValues = array(
                    ":username" => $username,
                    ":lateFees" => $newLateFees
                );
                $stm = $db->prepare($sql);
                $stm->execute($parameterValues);

                echo "Payment successful!";

                break;

            case "setrating":
                $rating = 0;
                if (isset($_POST["rating"])) {
                    $rating = $_POST["rating"];
                }
                $isbn = 0;
                if (isset($_POST["rate"])) {
                    $isbn = $_POST["rate"];
                }
                
                $sql1 = "INSERT INTO `rate` (isbn, userID, rating) VALUES (:isbn, :userID, :rating);";
                $parameterValues1 = array(
                    ":isbn" => $isbn,
                    ":userID" => $_SESSION["username"],
                    ":rating" => $rating
                );
                $stm = $db->prepare($sql1);
                $stm->execute($parameterValues1);

                $sql2 = "SELECT `avgRating`, `numRatings` FROM `book` WHERE `isbn` = :isbn;";
                $parameterValues2 = array(":isbn" => $isbn);
                $resultSet = getAll($sql2, $db, $parameterValues2);
                $book = $resultSet[0];

                $numRatings = $book["numRatings"] + 1;
                $avgRating = (($book["avgRating"] * $book["numRatings"]) + $rating) / $numRatings;

                $parameterValues3 = array(
                    ":avgRating" => $avgRating,
                    ":numRatings" => $numRatings,
                    ":isbn" => $isbn
                );
                $stm = $db->prepare("CALL updateRating(:avgRating, :numRatings, :isbn)");
                $stm->execute($parameterValues3);

                header("Location: index.php?mode=displaybook&key=$isbn");

                break;
                
            case "updaterating":
                $rating = 0;
                if (isset($_POST["rating"])) {
                    $rating = $_POST["rating"];
                }
                $isbn = 0;
                if (isset($_POST["rate"])) {
                    $isbn = $_POST["rate"];
                }

                $parameterValues1 = array(
                    ":isbn" => $isbn,
                    ":userID" => $_SESSION["username"]
                );
                $resultSet1 = getAll("CALL getUserRating(:isbn, :userID)", $db, $parameterValues1);
                $oldRating = $resultSet1[0]["rating"];
                
                $sql2 = "UPDATE `rate` SET `rating` = :rating WHERE `isbn` = :isbn AND `userID` = :userID;";
                $parameterValues2 = array(
                    ":rating" => $rating,
                    ":isbn" => $isbn,
                    ":userID" => $_SESSION["username"]
                );
                $stm = $db->prepare($sql2);
                $stm->execute($parameterValues2);

                $sql3 = "SELECT `avgRating`, `numRatings` FROM `book` WHERE `isbn` = :isbn;";
                $parameterValues3 = array(":isbn" => $isbn);
                $resultSet = getAll($sql3, $db, $parameterValues3);
                $book = $resultSet[0];

                $numRatings = $book["numRatings"];
                $avgRating = (($book["avgRating"] * $numRatings) + $rating - $oldRating) / $numRatings;

                $parameterValues4 = array(
                    ":avgRating" => $avgRating,
                    ":numRatings" => $numRatings,
                    ":isbn" => $isbn
                );
                $stm = $db->prepare("CALL updateRating(:avgRating, :numRatings, :isbn)");
                $stm->execute($parameterValues4);

                header("Location: index.php?mode=displaybook&key=$isbn");

                break;

            case "displaycheckedoutbooks":
                ?>
                <script>document.getElementById("checkedout-nav").classList.add("active");</script>
                <?php

                $resultSet = getAll("CALL getAllCheckedOutBooks()", $db);
                $pageTitle = "Checked Out Books";
                $columns = array("Title", "Checked Out By", "Date Out", "Due Date");
                displayResultSet($pageTitle, $resultSet, $columns, "book");

                break;
            
            case "displayuserlist":
                ?>
                <script>document.getElementById("users-nav").classList.add("active");</script>
                <?php
                
                if ($_SESSION["admin"]) {
                    $resultSet = getAll("CALL getAllUsers()", $db);
                    $pageTitle = "All Users";
                    $columns = array("Last Name", "First Name", "Username", "Phone", "Email", "Late Fees");
                    displayResultSet($pageTitle, $resultSet, $columns, "user");
                }
                else {
                    echo "Access Denied";
                }

                break;

            case "displayuser":
                if ($_SESSION["admin"]) {
                    $key = "";
                    if (isset($_GET["key"])) {
                        $key = $_GET["key"];
                    }
                    $parameterValues1 = array(":userID" => $key);
                    $dataList1 = getAll("CALL getUserByUserID(:userID)", $db, $parameterValues1);
                    $dataList2 = getAll("CALL getBooksCheckedOutByUser(:userID)", $db, $parameterValues1);

                    $parameterValues2 = array(
                        ":userID" => $key
                    );
                    $dataList3 = getAll("CALL getUserAddedBy(:userID)", $db, $parameterValues2);

                    $pageTitle = "User Information";

                    include('displayUser.php');
                }
                else {
                    echo "Access Denied";
                }

                break;

            case "displayrentalhistory":
                $userID = "";
                if ($_SESSION["admin"]) {
                    $parameterValues = array(":userID" => $_POST["rentalhistory"]);
                    $userID = $_POST["rentalhistory"];
                }
                else {
                    $parameterValues = array(":userID" => $_SESSION["username"]);
                    $userID = $_SESSION["username"];
                }
                $resultSet = getAll("CALL getRentalHistory(:userID)", $db, $parameterValues);
                $pageTitle = "Rental History for $userID";
                $columns = array("Title", "Date Out", "Date In");
                displayResultSet($pageTitle, $resultSet, $columns, "book");

                break;
            
            case "displayrecordlist":
                ?>
                <script>document.getElementById("records-nav").classList.add("active");</script>
                <?php
                
                if ($_SESSION["admin"]) {
                    $resultSet = getAll("CALL getAllRecords()", $db);
                    $pageTitle = "All Records";
                    $columns = array("User ID", "Title", "Date Out", "Date In");
                    displayResultSet($pageTitle, $resultSet, $columns, "record");
                }
                else {
                    echo "Access Denied";
                }

                break;

            case "displayrecord":
                if ($_SESSION["admin"]) {
                    $key = "";
                    if (isset($_GET["key"])) {
                        $key = $_GET["key"];
                    }

                    $parameterValues1 = array(":rentalID" => $key);
                    $dataList1 = getAll("CALL getRecordByRentalID(:rentalID)", $db, $parameterValues1);
                    $pageTitle = "Record Information";

                    include('displayRecord.php');
                }
                else {
                    echo "Access Denied";
                }

                break;

            case "displayaddbookform":
                ?>
                <script>document.getElementById("addbook-nav").classList.add("active");</script>
                <?php

                if ($_SESSION["admin"]) {
                    include("addbookform.html");
                }
                else {
                    echo "Access Denied";
                }

                break;

            case "addbook":
                $title = "";
                if (isset($_POST['title'])) {
                    $title = $_POST['title'];
                }
                $authors = "";
                if (isset($_POST['authors'])) {
                    $authors = $_POST['authors'];
                }
                $isbn = null;
                if (isset($_POST['isbn'])) {
                    $isbn = $_POST['isbn'];
                }
                $publisher = "";
                if (isset($_POST['publisher'])) {
                    $publisher = $_POST['publisher'];
                }
                $publicationDate = "";
                if (isset($_POST['publicationDate'])) {
                    $publicationDate = $_POST['publicationDate'];
                }
                $numPages = null;
                if (isset($_POST['numPages'])) {
                    $numPages = $_POST['numPages'];
                }
                $language = "";
                if (isset($_POST['language'])) {
                    $language = $_POST['language'];
                }

                $parameterValues1 = array(":isbn" => $isbn);
                $dataList = getAll("CALL getBookByISBN(:isbn)", $db, $parameterValues1);

                if (count($dataList) > 0) {
                    echo "Book already in database";
                }
                else if (empty($title) || empty($authors) || empty($isbn) || empty($publisher) || empty($publicationDate) || empty($numPages) || empty($language)) {
                    echo "Invalid data";
                }
                else {
                    $parameterValues2 = array(
                        ":isbn" => $isbn,
                        ":title" => $title,
                        ":authors" => $authors,
                        ":publisher" => $publisher,
                        ":publicationDate" => $publicationDate,
                        ":numPages" => $numPages,
                        ":language" => $language,
                        ":adminID" => $_SESSION["username"]
                    );
                    $stm = $db->prepare("CALL addNewBook(:isbn, :title, :authors, :publisher, :publicationDate, :numPages, :language, :adminID)");
                    $stm->execute($parameterValues2);
                    echo "<p>Book added successfully!</p>";
                }

                break;

            case "logout":
                session_unset();
                setcookie(session_name(), '', time()-1000, '/');
                $_SESSION = array();
                header("Location: loginform.php?mode=user");

                break;
                
            default:
                header("Location: index.php?mode=home");
                break;
        } 
    } catch (PDOException $e) {
        echo "Error!: ". $e->getMessage() . "<br/>";
        die();
    }

    include("footer.html");

    function displayResultSet($pageTitle, $resultSet, $columns, $tableType = null, $mode = null) {
        echo "<h4 style='margin-top: 10px'>".$pageTitle."</h4>";

        if ($mode == "browse") {
            echo "<br><a href='index.php?mode=displaytopratedbooks'>Top 100 Books</a>";
        }
        
        echo "<table class='table table-sm'>";
        $numCols = count($columns);
        if ($numCols > 0) {
            echo "<thead><tr>";
            if (isset($tableType)) {
                echo "<th></th>";
            }
            foreach($columns as $c) {
                echo "<th>{$c}</th>";
            }
            echo "</thead>";
        }
        
        echo "<tbody>";
        foreach($resultSet as $item) {

            echo "<tr>";
            
            if ($tableType == "book") {
                echo "<td><a href='index.php?mode=displaybook&key={$item['isbn']}'>View</a></td>";
            }
            else if ($tableType == "user") {
                echo "<td><a href='index.php?mode=displayuser&key={$item['username']}'>View</a></td>";
            }
            else if ($tableType == "record") {
                echo "<td><a href='index.php?mode=displayrecord&key={$item['rentalID']}'>View</a></td>";
            }
            
            foreach ($item as $key => $value) {
                if ($key != "isbn" && $key != "rentalID") {
                    if ($key == "lateFees") {
                        echo "<td>$ ".number_format($value, 2)."</td>";
                    }
                    else {
                        echo "<td>{$value}</td>";
                    }
                }
            }
            
            echo "</tr>";
            
        }
        echo "</tbody></table>";
    }

    function getAll($sql, $db, $parameterValues = null) {

        $statement = $db->prepare($sql);
        $statement->execute($parameterValues);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }

?>