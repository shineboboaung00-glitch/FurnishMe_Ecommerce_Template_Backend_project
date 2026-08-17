<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/components/connection.php';
require_once __DIR__ . '/classes/service.php';

// Admin Auth Check
$isAdmin = isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

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
    <title>About page</title>

    <?php include('components/css.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <!-- heading section start -->
    <section class="heading">
        <h3>our shop</h3>
        <p><a href="index.php">home</a> / <span>about</span></p>
    </section>
    <!-- heading section end -->

    <!-- about section start -->
    <section class="about">
        <div class="image">
            <img src="static/about-img.jpg" alt="About Us">
        </div>

        <div class="content">
            <span>welcome to our shop</span>
            <h3>we make your home more astonishing</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid fugit labore facere non autem soluta tempora natus, sequi obcaecati esse officia aspernatur impedit dignissimos ut porro praesentium similique numquam magnam.</p>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Numquam sequi illo cum expedita, eius laboriosam? Blanditiis quisquam, iure nisi, ex amet odit obcaecati eaque voluptatibus porro repudiandae sit libero debitis!</p>
            <a href="#" class="btn">read more</a>
        </div>
    </section>
    <!-- about section end -->

    <!-- services section start -->
    <section class="services">

        <h1 class="title">
            <span>our services</span>
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
        </h1>

        <div class="box-container">
            <?php
            if ($services && $services->rowCount() > 0):
                while ($row = $services->fetch(PDO::FETCH_ASSOC)):
                    $s_id = $row['id'];
                    $s_title = $row['title'] ?? '';
                    $s_description = $row['description'] ?? '';
                    $raw_image = $row['image'] ?? '';

                    if (!empty($raw_image)) {
                        $s_image = (strpos($raw_image, 'uploads/') === 0 || strpos($raw_image, 'http') === 0)
                            ? $raw_image
                            : 'uploads/' . $raw_image;
                    } else {
                        $s_image = 'static/default.jpg';
                    }

                    // Service Update & Delete Config Safe Encoding
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
                            'id' => (string)$s_id,
                            'title' => $s_title,
                            'description' => $s_description,
                            'old_image' => $raw_image
                        ]
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                    $service_delete_config = json_encode([
                        'module'  => 'service',
                        'action'  => 'delete',
                        'title'   => 'Delete Service',
                        'message' => 'Are you sure you want to delete ' . $s_title . '?',
                        'data'    => ['id' => (string)$s_id]
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            ?>
                    <div class="box">
                        <div class="image">
                            <img src="<?php echo htmlspecialchars($s_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($s_title, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <h3><?php echo htmlspecialchars($s_title, ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($s_description, ENT_QUOTES, 'UTF-8'); ?></p>

                        <!-- UPDATE BUTTON -->
                        <?php if ($isAdmin): ?>
                            <button onclick='openDynamicModal(<?php echo htmlspecialchars($service_update_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Update</button>

                            <!-- DELETE BUTTON -->
                            <button onclick='openDynamicModal(<?php echo htmlspecialchars($service_delete_config, ENT_QUOTES, "UTF-8"); ?>)' class="btn">Delete</button>
                        <?php endif; ?>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <p class="no_found_warnning">No services found!</p>
            <?php endif; ?>
        </div>

    </section>
    <!-- services section end -->

    <!-- Dynamic Form Modal -->
    <?php if ($isAdmin) include('components/dynamic_form.php'); ?>

    <?php include('components/footer.php'); ?>
    
    <?php include('components/js.php'); ?>

</body>

</html>