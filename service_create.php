<?php 
include 'components/connection.php';

if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $image = $_FILES['image']['name'];

    $data = "INSERT INTO service (title,description,image) VALUES ('$title','$description','$image')";

    if (!empty($image)) {
        move_uploaded_file($_FILES['image']['tmp_name'], 'static/' .$image);
    }

    $connection->query($data);
    header('location: about.php');
    exit();
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

    <div class="register-form" >

        <form method="POST" enctype="multipart/form-data">
            <h3>Service Create Form</h3>

            <input type="text" name="title" placeholder="Enter Service Title" class="box" required>
            <input type="text" name="description" placeholder="Enter Description" class="box" required>
            <input type="file" name="image" class="box" required>
            <button type="submit" name="submit" class="btn">Create Now</button>

        </form>
        
    </div>

    <!-- register form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>