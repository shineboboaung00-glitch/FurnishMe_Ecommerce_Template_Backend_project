<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../components/connection.php';
require_once __DIR__ . '/../classes/blog.php';

// Admin Auth Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Admin Variable
$isAdmin = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin';

$database = new Database();
$db = $database->getConnection();

$blog_object = new Blog($db);
$blog = $blog_object->read();

// Add New Blog Modal Configuration Setup
$blog_create_config = json_encode([
    'module' => 'blog',
    'action' => 'create',
    'title'  => 'Add New Blog',
    'fields' => [
        ['name' => 'title', 'label' => 'Blog Title', 'type' => 'text', 'placeholder' => 'Enter Blog Title'],
        ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'placeholder' => 'Enter Description'],
        ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'placeholder' => 'Enter Date'],
        ['name' => 'creator', 'label' => 'Creator Name', 'type' => 'text', 'placeholder' => 'Enter Creator Name'],
        ['name' => 'image', 'label' => 'Blog Image', 'type' => 'file']
    ]
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs Management - FurnishMe Admin</title>

    <!-- CSS Link -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr Custom Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_orange.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
                <h1>Blog Posts Management</h1>
                <?php if ($isAdmin): ?>
                    <button onclick='openDynamicModal(<?php echo htmlspecialchars($blog_create_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn title-btn">Add New Blog</button>
                <?php endif; ?>
            </div>

            <!-- Filter Controls Section Container -->
            <div class="filter-card">
                <!-- Search Box Filter -->
                <div class="filter-group search-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="filter-input" placeholder="Search by blog title, author, or keyword...">
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
                            <div class="option" data-value="z_to_a">Sort by: Z to A</div>
                        </div>
                        <input type="hidden" name="sort" value="">
                    </div>

                    <div class="custom-dropdown" id="statusDropdown">
                        <div class="dropdown-selected">
                            <span>All Status</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options">
                            <div class="option active" data-value="published">Published</div>
                            <div class="option" data-value="draft">Draft</div>
                        </div>
                        <input type="hidden" name="status" value="">
                    </div>

                    <button class="filter-btn-reset" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>
                </div>
            </div>

            <!-- blogs Table Section -->
            <h2 class="section-title">Blogs Management</h2>
            <div class="orders-table-container">

                <div class="orders-table">
                    <!-- Table Header -->
                    <div class="orders-header">
                        <div class="col col-id">Image</div>
                        <div class="col col-customer">Title</div>
                        <div class="col col-total">Description</div>
                        <div class="col col-items">Date</div>
                        <div class="col col-status">Creator</div>
                        <div class="col col-action">Action</div>
                    </div>

                    <?php
                    if ($blog && $blog->rowCount() > 0):
                        while ($row = $blog->fetch(PDO::FETCH_ASSOC)):
                            $blog_id = $row['id'];
                            $raw_title = $row['title'] ?? '';
                            $raw_description = $row['description'] ?? '';
                            $raw_date = $row['date'] ?? '';
                            $raw_creator = $row['creator'] ?? '';
                            $raw_image = $row['image'] ?? '';

                            if (!empty($raw_image)) {
                                $blog_image = (strpos($raw_image, 'uploads/') === 0 || strpos($raw_image, 'http') === 0)
                                    ? '../' . $raw_image
                                    : '../uploads/' . $raw_image;
                            } else {
                                $blog_image = '../static/default.jpg';
                            }

                            // Safe JSON Encoding
                            $blog_update_config = json_encode([
                                'module' => 'blog',
                                'action' => 'update',
                                'title'  => 'Edit Blog',
                                'fields' => [
                                    ['name' => 'title', 'label' => 'Blog Title', 'type' => 'text'],
                                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                                    ['name' => 'date', 'label' => 'Date', 'type' => 'date'],
                                    ['name' => 'creator', 'label' => 'Creator Name', 'type' => 'text'],
                                    ['name' => 'image', 'label' => 'Blog New Image (Optional)', 'type' => 'file']
                                ],
                                'data'   => [
                                    'id'          => (string)$blog_id,
                                    'title'       => $raw_title,
                                    'description' => $raw_description,
                                    'date'        => $raw_date,
                                    'creator'     => $raw_creator,
                                    'old_image'   => $raw_image
                                ]
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

                            $blog_delete_config = json_encode([
                                'module'  => 'blog',
                                'action'  => 'delete',
                                'title'   => 'Delete Blog',
                                'message' => 'Are you sure you want to delete ' . $raw_title . '?',
                                'data'    => ['id' => (string)$blog_id]
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                    ?>
                            <!-- Blog Row -->
                            <div class="orders-row">
                                <div class="col col-id">
                                    <img src="<?php echo htmlspecialchars($blog_image, ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?php echo htmlspecialchars($raw_title, ENT_QUOTES, 'UTF-8'); ?>"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                </div>

                                <div class="col col-customer">
                                    <h3><?php echo htmlspecialchars($raw_title, ENT_QUOTES, 'UTF-8'); ?></h3>
                                </div>

                                <div class="col col-total">
                                    <p><?php echo htmlspecialchars($raw_description, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>

                                <div class="col col-items">
                                    <span><?php echo htmlspecialchars($raw_date, ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>

                                <div class="col col-status">
                                    <div class="stars">
                                        <span class="subtext"><?php echo htmlspecialchars($raw_creator, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </div>

                                <div class="col col-action">
                                    <?php if ($isAdmin): ?>
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($blog_update_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Update</button>
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($blog_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Delete</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <p class="no_found_warnning">No blogs found!</p>
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