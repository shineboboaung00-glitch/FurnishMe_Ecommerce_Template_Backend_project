<?php
include 'components/connection.php';

$error = '';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $image = $_FILES['image']['name'];

    if (empty($name)) {
        $error = "Name is required";
    }
    else if (strlen($name) < 5 || strlen($name) > 15) {
        $error = "Name must be between 5 and 15 characters long.";
    } 
    else {
        if (!empty($image)) {
            move_uploaded_file($_FILES['image']['tmp_name'], 'static/' . $image);
        }
        $data = "INSERT INTO category (name,image) VALUES ('$name','$image')";

        if ($connection->query($data)) {
            header('Location: shop.php');
            exit();
        }
        else {
            $error = "Database Error: " . $connection->error;
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Create</title>

    <?php include('components/css.php'); ?>
</head>

<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <!-- register form start  -->

    <div class="register-form">

        <form method="POST" enctype="multipart/form-data">
            <h3>Category Create Form</h3>

            <?php if (!empty($error)): ?>
                <p class="error_warning"><?php echo $error; ?></p>
            <?php endif; ?>

            <input type="text" name="name" placeholder="Enter your category name" class="box" required>
            <input type="file" name="image" class="box" required>
            <button type="submit" name="submit" class="btn">Create Now</button>

        </form>

    </div>

    <!-- register form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>