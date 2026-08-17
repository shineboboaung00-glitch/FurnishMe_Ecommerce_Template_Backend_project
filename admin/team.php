<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../components/connection.php';
require_once __DIR__ . '/../classes/team.php';

// Admin Auth Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Admin Variable 
$isAdmin = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin';

$database = new Database();
$db = $database->getConnection();

$team_object = new Team($db);
$teams = $team_object->read();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Management - FurnishMe Admin</title>

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
                <h1>Team & Staff Management</h1>
                <?php if ($isAdmin): ?>
                    <button onclick="openDynamicModal({
                        module: 'team',
                        action: 'create',
                        title: 'Add New Team Member',
                        fields: [
                            { name: 'name', label: 'Member Name', type: 'text', placeholder: 'Enter name' },
                            { name: 'position', label: 'Position', type: 'text', placeholder: 'Enter position' },
                            { name: 'image', label: 'Member Image', type: 'file' }
                        ]
                    })" class="btn title-btn">Add Member</button>
                <?php endif; ?>
            </div>

            <!-- Filter Controls Section Container -->
            <div class="filter-card">
                <!-- Search Box Filter -->
                <div class="filter-group search-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="filter-input" placeholder="Search by name, email, or role...">
                </div>

                <!-- Dropdown Filters -->
                <div class="filter-controls-group">

                    <div class="custom-dropdown" id="roleDropdown">
                        <div class="dropdown-selected">
                            <span>All Roles</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-options">
                            <div class="option active" data-value="">All Roles</div>
                            <div class="option" data-value="admin">Admin</div>
                            <div class="option" data-value="manager">Manager</div>
                            <div class="option" data-value="editor">Editor</div>
                        </div>
                        <input type="hidden" name="role" value="">
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

                    <button class="filter-btn-reset" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>

                </div>
            </div>

            <!-- Header -->
            <h2 class="section-title">Team Members Management</h2>
            <div class="orders-table-container">

                <div class="orders-table">
                    <!-- Table Header -->
                    <div class="orders-header">
                        <div class="col col-id">Image</div>
                        <div class="col col-customer">Member Name</div>
                        <div class="col col-total">Position</div>
                        <div class="col col-action">Action</div>
                    </div>

                    <?php
                    if ($teams && $teams->rowCount() > 0):
                        while ($row = $teams->fetch(PDO::FETCH_ASSOC)):
                            $team_id = $row['id'];
                            $raw_name = $row['name'] ?? '';
                            $raw_position = $row['position'] ?? '';
                            $raw_image = $row['image'] ?? '';

                            if (!empty($raw_image)) {
                                $team_image = (strpos($raw_image, 'uploads/') === 0 || strpos($raw_image, 'http') === 0)
                                    ? '../' . $raw_image
                                    : '../uploads/' . $raw_image;
                            } else {
                                $team_image = '../static/default.jpg';
                            }

                            $team_update_config = json_encode([
                                'module' => 'team',
                                'action' => 'update',
                                'title'  => 'Edit Team Member',
                                'fields' => [
                                    ['name' => 'name', 'label' => 'Member Name', 'type' => 'text'],
                                    ['name' => 'position', 'label' => 'Position', 'type' => 'text'],
                                    ['name' => 'image', 'label' => 'New Image (Optional)', 'type' => 'file']
                                ],
                                'data'   => [
                                    'id'        => (string)$team_id,
                                    'name'      => $raw_name,
                                    'position'  => $raw_position,
                                    'old_image' => $raw_image
                                ]
                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                            $team_delete_config = json_encode([
                                'module'  => 'team',
                                'action'  => 'delete',
                                'title'   => 'Delete Team Member',
                                'message' => 'Are you sure you want to delete ' . $raw_name . '?',
                                'data'    => ['id' => (string)$team_id]
                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    ?>

                            <div class="orders-row">
                                <!-- 1. Image -->
                                <div class="col col-id">
                                    <img src="<?php echo htmlspecialchars($team_image, ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="<?php echo htmlspecialchars($raw_name, ENT_QUOTES, 'UTF-8'); ?>"
                                        onerror="this.onerror=null; this.src='../static/default.jpg';"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                </div>

                                <!-- 2. Member Name -->
                                <div class="col col-customer">
                                    <h3><?php echo htmlspecialchars($raw_name, ENT_QUOTES, 'UTF-8'); ?></h3>
                                </div>

                                <!-- 3. Position -->
                                <div class="col col-total"><?php echo htmlspecialchars($raw_position, ENT_QUOTES, 'UTF-8'); ?></div>

                                <!-- 4. Action -->
                                <div class="col col-action">
                                    <?php if ($isAdmin): ?>
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($team_update_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Update</button>
                                        <button onclick='openDynamicModal(<?php echo htmlspecialchars($team_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Delete</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <p class="no_found_warnning">No team members found!</p>
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