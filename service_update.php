<?php
include 'components/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $data = "SELECT * FROM service WHERE id=$id";
    $read_data = $connection->query($data);
    $old_data = mysqli_fetch_array($read_data);
    $old_image = $old_data['image'];

    if (isset($_POST['submit'])) {
        $title = mysqli_real_escape_string($connection, $_POST['title']);
        $description = mysqli_real_escape_string($connection, $_POST['description']);

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

        $update_date = "UPDATE service SET title = '$title' , description = '$description', image = '$image' WHERE id='$id'";
        $connection->query($update_date);
        header('location: about.php');
        exit();
    }
} else {
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
    <title>Service Update</title>

    <?php include('components/css.php'); ?>
</head>

<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <!-- register form start  -->

    <div class="register-form">

        <form method="POST" enctype="multipart/form-data">
            <h3>Service Edit Form</h3>

            <input type="text" name="title" placeholder="Enter Service Title" value="<?php echo htmlspecialchars($old_data['title']) ?>" class="box" required>
            <input type="text" name="description" placeholder="Enter Description" value="<?php echo htmlspecialchars($old_data['description']) ?>" class="box" required>
            <input type="file" name="image" class="box">
            <?php if ($old_data['image']): ?>
                <img src="static/<?php echo $old_data['image'] ?>" style="width: 100px; height: 100px; ">
            <?php endif; ?>
            <button type="submit" name="submit" class="btn">Update Now</button>

        </form>

    </div>

    <!-- register form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>