<?php
include 'components/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $data = "SELECT * FROM product WHERE id=$id";
    $read_data = $connection->query($data);
    $old_data = mysqli_fetch_array($read_data);
    $old_image = $old_data['image'];

    if (isset($_POST['submit'])) {
        $name = mysqli_real_escape_string($connection, $_POST['name']);
        $price = mysqli_real_escape_string($connection, $_POST['price']);
        $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
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

        $update_date = "UPDATE product SET name = '$name' , price = '$price', quantity = '$quantity' , image = '$image' WHERE id='$id'";
        $connection->query($update_date);
        header('location: shop.php');
        exit();
    }
} else {
    header('location: shop.php');
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

    <div class="register-form">
        <form method="POST" enctype="multipart/form-data">
            <h3>Product Create Form</h3>

            <input type="text" name="name" placeholder="Enter your product name" value="<?php echo htmlspecialchars($old_data['name']) ?>" class="box" required>
            <input type="number" name="price" placeholder="Enter your price" value="<?php echo htmlspecialchars($old_data['price']) ?>" class="box" required>
            <input type="number" name="quantity" placeholder="Enter your quantity" value="<?php echo htmlspecialchars($old_data['quantity']) ?>" class="box" required>
            <input type="file" name="image" class="box">
            <?php if ($old_data['image']): ?>
                <img src="static/<?php echo $old_data['image'] ?>" style="width: 100px; margin-top: 10px; ">
            <?php endif; ?>
            <button type="submit" name="submit" class="btn">Update Now</button>
        </form>
    </div>

    <!-- register form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>