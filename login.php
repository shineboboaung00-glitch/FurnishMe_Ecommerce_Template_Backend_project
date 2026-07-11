<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <?php include('components/css.php'); ?>

</head>

<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <?php
    
    include('components/connection.php'); // Database connection

    $error_message = '';

    // Check if cookie exists and pre-fill the form
    if (isset($_COOKIE['username_or_email'])) {
        $saved_username_or_email = $_COOKIE['username_or_email'];
    } else {
        $saved_username_or_email = '';
    }

    if (isset($_POST['login'])) {
        $username_or_email = $_POST['username_or_email'];
        $password = $_POST['password'];

        // Check if the input is either username or email
        $check_user = "SELECT * FROM users WHERE username='$username_or_email' OR email='$username_or_email'";
        $result = $connection->query($check_user);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Start session and store user information
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];

                // If "Remember me" is checked, set cookies
                if (isset($_POST['remember'])) {
                    // Save the username or email in a cookie for 30 days
                    setcookie('username_or_email', $username_or_email, time() + (30 * 24 * 60 * 60), "/"); // 30 days expiration
                }
                header('Location: index.php');

                exit();
            } else {
                $error_message = "Invalid password. Please try again.";
            }
        } else {
            $error_message = "No account found with that username or email.";
        }
    }
    ?>

    <!-- login form start  -->

    <div class="login-form">
        <form action="login.php" method="post">
            <h3>Login Form</h3>

            <!-- Display error message -->
            <?php if (!empty($error_message)) { ?>
                <div class="error-message" style="color: red;">
                    <?php echo $error_message; ?>
                </div>
            <?php } ?>

            <input type="text" placeholder="Enter your username or email" name="username_or_email" class="box" value="<?php echo isset($saved_username_or_email) ? $saved_username_or_email : ''; ?>" required>
            <input type="password" placeholder="Enter your password" name="password" class="box" required>
            <div class="remember">
                <input type="checkbox" name="remember" id="remember-me">
                <label for="remember-me">Remember me</label>
            </div>
            <input type="submit" value="Login Now" name="login" class="btn">
            <p>Forgot password? <a href="#">Click here</a></p>
            <p>Don't have an account? <a href="register.php">Create now</a></p>
        </form>
    </div>

    <!-- login form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>