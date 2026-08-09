<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/components/connection.php';
require_once __DIR__ . '/classes/team.php';

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
    <title>Home</title>

    <?php include('components/css.php'); ?>

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
            <button onclick="openDynamicModal({
                module: 'team',
                action: 'create',
                title: 'Add New Team Member',
                fields: [
                    { name: 'name', label: 'Team Mamber Name', type: 'text', placeholder: 'Team Mamber Name' },
                    { name: 'position', label: 'Position', type: 'text', placeholder: 'Enter Position' },
                    { name: 'image', label: 'Team Mamber Image', type: 'file' }
                ]
            })" class="btn title-btn">Add New Team Member</button>
        </h1>

        <div class="box-container">
            <?php
            if ($teams && $teams->rowCount() > 0):
                while ($row = $teams->fetch(PDO::FETCH_ASSOC)):
                    $team_id = $row['id'];
                    $team_mamber_name = $row['name'] ?? '';
                    $position = $row['position'] ?? $row['position'] ?? '';
                    $t_image = $row['image'] ?? '';
                    $team_image = $t_image ? 'uploads/' . $t_image : 'static/default.jpg';
            ?>

                    <div class="box">

                        <div class="icons">
                            <!-- UPDATE BUTTON -->
                            <button onclick='openDynamicModal({
                        module: "team",
                        action: "update",
                        title: "Edit Service",
                        fields: [
                            { name: "name", label: "Team Mamber Name", type: "text" },
                            { name: "position", label: "Position", type: "text" },
                            { name: "image", label: "Team Mamber New Image (Optional)", type: "file" }
                        ],
                        data: <?php echo json_encode([
                                    'id' => (string)$team_id,
                                    'name' => $team_mamber_name,
                                    'position' => $position,
                                    'old_image' => $t_image
                                ], JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS); ?>
                    })' class="btn">Update</button>

                            <!-- DELETE BUTTON -->
                            <button onclick='openDynamicModal({
                        module: "team",
                        action: "delete",
                        title: "Delete Team Mamber",
                        message: <?php echo json_encode("Are you sure you want to delete " . $team_mamber_name . "?"); ?>,
                        data: { id: "<?php echo $team_id; ?>" }
                    })' class="btn">Delete</button>

                        </div>
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
                <p class="no_found_warnning">No services found!</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- team section end -->

    <!-- Dynamic Form Modal -->
    <?php include('components/dynamic_form.php'); ?>

    <?php include('components/footer.php'); ?>

    <?php include('components/js.php'); ?>

</body>

</html>