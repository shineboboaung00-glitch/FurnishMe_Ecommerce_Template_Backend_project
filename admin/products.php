<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../components/connection.php';
require_once __DIR__ . '/../classes/product.php';

// Admin Auth Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Admin Variable 
$isAdmin = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin';

$database = new Database();
$db = $database->getConnection();

$product_object = new Product($db);
$products = $product_object->read();

// Product Create Config Setup
$prod_create_config = json_encode([
    'module' => 'product',
    'action' => 'create',
    'title'  => 'Add New Product',
    'fields' => [
        ['name' => 'name', 'label' => 'Product Name', 'type' => 'text', 'placeholder' => 'Enter product name'],
        ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'placeholder' => 'Enter price'],
        ['name' => 'quantity', 'label' => 'Quantity', 'type' => 'number', 'placeholder' => 'Enter quantity'],
        ['name' => 'rating', 'label' => 'Rating (1 to 5)', 'type' => 'number', 'placeholder' => 'e.g. 5'],
        ['name' => 'image', 'label' => 'Product Image', 'type' => 'file']
    ]
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - Admin Dashboard</title>

    <!-- CSS Link -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body class="admin-body">

    <!-- 1. Sidebar Panel -->
    <?php include_once(__DIR__ . '/../components/dashboard_sidebar.php'); ?>

    <!-- 2. Main Content Area -->
    <main class="main-content">

        <!-- Dashboard Header -->
        <?php include_once(__DIR__ . '/../components/dashboard_header.php'); ?>

        <!-- Dashboard Body Area -->
        <div class="dashboard-body">

            <!-- Product Create Button -->
            <div class="welcome-header_product">
                <h1>Products List</h1>
                <?php if ($isAdmin): ?>
                    <button onclick='openDynamicModal(<?php echo htmlspecialchars($prod_create_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn title-btn">Add new product</button>
                <?php endif; ?>
            </div>

            <!-- Filter Controls Section Container -->
            <div class="filter-card">
                <!-- Search Box Filter -->
                <div class="filter-group search-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="filter-input" placeholder="Search product name, SKU...">
                </div>

                <!-- Dropdown Filters -->
                <div class="filter-controls-group">

                    <div class="custom-dropdown" id="categoryDropdown">
                        <div class="dropdown-selected">
                            <span>All Categories</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options">
                            <div class="option active" data-value="chairs">Chairs</div>
                            <div class="option" data-value="tables">Tables</div>
                            <div class="option" data-value="bathroom">Bathroom</div>
                        </div>
                        <input type="hidden" name="category" value="">
                    </div>

                    <div class="custom-dropdown" id="statusDropdown">
                        <div class="dropdown-selected">
                            <span>All Status</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options">
                            <div class="option active" data-value="">In Stock</div>
                            <div class="option" data-value="out_of_stock">Out of Stock</div>
                        </div>
                        <input type="hidden" name="status" value="">
                    </div>

                    <div class="custom-dropdown" id="sortDropdown">
                        <div class="dropdown-selected">
                            <span>Sort By</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options">
                            <div class="option active" data-value="low_to_high">Price: Low to High</div>
                            <div class="option" data-value="high_to_low">Price: High to Low</div>
                        </div>
                        <input type="hidden" name="sort" value="">
                    </div>

                    <button class="filter-btn-reset" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>
                </div>
            </div>

            <!-- Products Table Section -->
            <h2 class="section-title">Products Management</h2>
            <div class="orders-table-container">

                <div class="orders-table">
                    <!-- Table Header -->
                    <div class="orders-header">
                        <div class="col col-id">Image</div>
                        <div class="col col-customer">Product Name</div>
                        <div class="col col-total">Price</div>
                        <div class="col col-items">Stock</div>
                        <div class="col col-status">Rating</div>
                        <div class="col col-action">Action</div>
                    </div>

                    <?php
                    if ($products && $products->rowCount() > 0):
                        while ($row = $products->fetch(PDO::FETCH_ASSOC)):
                            $p_id = $row['id'];
                            $raw_name = $row['name'] ?? '';
                            $p_name = htmlspecialchars($raw_name, ENT_QUOTES, 'UTF-8');
                            $p_price = htmlspecialchars($row['price'] ?? '', ENT_QUOTES, 'UTF-8');
                            $p_qty = htmlspecialchars($row['quantity'] ?? '', ENT_QUOTES, 'UTF-8');
                            $p_rating = floatval($row['rating'] ?? 0);
                            $raw_image = $row['image'] ?? '';

                            if (!empty($raw_image)) {
                                $p_image = (strpos($raw_image, 'uploads/') === 0 || strpos($raw_image, 'http') === 0)
                                    ? '../' . $raw_image
                                    : '../uploads/' . $raw_image;
                            } else {
                                $p_image = '../static/default.jpg';
                            }

                            // JSON Safe Encoding
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
                                    'id'        => (string)$p_id,
                                    'name'      => $raw_name,
                                    'price'     => (string)($row['price'] ?? ''),
                                    'quantity'  => (string)($row['quantity'] ?? ''),
                                    'rating'    => (string)($row['rating'] ?? ''),
                                    'old_image' => $raw_image
                                ]
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

                            $prod_delete_config = json_encode([
                                'module'  => 'product',
                                'action'  => 'delete',
                                'title'   => 'Delete Product',
                                'message' => 'Are you sure you want to delete ' . $raw_name . '?',
                                'data'    => ['id' => (string)$p_id]
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                    ?>
                            <!-- Product Row -->
                            <div class="orders-row">
                                <div class="col col-id">
                                    <img src="<?php echo htmlspecialchars($p_image, ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?php echo $p_name; ?>"
                                        onerror="this.onerror=null; this.src='../static/default.jpg';"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                </div>

                                <div class="col col-customer">
                                    <h3><?php echo $p_name; ?></h3>
                                </div>

                                <div class="col col-total">$<?php echo $p_price; ?></div>

                                <div class="col col-items"><span><?php echo $p_qty; ?></span> items</div>

                                <div class="col col-status">
                                    <div class="stars">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $p_rating) {
                                                echo '<i class="fas fa-star" style="color: gold;"></i>';
                                            } elseif ($i - 0.5 <= $p_rating) {
                                                echo '<i class="fas fa-star-half-alt" style="color: gold;"></i>';
                                            } else {
                                                echo '<i class="far fa-star" style="color: #ccc;"></i>';
                                            }
                                        }
                                        ?>
                                        <span class="subtext">(<?php echo $p_rating; ?>)</span>
                                    </div>
                                </div>

                                <div class="col col-action">
                                    <?php if ($isAdmin): ?>
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($prod_update_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Update</button>
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($prod_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn btn-delete">Delete</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <p class="no_found_warnning">No products found!</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </main>

    <!-- Dynamic Form Modal -->
    <?php include_once(__DIR__ . '/../components/dynamic_form.php'); ?>

    <script src="../js/script.js"></script>

</body>

</html>