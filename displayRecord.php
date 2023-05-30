<?php
    if (isset($pageTitle)) {
        echo "<h4>{$pageTitle}</h4>";
    }

	if (count($dataList1) === 0) {
		echo "There is no data";
		exit();
	}

   $record = $dataList1[0];
?>

<table class="table" style="width: 500px;">
    <tbody>
    <tr>
        <td>Rental ID</td>
        <td><?php echo $record['rentalID']; ?></td>
    </tr>
    <tr>
        <td>User ID</td>
        <td><?php echo $record['userID']; ?></td>
    </tr>
    <tr>
        <td>Title</td>
        <td><a href="index.php?mode=displaybook&key=<?php echo $record["isbn"]; ?>"><?php echo $record['title']; ?></a></td>
    </tr>
    <tr>
        <td>Date Out</td>
        <td><?php echo $record['dateOut'] ?></td>
    </tr>
    <tr>
        <td>Date In</td>
        <td><?php echo $record['dateIn']; ?></td>
    </tr>
    </tbody>
</table>