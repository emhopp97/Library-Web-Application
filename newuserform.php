<div class='container-fluid'>
    <h3>
        <?php
            if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
                echo "Add User";
            }
            else {
                echo "Create Account";
            }
        ?>
    </h3>
    <form action='index.php?mode=addnewuser' method='post'>
    
        <table class='table'>
            <tr>
                <td>First name:</td><td><input type='text' name="firstName" required
                    placeholder='Enter first name'></td>
            </tr>
            <tr>
                <td>Last name:</td><td><input type='text' name="lastName" required
                    placeholder='Enter last name'></td>
            </tr>
            <tr>
                <td>Phone:</td><td><input type='tel' name="phone" required
                    placeholder='Enter phone'></td>
            </tr>
            <tr>
                <td>Email:</td><td><input type='email' name="email" required
                    placeholder='Enter email'></td>
            </tr>
            <tr>
                <td>Address:</td><td><input type='text' name="address" required
                    placeholder='Enter address'></td>
            </tr>
            <tr>
                <td>Username:</td><td><input type='text' name="username" required
                    placeholder='Enter username'></td>
            </tr>
            <tr>
                <td>Password:</td><td><input type='password' name="password" required
                    placeholder='Enter password'></td>
            </tr>
        </table>

        <p><button type='submit' name="register" class="btn btn-primary">Submit</button></p>

    </form>
</div>