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
            <h3>Product Create Form</h3>

            <input type="text" placeholder="Enter your product name" class="box" required>
            <input type="number" placeholder="Enter your price" name="email" class="box" required>
            <input type="number" placeholder="Enter your quantity" class="box" required>
            <input type="file" class="box" required>
            <input type="submit" value="Create Now" class="btn">
        </form>
    </div>

    <!-- register form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>