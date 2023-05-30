<?php
    if (isset($pageTitle)) {
        echo "<h4>{$pageTitle}</h4>";
    }

	if (count($dataList) == 0) {
		echo "There is no data";
		exit();
	}

    $user = $dataList[0];
?>

<form action='index.php?mode=payfees' method='post'>

    <table class='table'>
        <tr>
            <td>Total Fees:</td><td><?php echo "$ ".number_format($user['lateFees'], 2) ?></td>
        </tr>
        <tr>
            <td>Pay Amount:</td><td><input type='text' name="payAmount" required></td>
        </tr>
        <tr>
            <td>First name:</td><td><input type='text' name="firstName" required
                value="<?php echo $user['firstName']; ?>"></td>
        </tr>
        <tr>
            <td>Last name:</td><td><input type='text' name="lastName" required
                value="<?php echo $user['lastName']; ?>"></td>
        </tr>
        <tr>
            <td>Address:</td><td><input type='text' name="address" required
                value="<?php echo $user['address']; ?>"></td>
        </tr>
        <tr>
            <td>City:</td><td><input type='text' name="city" required></td>
        </tr>
        <tr>
            <td>State:</td><td><input type='text' name="state" required></td>
        </tr>
        <tr>
            <td>Zip Code:</td><td><input type='text' name="zipcode" maxlength="5" required></td>
        </tr>
        <tr>
            <td>Credit Card Number:</td><td><input type='text' name="cardnum" required></td>
        </tr>
        <tr>
            <td>Expiration:</td><td><input type='month' name="expiration" required></td>
        </tr>
        <tr>
            <td>CVV:</td><td><input type='text' name="cvv" maxlength="3" required></td>
        </tr>
    </table>

    <input type="hidden" name="lateFees" value="<?php echo $user['lateFees']; ?>">

    <p><button type='submit' class="btn btn-primary">Submit Payment</button></p>

</form>