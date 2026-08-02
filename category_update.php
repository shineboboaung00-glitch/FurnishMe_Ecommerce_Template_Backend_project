<?php
require_once 'components/connection.php';
require_once 'classes/category.php';

$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: shop.php');
    exit();
}

$category->id = $_GET['id'];
$old_data = $category->readone();

if (!$old_data) {
    header('Location: shop.php');
    exit();
}

$errors = [];
$message = '';

if (isset($_POST['update'])) {
    $update_data = $category->update($_POST, $_FILES, $old_data['image']);

    if ($update_data['status'] === true) {
        header('Location: shop.php');
        exit();
    } else {
        $errors = $update_data['errors'] ?? [];
        $message = $update_data['message'] ?? '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Update</title>

    <?php include('components/css.php'); ?>
</head>

<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <!-- Category Update form start  -->

    <div class="register-form">

        <form method="POST" enctype="multipart/form-data">
            <h3>Category Edit Form</h3>

            <!-- Database Error -->
            <?php if (!empty($message)): ?>
                <p style="color: red; text-align: center; font-size: 1.5rem; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            <?php endif; ?>

            <!-- Title Input Field -->
            <input type="text" name="name" placeholder="Enter your category name" class="box" value="<?php echo htmlspecialchars($_POST['name'] ?? $old_data['name'] ?? ''); ?>">
            <?php if (isset($errors['name'])): ?>
                <span style="color: red; font-size: 1.2rem; display: block; margin-top: -0.5rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($errors['name']); ?></span>
            <?php endif; ?>

            <!-- Current Image Preview -->
            <?php if (!empty($old_data['image'])): ?>
                <div style="margin-bottom: 1rem;">
                    <p>Current Image:</p>
                    <img src="uploads/<?php echo htmlspecialchars($old_data['image']); ?>" width="100" style="border-radius: 5px;">
                </div>
            <?php endif; ?>

            <!-- Image Input Field -->
            <label>New Image (Optional):</label>
            <input type="file" name="image" class="box" accept="image/*">
            <?php if (isset($errors['image'])): ?>
                <span style="color: red; font-size: 1.2rem; display: block; margin-top: -0.5rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($errors['image']); ?></span>
            <?php endif; ?>

            <button type="submit" name="update" class="btn">Update Now</button>

        </form>

    </div>

    <!-- Category Update form end  -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>