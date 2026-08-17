<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/components/connection.php';
require_once __DIR__ . '/classes/category.php';
require_once __DIR__ . '/classes/product.php';

// Admin Auth Check
$isAdmin = isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

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
    <title>Shop Page</title>

    <?php include('components/css.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

            <?php if ($isAdmin): ?>
                <button onclick="openDynamicModal({
                    module: 'categories',
                    action: 'create',
                    title: 'Add New Category',
                    fields: [
                        { name: 'name', label: 'Category Name', type: 'text', placeholder: 'Enter category name' },
                        { name: 'image', label: 'Category Image', type: 'file' }
                    ]
                })" class="btn title-btn">Add new category</button>
            <?php endif; ?>
        </h1>

        <div class="box-container">
            <?php
            if ($categories && $categories->rowCount() > 0):
                while ($row = $categories->fetch(PDO::FETCH_ASSOC)):
                    $id = $row['id'];
                    $name = $row['name'] ?? '';
                    $raw_c_image = $row['image'] ?? '';

                    if (!empty($raw_c_image)) {
                        $c_image = (strpos($raw_c_image, 'uploads/') === 0 || strpos($raw_c_image, 'http') === 0)
                            ? $raw_c_image
                            : 'uploads/' . $raw_c_image;
                    } else {
                        $c_image = 'static/default.jpg';
                    }

                    // Update & Delete Params Safe Encoding
                    $cat_update_config = json_encode([
                        'module' => 'categories',
                        'action' => 'update',
                        'title'  => 'Edit Category',
                        'fields' => [
                            ['name' => 'name', 'label' => 'Category Name', 'type' => 'text'],
                            ['name' => 'image', 'label' => 'New Image (Optional)', 'type' => 'file']
                        ],
                        'data'   => [
                            'id' => (string)$id,
                            'name' => $name,
                            'old_image' => $raw_c_image
                        ]
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                    $cat_delete_config = json_encode([
                        'module'  => 'categories',
                        'action'  => 'delete',
                        'title'   => 'Delete Category',
                        'message' => 'Are you sure you want to delete ' . $name . '?',
                        'data'    => ['id' => (string)$id]
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            ?>
                    <div class="box">
                        <a href="#">
                            <img src="<?php echo htmlspecialchars($c_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                            <h3><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h3>
                        </a>

                        <?php if ($isAdmin): ?>

                            <button onclick='openDynamicModal(<?php echo htmlspecialchars($cat_update_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Update</button>

                            <button onclick='openDynamicModal(<?php echo htmlspecialchars($cat_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Delete</button>


                        <?php endif; ?>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <p class="no_found_warnning">No categories found!</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- products section start -->
    <section class="products">
        <h1 class="title">
            <span>our products</span>

            <?php if ($isAdmin): ?>
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
            <?php endif; ?>
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
                    $raw_p_image = $row['image'] ?? '';

                    if (!empty($raw_p_image)) {
                        $p_image = (strpos($raw_p_image, 'uploads/') === 0 || strpos($raw_p_image, 'http') === 0)
                            ? $raw_p_image
                            : 'uploads/' . $raw_p_image;
                    } else {
                        $p_image = 'static/default.jpg';
                    }

                    // Product Update & Delete Params Safe Encoding
                    $prod_update_config = json_encode([
                        'module' => 'product',
                        'action' => 'update',
                        'title'  => 'Edit Product',
                        'fields' => [
                            ['name' => 'name', 'label' => 'Product Name', 'type' => 'text'],
                            ['name' => 'price', 'label' => 'Price', 'type' => 'number'],
                            ['name' => 'quantity', 'label' => 'Quantity', 'type' => 'number'],
                            ['name' => 'rating', 'label' => 'Rating (1 to 5)', 'type' => 'number'],
                            ['name' => 'image', 'label' => 'New Image (Optional)', 'type' => 'file']
                        ],
                        'data'   => [
                            'id' => (string)$p_id,
                            'name' => $p_name,
                            'price' => (string)$p_price,
                            'quantity' => (string)$p_qty,
                            'rating' => (string)$p_rating,
                            'old_image' => $raw_p_image
                        ]
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                    $prod_delete_config = json_encode([
                        'module'  => 'product',
                        'action'  => 'delete',
                        'title'   => 'Delete Product',
                        'message' => 'Are you sure you want to delete ' . $p_name . '?',
                        'data'    => ['id' => (string)$p_id]
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            ?>
                    <div class="box">
                        <?php if ($isAdmin): ?>
                            <div class="icons">



                                <button onclick='openDynamicModal(<?php echo htmlspecialchars($prod_update_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Update</button>

                                <button onclick='openDynamicModal(<?php echo htmlspecialchars($prod_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Delete</button>

                            </div>
                        <?php endif; ?>

                        <div class="image">
                            <img src="<?php echo htmlspecialchars($p_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($p_name, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="content">
                            <div class="price">$<?php echo htmlspecialchars($p_price, ENT_QUOTES, 'UTF-8'); ?></div>
                            <h3><?php echo htmlspecialchars($p_name, ENT_QUOTES, 'UTF-8'); ?></h3>

                            <div class="quantity" style="font-size: 1.4rem; color: #666; margin: 0.5rem 0;">
                                Stock: <span><?php echo htmlspecialchars($p_qty, ENT_QUOTES, 'UTF-8'); ?></span> items
                            </div>

                            <div class="stars">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $p_rating) {
                                        echo '<i class="fas fa-star"></i>';
                                    } elseif ($i - 0.5 <= $p_rating) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                ?>
                                <span>(<?php echo $p_rating; ?>)</span>
                            </div>

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

    <!-- Dynamic Form Modal -->
    <?php if ($isAdmin) include('components/dynamic_form.php'); ?>

    <?php include('components/footer.php'); ?>

    <?php include('components/js.php'); ?>

</body>

</html>