<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../components/connection.php';
require_once __DIR__ . '/../classes/category.php';

//  Admin Auth Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Admin Variable 
$isAdmin = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin';

$database = new Database();
$db = $database->getConnection();

$category_object = new Category($db);
$categories = $category_object->read();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FurnishMe - Admin Dashboard</title>

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

            <!-- Welcome Title -->
            <div class="welcome-header_product">
                <h1>Categories List</h1>

                <?php
                $cat_create_config = json_encode([
                    'module' => 'categories',
                    'action' => 'create',
                    'title'  => 'Add New Category',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Category Name', 'type' => 'text', 'placeholder' => 'Enter category name'],
                        ['name' => 'image', 'label' => 'Category Image', 'type' => 'file']
                    ]
                ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                ?>

                
                <?php if ($isAdmin): ?>
                    <button onclick="openDynamicModal(<?php echo htmlspecialchars($cat_create_config, ENT_QUOTES, 'UTF-8'); ?>)" class="btn title-btn">Add new category</button>
                <?php endif; ?>
            </div>

            <!-- Filter Controls Section Container -->
            <div class="filter-card">
                <!-- Search Box Filter -->
                <div class="filter-group search-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="filter-input" placeholder="Search category name...">
                </div>

                <!-- Dropdown Filters -->
                <div class="filter-controls-group">

                    <div class="custom-dropdown" id="sortDropdown">
                        <div class="dropdown-selected">
                            <span>Sort By</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options">
                            <div class="option active" data-value="">Sort by: A to Z</div>
                            <div class="option" data-value="chairs">Sort by: Z to A</div>
                            <div class="option" data-value="tables">Newest First</div>
                            <div class="option" data-value="bathroom">Oldest First</div>
                        </div>

                        <input type="hidden" name="sort" value="">
                    </div>


                    <div class="custom-dropdown" id="statusDropdown">
                        <div class="dropdown-selected">
                            <span>All Status</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options">
                            <div class="option active" data-value="">Active</div>
                            <div class="option" data-value="inactive">Inactive</div>
                        </div>

                        <input type="hidden" name="status" value="">
                    </div>

                    <!-- Filter Reset / Apply Button -->
                    <button class="filter-btn-reset" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>

                </div>
            </div>

            <!-- Categories Table Section -->
            <h2 class="section-title">Categories Management</h2>
            <section class="orders-table-container">

                <div class="orders-table">

                    <!-- Table Header -->
                    <div class="orders-header">
                        <div class="col col-id">Image</div>
                        <div class="col col-customer">Category Name</div>
                        <div class="col col-action">Action</div>
                    </div>

                    <?php
                    if ($categories && $categories->rowCount() > 0):
                        while ($row = $categories->fetch(PDO::FETCH_ASSOC)):
                            $id = $row['id'];
                            $name = $row['name'] ?? '';
                            $raw_image = $row['image'] ?? '';

                            if (!empty($raw_image)) {
                                $image = (strpos($raw_image, 'uploads/') === 0 || strpos($raw_image, 'http') === 0)
                                    ? '../' . $raw_image
                                    : '../uploads/' . $raw_image;
                            } else {
                                $image = '../static/default.jpg';
                            }

                            // Category Update & Delete JSON Safe Encoding
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
                                    'old_image' => $raw_image
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

                            <!-- Product Row -->
                            <div class="orders-row">

                                <div class="col col-id">
                                    <img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
                                        onerror="this.onerror=null; this.src='../static/default.jpg';" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" />
                                </div>

                                <!-- 2. Product Name -->
                                <div class="col col-customer">
                                    <h3><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h3>
                                </div>

                                <!-- 6. Action -->
                                <div class="col col-action">
                                    <?php if ($isAdmin): ?>

                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($cat_update_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Update</button>

                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($cat_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Delete</button>

                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <p class="no_found_warnning">No categories found!</p>
                    <?php endif; ?>

                </div>

            </section>

        </div>
    </main>

    <!-- Dynamic Form Modal -->
    <?php include_once(__DIR__ . '/../components/dynamic_form.php'); ?>

    <script src="../js/script.js"></script>

</body>

</html>