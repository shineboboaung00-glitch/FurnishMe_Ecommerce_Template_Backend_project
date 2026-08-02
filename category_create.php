<?php

require_once 'components/connection.php';

require_once 'classes/category.php';

$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $category->create($_POST, $_FILES);

    if ($result['status']) {
        header('Location: shop.php');
        exit();
    } else {
        $errors = $result['errors'] ?? [];
        if (isset($result['message'])) {
            $message = $result['message'];
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

    <!-- Category form start  -->

    <div class="register-form">

        <form method="POST" enctype="multipart/form-data">
            <h3>Category Create Form</h3>

            <!-- Database Error -->
            <?php if (!empty($message)): ?>
                <p style="color: red; text-align: center; font-size: 1.5rem; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            <?php endif; ?>

            <!-- Title Input Field -->
            <input type="text" name="name" placeholder="Enter your category name" class="box" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            <?php if (isset($errors['name'])): ?>
                <span style="color: red; font-size: 1.2rem; display: block; margin-top: -0.5rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($errors['name']); ?></span>
            <?php endif; ?>

            <!-- Image Input Field -->
            <input type="file" name="image" class="box" accept="image/*">
            <?php if (isset($errors['image'])): ?>
                <span style="color: red; font-size: 1.2rem; display: block; margin-top: -0.5rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($errors['image']); ?></span>
            <?php endif; ?>

            <button type="submit" name="submit" class="btn">Create Now</button>

        </form>

    </div>

    <!-- Category form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>