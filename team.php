<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/components/connection.php';
require_once __DIR__ . '/classes/team.php';

// Admin Auth Check
$isAdmin = isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

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
    <title>Team Page</title>

    <?php include('components/css.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <!-- heading section start -->
    <section class="heading">
        <h3>our shop</h3>
        <p><a href="index.php">home</a> / <span>team</span></p>
    </section>
    <!-- heading section end -->

    <!-- team section start -->
    <section class="team">

        <h1 class="title">
            <span>our team</span>

            <?php if ($isAdmin): ?>
                <button onclick="openDynamicModal({
                module: 'team',
                action: 'create',
                title: 'Add New Team Member',
                fields: [
                    { name: 'name', label: 'Team Member Name', type: 'text', placeholder: 'Enter Team Member Name' },
                    { name: 'position', label: 'Position', type: 'text', placeholder: 'Enter Position' },
                    { name: 'image', label: 'Team Member Image', type: 'file' }
                ]
            })" class="btn title-btn">Add New Team Member</button>
            <?php endif; ?>
        </h1>

        <div class="box-container">
            <?php
            if ($teams && $teams->rowCount() > 0):
                while ($row = $teams->fetch(PDO::FETCH_ASSOC)):
                    $team_id = $row['id'];
                    $team_mamber_name = $row['name'] ?? '';
                    $position = $row['position'] ?? '';
                    $t_image = $row['image'] ?? '';

                    if (!empty($t_image)) {
                        $team_image = (strpos($t_image, 'uploads/') === 0 || strpos($t_image, 'http') === 0)
                            ? $t_image
                            : 'uploads/' . $t_image;
                    } else {
                        $team_image = 'static/default.jpg';
                    }

                    // Team Update & Delete Config Safe Encoding
                    $team_update_config = json_encode([
                        'module' => 'team',
                        'action' => 'update',
                        'title'  => 'Edit Team Member',
                        'fields' => [
                            ['name' => 'name', 'label' => 'Team Member Name', 'type' => 'text'],
                            ['name' => 'position', 'label' => 'Position', 'type' => 'text'],
                            ['name' => 'image', 'label' => 'Team Member New Image (Optional)', 'type' => 'file']
                        ],
                        'data'   => [
                            'id' => (string)$team_id,
                            'name' => $team_mamber_name,
                            'position' => $position,
                            'old_image' => $t_image
                        ]
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                    $team_delete_config = json_encode([
                        'module'  => 'team',
                        'action'  => 'delete',
                        'title'   => 'Delete Team Member',
                        'message' => 'Are you sure you want to delete ' . $team_mamber_name . '?',
                        'data'    => ['id' => (string)$team_id]
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            ?>

                    <div class="box">
                        <?php if ($isAdmin): ?>
                            <div class="icons">
                                <!-- UPDATE BUTTON --> 
                                <button onclick='openDynamicModal(<?php echo htmlspecialchars($team_update_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Update</button>
                                <!-- DELETE BUTTON -->
                                <button onclick='openDynamicModal(<?php echo htmlspecialchars($team_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Delete</button>
                            </div>
                        <?php endif; ?>

                        <div class="image">
                            <img src="<?php echo htmlspecialchars($team_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($team_mamber_name, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="user">
                            <h3><?php echo htmlspecialchars($team_mamber_name, ENT_QUOTES, 'UTF-8'); ?></h3>
                            <span><?php echo htmlspecialchars($position, ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <p class="no_found_warnning">No team members found!</p>
            <?php endif; ?>
        </div>
    </section>
    <!-- team section end -->

    <!-- Dynamic Form Modal -->
    <?php if ($isAdmin) include('components/dynamic_form.php'); ?>

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>