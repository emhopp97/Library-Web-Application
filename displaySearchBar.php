<div class='container-fluid'>
    <h4 id='searchheader'>Search Catalog</h4>
    <form action='index.php?mode=search' method='post'>
    
        <table class='search'>
            <tr>
                <td>
                    <select name="type" id="searchcategory">
                        <option value="title" <?php if ($type == "title") echo "selected"; ?>>Title</option>
                        <option value="author"<?php if ($type == "author") echo "selected"; ?>>Author</option>
                        <option value="isbn"<?php if ($type == "isbn") echo "selected"; ?>>ISBN</option>
                    </select>
                </td>
                <td id='searchbartd'><input type='search' name='searchbar' id='searchbar' placeholder='Enter keyword' <?php if (isset($text)) { ?> value='<?php echo $text; ?>'<?php } ?> required></td>
                <td id='searchbuttontd'><button type='submit' class='btn btn-primary'>Search</button></td>
            </tr>
        </table>

    </form>
</div>