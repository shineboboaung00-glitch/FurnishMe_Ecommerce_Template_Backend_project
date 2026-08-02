<?php
require_once 'components/connection.php';

require_once 'classes/category.php';

// Database & Category instances
$database = new Database();
$db = $database->getConnection();
$category = new Category($db);
// Delete Logic
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $category->id = $_GET['id'];
    if ($category->delete()) {
        header('Location: shop.php');
        exit();
    }
}

//Read Category
$category_read_data = $category->read();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <?php include('components/css.php'); ?>

</head>

<body>

    <?php include('components/header.php'); ?>

    <?php include('components/navbar.php'); ?>

    <!-- heading section start -->

    <section class="heading">
        <h3>our shop</h3>
        <p><a href="index.php">home</a> / <span>shop</span></p>
    </section>

    <!-- heading section end -->


    <!-- category section start -->

    <section class="category">
        <h1 class="title"> <span>our categories</span> <a href="category_create.php" class="btn title-btn">Add new category</a> </h1>
        <div class="box-container">

            <?php

            if ($category_read_data && $category_read_data->rowCount() > 0) {
                while ($data = $category_read_data->fetch(PDO::FETCH_ASSOC)) {
                    $id = $data['id'];
                    $name = $data['name'];
                    $image = $data['image'];

            ?>
                    <div class="box">
                        <a href="#">
                            <img src="uploads/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($name); ?>">
                            <h3><?php echo htmlspecialchars($name); ?></h3>
                        </a>
                        <a href="category_update.php?id=<?php echo $id; ?>" class="btn">Update</a>
                        <!-- ပြင်ဆင်ပြီး Delete Link -->
                        <a href="javascript:void(0);"
                            onclick="openDeleteModal('shop.php?action=delete&id=<?php echo $data['id']; ?>')"
                            class="btn">Delete</a>
                    </div>
            <?php
                }
            } else {
                echo "<p style='font-size:1.5rem; text-align:center;'>No products found.</p>";
            }
            ?>

            <?php include('components/delete.php') ?>

        </div>
    </section>

    <!-- category section end -->


    <!-- products section start -->

    <section class="products">
        <h1 class="title"> <span>our products</span> <button onclick="window.location.href='product_create.php'" class="btn title-btn">Add new product</button></h1>

        <div class="box-container">

            <div class="box">
                <div class="icons">
                    <a href="product_update.php?id=" class="btn">Update</a>
                    <a href="product_delete.php?id=" class="btn">Delete</a>
                </div>
                <div class="image">
                    <img src="static/">
                </div>
                <div class="content">
                    <div class="price"></div>
                    <h3></h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span></span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- products section end -->

    <?php include('components/footer.php'); ?>

    <?php include('components/js.php'); ?>

</body>

</html>