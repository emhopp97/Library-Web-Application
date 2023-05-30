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

<form action="index.php?mode=updateuserinfo" method="post">

    <table class="table" style="width: 600px;">
        <tbody>
        <tr>
            <td>Phone</td>
            <td><input type="tel" name="phone" value="<?php echo $user['phone']; ?>"></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="email" name="email" value="<?php echo $user['email']; ?>"></td>
        </tr>
        <tr>
            <td>Address</td>
            <td><input type="text" name="address" value="<?php echo $user['address']; ?>"></td>
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
        </tbody>
    </table>

    <p><button type="submit" class="btn btn-primary">Update</button></p>

</form>

<form action="index.php?mode=displayfeeform" method="post">
    <p><button type="submit" class="btn btn-primary" style="background-color: red">Pay Fees</button></p>
</form>

<form action="index.php?mode=displayrentalhistory" method="post">
    <p><button type="submit" class="btn btn-primary" style="background-color: green">View Rental History</button></p>
</form>