<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/components/connection.php';
require_once __DIR__ . '/classes/category.php';
require_once __DIR__ . '/classes/product.php';

$database = new Database();
$db = $database->getConnection();

$category_object = new Category($db);
$categories = $category_object->read();

$product_object = new Product($db);
$products = $product_object->read();

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

    <!-- category section start -->
    <section class="category">
        <h1 class="title">
            <span>our categories</span>
            <button onclick="openDynamicModal({
                module: 'categories',
                action: 'create',
                title: 'Add New Category',
                fields: [
                    { name: 'name', label: 'Category Name', type: 'text', placeholder: 'Enter category name' },
                    { name: 'image', label: 'Category Image', type: 'file' }
                ]
            })" class="btn title-btn">Add new category</button>
        </h1>

        <div class="box-container">
            <?php
            if ($categories && $categories->rowCount() > 0):
                while ($row = $categories->fetch(PDO::FETCH_ASSOC)):
                    $id = $row['id'];
                    $name = $row['name'] ?? '';
                    $image = $row['image'] ? 'uploads/' . $row['image'] : 'static/default.jpg';
            ?>
                    <div class="box">
                        <a href="#">
                            <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>">
                            <h3><?php echo $name; ?></h3>
                        </a>

                        <button onclick="openDynamicModal({
                            module: 'categories',
                            action: 'update',
                            title: 'Edit Category',
                            fields: [
                                { name: 'name', label: 'Category Name', type: 'text' },
                                { name: 'image', label: 'New Image (Optional)', type: 'file' }
                            ],
                            data: { 
                                id: '<?php echo $id; ?>', 
                                name: '<?php echo $name; ?>',
                                old_image: '<?php echo $row['image']; ?>' 
                            }
                        })" class="btn">Update</button>

                        <button onclick="openDynamicModal({
                            module: 'categories',
                            action: 'delete',
                            title: 'Delete Category',
                            message: 'Are you sure you want to delete <?php echo $name; ?> ?',
                            data: { id: '<?php echo $id; ?>' }
                        })" class="btn">Delete</button>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <p class="no_found_warnning">No categories found!</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Dynamic Form Modal -->
    <?php include('components/dynamic_form.php'); ?>

    <!-- products section start -->
    <section class="products">
        <h1 class="title">
            <span>our products</span>
            <button onclick="openDynamicModal({
            module: 'product',
            action: 'create',
            title: 'Add New Product',
            fields: [
                { name: 'name', label: 'Product Name', type: 'text', placeholder: 'Enter product name' },
                { name: 'price', label: 'Price', type: 'number', placeholder: 'Enter price' },
                { name: 'quantity', label: 'Quantity', type: 'number', placeholder: 'Enter quantity' },
                { name: 'rating', label: 'Rating (1 to 5)', type: 'number', placeholder: 'e.g. 5' },
                { name: 'image', label: 'Product Image', type: 'file' }
            ]
        })" class="btn title-btn">Add new product</button>
        </h1>

        <div class="box-container">
            <?php
            if ($products && $products->rowCount() > 0):
                while ($row = $products->fetch(PDO::FETCH_ASSOC)):
                    $p_id = $row['id'];
                    $p_name = $row['name'] ?? '';
                    $p_price = $row['price'] ?? '';
                    $p_qty = $row['quantity'] ?? '';
                    $p_rating = floatval($row['rating'] ?? 0); 
                    $p_image = $row['image'] ? 'uploads/' . $row['image'] : 'static/default.jpg';
            ?>
                    <div class="box">
                        <div class="icons">
                            <button onclick="openDynamicModal({
                        module: 'product',
                        action: 'update',
                        title: 'Edit Product',
                        fields: [
                            { name: 'name', label: 'Product Name', type: 'text' },
                            { name: 'price', label: 'Price', type: 'number' },
                            { name: 'quantity', label: 'Quantity', type: 'number' },
                            { name: 'rating', label: 'Rating (1 to 5)', type: 'number' },
                            { name: 'image', label: 'New Image (Optional)', type: 'file' }
                        ],
                        data: { 
                            id: '<?php echo $p_id; ?>', 
                            name: '<?php echo $p_name; ?>',
                            price: '<?php echo $p_price; ?>',
                            quantity: '<?php echo $p_qty; ?>',
                            rating: '<?php echo $p_rating; ?>',
                            old_image: '<?php echo $row['image']; ?>' 
                        }
                    })" class="btn">Update</button>

                            <button onclick="openDynamicModal({
                        module: 'product',
                        action: 'delete',
                        title: 'Delete Product',
                        message: 'Are you sure you want to delete <?php echo $p_name; ?>?',
                        data: { id: '<?php echo $p_id; ?>' }
                    })" class="btn">Delete</button>
                        </div>

                        <div class="image">
                            <img src="<?php echo $p_image; ?>" alt="<?php echo $p_name; ?>">
                        </div>

                        <div class="content">
                            <div class="price">$<?php echo $p_price; ?></div>
                            <h3><?php echo $p_name; ?></h3>

                            
                            <div class="quantity" style="font-size: 1.4rem; color: #666; margin: 0.5rem 0;">
                                Stock: <span><?php echo $p_qty; ?></span> items
                            </div>

                            <!-- Dynamic Star Rating Display Start -->
                            <div class="stars">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $p_rating) {
                                        echo '<i class="fas fa-star"></i>'; // Full Star
                                    } elseif ($i - 0.5 <= $p_rating) {
                                        echo '<i class="fas fa-star-half-alt"></i>'; // Half Star
                                    } else {
                                        echo '<i class="far fa-star"></i>'; // Empty Star
                                    }
                                }
                                ?>
                                <span>(<?php echo $p_rating; ?>)</span>
                            </div>
                            <!-- Dynamic Star Rating Display End -->
                            
                        </div>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <p class="no_found_warnning">No products found!</p>
            <?php endif; ?>
        </div>
    </section>
    <!-- products section end -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

    <script src="js/script.js"></script>

</body>

</html>