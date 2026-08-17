<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../components/connection.php';
require_once __DIR__ . '/../classes/newsletter.php';

// Admin Auth Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Admin Variable 
$isAdmin = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin';

$database = new Database();
$db = $database->getConnection();

$newsletter_object = new Newsletter($db);
$newsletters = $newsletter_object->read();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter - FurnishMe Admin</title>

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
                <h1>Newsletter & Subscribers</h1>
            </div>

            <!-- Filter Controls Section Container -->
            <div class="filter-card">
                <!-- Search Box Filter -->
                <div class="filter-group search-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="filter-input" placeholder="Search subscriber email address...">
                </div>

                <!-- Dropdown Filters -->
                <div class="filter-controls-group">

                    <div class="custom-dropdown" id="categoryDropdown">
                        <div class="dropdown-selected">
                            <span>All Status</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options">
                            <div class="option active" data-value="">Subscribed</div>
                            <div class="option" data-value="unsubscribed">Unsubscribed</div>
                        </div>

                        <input type="hidden" name="status" value="">

                    </div>

                    
                    <button class="filter-btn-reset" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>

                </div>
            </div>

            <!-- Messages Table Section -->
            <div class="orders-table-container">

                <div class="orders-table">
                    <!-- Table Header-->
                    <div class="orders-header">
                        <div class="col col-id">Email Address</div>
                        <div class="col col-action">Action</div>
                    </div>

                    <?php
                    if ($newsletters && $newsletters->rowCount() > 0):
                        while ($row = $newsletters->fetch(PDO::FETCH_ASSOC)):
                            $id = $row['id'];
                            $raw_email = $row['email'] ?? '';
                            $email = htmlspecialchars($raw_email, ENT_QUOTES, 'UTF-8');

                            // Delete Modal Config
                            $newsletter_delete_config = json_encode([
                                'module'  => 'newsletter',
                                'action'  => 'delete',
                                'title'   => 'Delete Subscriber',
                                'message' => 'Are you sure you want to delete this email address (' . $raw_email . ')?',
                                'data'    => ['id' => (string)$id]
                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    ?>
                            <!-- Message Row -->
                            <div class="orders-row">

                                <!-- Email Column -->
                                <div class="col col-id">
                                    <h3 style="text-transform: none; word-break: break-all;"><?php echo $email; ?></h3>
                                </div>

                                <!-- Action Column -->
                                <div class="col col-action">
                                    <?php if ($isAdmin): ?>
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($newsletter_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn" style="background-color: #e74c3c; color: white;" title="Delete Subscriber">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <p class="no_found_warnning">No subscriber emails found!</p>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </main>

    <!-- Dynamic Form Modal Container -->
    <?php include_once(__DIR__ . '/../components/dynamic_form.php'); ?>

    <script src="../js/script.js"></script>

</body>

</html>