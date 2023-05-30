<?php
    if (isset($pageTitle)) {
        echo "<h4>{$pageTitle}</h4>";
    }

	if (count($dataList1) === 0) {
		echo "There is no data";
		exit();
	}

   $user = $dataList1[0];
   echo "<p>Name: {$user['lastName']}, {$user['firstName']}</p>";
?>

<table class="table" style="width: 600px;">
    <tbody>
    <tr>
        <td>Username</td>
        <td><?php echo $user['username']; ?></td>
    </tr>
    <tr>
        <td>Phone</td>
        <td><?php echo $user['phone']; ?></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><?php echo $user['email']; ?></td>
    </tr>
    <tr>
        <td>Address</td>
        <td><?php echo $user['address']; ?></td>
    </tr>
    <tr>
        <td>Current Rentals</td>
        <td>
            <ul>
            <?php
            if (count($dataList2) > 0) {
                foreach ($dataList2 as $book) {
                    echo "<li><a href='index.php?mode=displaybook&key={$book['isbn']}'>".$book['title']."</a></li>";
                }
            }
            ?>
            </ul>
        </td>
    </tr>
    <tr>
        <td>Total Late Fees</td>
        <td><?php echo "$ ".number_format($user['lateFees'], 2) ?></td>
    </tr>
    <?php
    if (count($dataList3) > 0) {
    ?>
    <tr>
        <td>Added By</td>
        <td><?php echo $dataList3[0]["adminID"]; ?></td>
    </tr>
    <?php
    }
    ?>
    </tbody>
</table>

<form action="index.php?mode=displayrentalhistory" method="post">
    <p><button type="submit" class="btn btn-primary" name="rentalhistory" value="<?php echo $user['username']; ?>" style="background-color: green">Rental History</button></p>
</form>