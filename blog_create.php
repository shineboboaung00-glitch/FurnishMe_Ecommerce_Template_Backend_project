<?php 
include 'components/connection.php';

if (isset($_POST['submit'])) {
    $title =mysqli_real_escape_string($connection, $_POST['title']);
    $description =mysqli_real_escape_string($connection, $_POST['description']);
    $date =mysqli_real_escape_string($connection, $_POST['date']);
    $creator =mysqli_real_escape_string($connection, $_POST['creator']);
    $image = $_FILES['image']['name'];

    $data = "INSERT INTO blog (title,description,date,creator,image) VALUES ('$title','$description','$date','$creator','$image')";

    if (!empty($image)) {
        move_uploaded_file($_FILES['image']['tmp_name'], 'static/' .$image);
    }

    $connection->query($data);
    header('location: blog.php');
    exit();
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Create</title>

    <?php include('components/css.php'); ?>
</head>

<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <!-- register form start  -->

    <div class="register-form">
        <form method="POST" enctype="multipart/form-data">
            <h3>Blog Create Form</h3>

            <input type="text" name="title" placeholder="Enter blog name" class="box" required>
            <input type="text" name="description" placeholder="Enter blog description" class="box" required>
            <input type="file" name="image" class="box" required>
            <input type="date" name="date" placeholder="Enter date" class="box" required>
            <input type="text" name="creator" placeholder="Enter creator name" class="box" required>
            <button type="submit" name="submit" class="btn">Create Now</button>
        </form>
    </div>

    <!-- register form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>