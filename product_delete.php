<?php 
include 'components/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $data = "SELECT * FROM product WHERE id=$id";
    $read_data = $connection->query($data);
    $old_data = mysqli_fetch_array($read_data);
    $old_image = $old_data['image'];

    if (isset($_POST['submit'])) {
        $delete_query = "DELETE FROM product WHERE id=$id";
        if (!empty($old_image) && file_exists('static/' .$old_image)) {
            unlink('static/' .$old_image);
        }

        $connection->query($delete_query);
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

        <form method="POST">
            <h3>Post Delete Form</h3>
            <p>Are you sure you want to delete this post?</p>
            <a href="shop.php" class="btn">No</a>
            <button type="submit" name="submit" class="btn">Yes</button>
        </form>

    </div>

    <!-- register form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>