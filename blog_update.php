<?php

include 'components/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $data = "SELECT * FROM blog WHERE id=$id";
    $read_data = $connection->query($data);
    $old_data = mysqli_fetch_array($read_data);
    $old_image = $old_data['image'];

    if (isset($_POST['submit'])) {
        $title = mysqli_real_escape_string($connection, $_POST['title']);
        $description = mysqli_real_escape_string($connection, $_POST['description']);
        $date = mysqli_real_escape_string($connection, $_POST['date']);
        $creator = mysqli_real_escape_string($connection, $_POST['creator']);
        $new_image = $_FILES['image']['name'];

        if (!empty($new_image)) {
            $image = $new_image;
            if (file_exists('static/' . $old_image) && !empty($old_image)) {
                unlink('static/' . $old_image);
            }
            move_uploaded_file($_FILES['image']['tmp_name'], 'static/' . $image);
        } else {
            $image = $old_image;
        }

        $update_date = "UPDATE blog SET title = '$title' , description = '$description', date = '$date', creator = '$creator' , image = '$image' WHERE id='$id'";
        $connection->query($update_date);
        header('location: blog.php');
        exit();
    }
} else {
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
    <title>Blog Update</title>

    <?php include('components/css.php'); ?>
</head>

<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <!-- register form start  -->

    <div class="register-form">
        <form method="POST" enctype="multipart/form-data">
            <h3>Blog Update Form</h3>

            <input type="text" name="title" placeholder="Enter blog name" value="<?php echo htmlspecialchars($old_data['title']) ?>" class="box" required>
            <input type="text" name="description" placeholder="Enter blog description" value="<?php echo htmlspecialchars($old_data['description']) ?>" class="box" required>
            <input type="file" name="image" class="box" required>
            <?php if ($old_data['image']): ?>
                <img src="static/<?php echo $old_data['image'] ?>" style="width: 100px; margin-top: 10px; ">
            <?php endif; ?>
            <input type="date" name="date" placeholder="Enter date" value="<?php echo htmlspecialchars($old_data['date']) ?>" class="box" required>
            <input type="text" name="creator" placeholder="Enter creator name" value="<?php echo htmlspecialchars($old_data['creator']) ?>" class="box" required>
            <button type="submit" name="submit" class="btn">Create Now</button>
        </form>
    </div>

    <!-- register form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>