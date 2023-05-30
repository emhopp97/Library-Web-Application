<?php
    $book = $data[0];
?>

<div class="container-fluid">
    <h3>Edit Book</h3>
    <form method="post" action="index.php?mode=updatebook">
        <table class="table" style="width: 1000px;">
            <tr>
                <td>ISBN</td>
                <td><?php echo $book['isbn']; ?></td>
            </tr>
            <tr>
                <td>Title</td>
                <td><input type="text" name="title" size="40" value="<?php echo $book['title']; ?>" required></td>
            </tr>
            <tr>
                <td>Authors</td>
                <td><input type="text" name="authors" size="40" value="<?php echo $book['authors']; ?>" required></td>
            </tr>
            <tr>
                <td>Publisher</td>
                <td><input type="text" name="publisher" size="40" value="<?php echo $book['publisher']; ?>" required></td>
            </tr>
            <tr>
                <td>Publication Date</td>
                <td><input type="text" name="publicationDate" value="<?php echo $book['publicationDate'] ?>" required></td>
            </tr>
            <tr>
                <td>Number of Pages</td>
                <td><input type="number" name="numPages" value="<?php echo $book['numPages']; ?>" required></td>
            </tr>
            <tr>
                <td>Language</td>
                <td><input type="text" name="language" value="<?php echo $book['language']; ?>" required></td>
            </tr>
            <tr>
                <td>Number of Ratings</td>
                <td><input type="number" name="numRatings" value="<?php echo $book['numRatings']; ?>" required></td>
            </tr>
            <tr>
                <td>Average Rating</td>
                <td><input type="number" step="0.001" name="avgRating" value="<?php echo $book['avgRating']; ?>" required></td>
            </tr>
        </table>

        <p><button type='submit' name="update" class="btn btn-primary" value="<?php echo $book['isbn']; ?>">Update</button></p>

    </form>
</div>