<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../components/connection.php';
require_once __DIR__ . '/../classes/service.php';

// Admin Auth Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Admin Variable 
$isAdmin = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin';

$database = new Database();
$db = $database->getConnection();

$service_object = new Service($db);
$services = $service_object->read();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Management - FurnishMe Admin</title>

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
                <h1>Services Management</h1>
                <?php if ($isAdmin): ?>
                    <button onclick="openDynamicModal({
                        module: 'service',
                        action: 'create',
                        title: 'Add New Service',
                        fields: [
                            { name: 'title', label: 'Service Title', type: 'text', placeholder: 'Enter Service Title' },
                            { name: 'description', label: 'Description', type: 'textarea', placeholder: 'Enter Description' },
                            { name: 'image', label: 'Service Image', type: 'file' }
                        ]
                    })" class="btn title-btn">Add New Service</button>
                <?php endif; ?>
            </div>

            <!-- Filter Controls Section Container -->
            <div class="filter-card">
                <!-- Search Box Filter -->
                <div class="filter-group search-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="filter-input" placeholder="Search by service title, tag, or keyword...">
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
                            <div class="option active" data-value="active">Active</div>
                            <div class="option" data-value="draft">Draft</div>
                        </div>
                        <input type="hidden" name="status" value="">
                    </div>

                    <!-- Filter Reset / Apply Button -->
                    <button class="filter-btn-reset" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>

                </div>
            </div>

            <!-- Services Table Section -->
            <h2 class="section-title">Services Management</h2>
            <div class="orders-table-container">

                <div class="orders-table">
                    <!-- Table Header -->
                    <div class="orders-header">
                        <div class="col col-id">Image</div>
                        <div class="col col-customer">Service Title</div>
                        <div class="col col-total">Description</div>
                        <div class="col col-action">Action</div>
                    </div>

                    <?php
                    if ($services && $services->rowCount() > 0):
                        while ($row = $services->fetch(PDO::FETCH_ASSOC)):
                            $s_id = $row['id'];
                            $raw_title = $row['title'] ?? '';
                            $raw_description = $row['description'] ?? '';
                            $raw_image = $row['image'] ?? '';

                            if (!empty($raw_image)) {
                                $s_image = (strpos($raw_image, 'uploads/') === 0 || strpos($raw_image, 'http') === 0)
                                    ? '../' . $raw_image
                                    : '../uploads/' . $raw_image;
                            } else {
                                $s_image = '../static/default.jpg';
                            }

                            $service_update_config = json_encode([
                                'module' => 'service',
                                'action' => 'update',
                                'title'  => 'Edit Service',
                                'fields' => [
                                    ['name' => 'title', 'label' => 'Service Title', 'type' => 'text'],
                                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                                    ['name' => 'image', 'label' => 'New Image (Optional)', 'type' => 'file']
                                ],
                                'data'   => [
                                    'id'          => (string)$s_id,
                                    'title'       => $raw_title,
                                    'description' => $raw_description,
                                    'old_image'   => $raw_image
                                ]
                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                            $service_delete_config = json_encode([
                                'module'  => 'service',
                                'action'  => 'delete',
                                'title'   => 'Delete Service',
                                'message' => 'Are you sure you want to delete ' . $raw_title . '?',
                                'data'    => ['id' => (string)$s_id]
                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    ?>
                            <!-- Service Row -->
                            <div class="orders-row">
                                <!-- 1. Image -->
                                <div class="col col-id">
                                    <img src="<?php echo htmlspecialchars($s_image, ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?php echo htmlspecialchars($raw_title, ENT_QUOTES, 'UTF-8'); ?>"
                                        onerror="this.onerror=null; this.src='../static/default.jpg';"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                </div>

                                <!-- 2. Title -->
                                <div class="col col-customer">
                                    <h3><?php echo htmlspecialchars($raw_title, ENT_QUOTES, 'UTF-8'); ?></h3>
                                </div>

                                <!-- 3. Description -->
                                <div class="col col-total">
                                    <p><?php echo htmlspecialchars($raw_description, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>

                                <!-- 4. Action -->
                                <div class="col col-action">
                                    <?php if ($isAdmin): ?>
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($service_update_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Update</button>
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($service_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Delete</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <p class="no_found_warnning">No services found!</p>
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