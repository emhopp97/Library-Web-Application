<?php
    if (isset($pageTitle)) {
        echo "<h4>{$pageTitle}</h4>";
    }

	if (count($dataList1) === 0) {
		echo "Book not found!";
		exit();
	}

    $book = $dataList1[0];

    if (!$_SESSION["admin"]) {
        $userRating = null;
        if (count($dataList4) > 0) {
            $userRating = $dataList4[0];
        }
    }
?>

<table class="table" style="width: 600px;">
    <tbody>
    <tr>
        <td>Title</td>
        <td><?php echo $book['title']; ?></td>
    </tr>
    <tr>
        <td>Author(s)</td>
        <td><?php echo $book['authors']; ?></td>
    </tr>
    <tr>
        <td>ISBN</td>
        <td><?php echo $book['isbn']; ?></td>
    </tr>
    <tr>
        <td>Publisher</td>
        <td><?php echo $book['publisher']; ?></td>
    </tr>
    <tr>
        <td>Publication Date</td>
        <td><?php echo $book['publicationDate'] ?></td>
    </tr>
    <tr>
        <td>Number of Pages</td>
        <td><?php echo $book['numPages']; ?></td>
    </tr>
    <tr>
        <td>Language</td>
        <td><?php echo $book['language']; ?></td>
    </tr>
    <tr>
        <td>Number of Ratings</td>
        <td><?php echo number_format($book['numRatings']); ?></td>
    </tr>
    <tr>
        <td>Average Rating</td>
        <td><?php echo number_format($book['avgRating'], 2); ?></td>
    </tr>
    <?php
    if ($_SESSION["admin"] && count($dataList5) > 0) {
    ?>
    <tr>
        <td>Added By</td>
        <td><?php echo $dataList5[0]["adminID"]; ?></td>
    </tr>
    <?php
    }

    ?>
    <?php
    if (!$_SESSION["admin"]) {
    ?>
    <tr>
        <td>Your Rating</td>
        <td>
            <?php
            if (!is_null($userRating)) {
            ?>
            <form method="post" action="index.php?mode=updaterating">
                <input type="number" name="rating" min="1" max="5" value="<?php echo $userRating['rating']; ?>" required>
                <button type="submit" name="rate" class="btn btn-primary" style="margin-left: 5px; background-color: green;" value="<?php echo $book['isbn'] ?>">Update Rating</button>
            </form>
            <?php
            }
            else {
            ?>
            <form method="post" action="index.php?mode=setrating">
                <input type="number" name="rating" min="1" max="5" required>
                <button type="submit" name="rate" class="btn btn-primary" style="margin-left: 5px; background-color: green;" value="<?php echo $book['isbn'] ?>">Rate</button>
            </form>
            <?php
            }
            ?>
        </td>
    </tr>
    <?php
    }
    ?>
    <tr>
        <?php
        if (!$_SESSION["admin"] && count($dataList3) > 0) {
        ?>
        <td>Due Date</td>
        <td><?php echo date("m/d/Y", strtotime($dataList3[0]["dueDate"])); ?></td>
        <?php
        }
        else {
        ?>
        <td>Availability</td>
        <td>
            <?php
            if (count($dataList2) == 0) {
                echo "IN";
            } else {
                echo "OUT";
            }
            ?>
        </td>
        <?php
        }
        ?>
    </tr>
    <?php
    if ($_SESSION["admin"] && count($dataList2) > 0) {
    ?>
    <tr>
        <td>Checked Out By</td>
        <td><a href="index.php?mode=displayuser&key=<?php echo $dataList2[0]["userID"]; ?>"><?php echo $dataList2[0]["userID"]; ?></a></td>
    </tr>
    <?php
    }
    ?>
    </tbody>
</table>

<?php
if (!$_SESSION["admin"] && count($dataList2) == 0) {
?>
<form action="index.php?mode=checkOut" method="post">
    
    <p><button type="submit" class="btn btn-primary" name="check_out" value="<?php echo $book['isbn']; ?>">Check Out</button></p>
    
</form>
<?php
}
else if (!$_SESSION["admin"] && count($dataList3) > 0) {
?>
<form action="index.php?mode=checkIn" method="post">
    
    <p><button type="submit" class="btn btn-primary" name="check_in" style="background-color: red;" value="<?php echo $book['isbn']; ?>">Check In</button></p>
    
</form>
<?php
}
if ($_SESSION["admin"]) {
?>
<form method="post" action="index.php?mode=displayeditbookform">

    <p><button type="submit" class="btn btn-primary" name="edit" value="<?php echo $book["isbn"]; ?>">Edit</button></p>

</form>
<?php
}
?>