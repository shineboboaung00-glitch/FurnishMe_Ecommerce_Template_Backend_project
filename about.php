<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/components/connection.php';
require_once __DIR__ . '/classes/service.php';

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
            <img src="static/about-img.jpg" alt="">
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
        </h1>

        <div class="box-container">
            <?php
            if ($services && $services->rowCount() > 0):
                while ($row = $services->fetch(PDO::FETCH_ASSOC)):
                    $s_id = $row['id'];
                    $s_title = $row['title'] ?? '';
                    $s_description = $row['description'] ?? $row['decription'] ?? '';
                    $raw_image = $row['image'] ?? '';
                    $s_image = $raw_image ? 'uploads/' . $raw_image : 'static/default.jpg';
            ?>
                <div class="box">
                    <img src="<?php echo htmlspecialchars($s_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($s_title, ENT_QUOTES, 'UTF-8'); ?>">
                    <h3><?php echo htmlspecialchars($s_title, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($s_description, ENT_QUOTES, 'UTF-8'); ?></p>

                    <!-- UPDATE BUTTON -->
                    <button onclick='openDynamicModal({
                        module: "service",
                        action: "update",
                        title: "Edit Service",
                        fields: [
                            { name: "title", label: "Service Title", type: "text" },
                            { name: "description", label: "Description", type: "textarea" },
                            { name: "image", label: "New Image (Optional)", type: "file" }
                        ],
                        data: <?php echo json_encode([
                            'id' => (string)$s_id,
                            'title' => $s_title,
                            'description' => $s_description,
                            'old_image' => $raw_image
                        ], JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS); ?>
                    })' class="btn">Update</button>

                    <!-- DELETE BUTTON -->
                    <button onclick='openDynamicModal({
                        module: "service",
                        action: "delete",
                        title: "Delete Service",
                        message: <?php echo json_encode("Are you sure you want to delete " . $s_title . "?"); ?>,
                        data: { id: "<?php echo $s_id; ?>" }
                    })' class="btn">Delete</button>
                </div>
            <?php 
                endwhile;
            else:
            ?>
                <p class="no_found_warnning">No services found!</p>
            <?php endif; ?>
        </div>

    </section>

    <!-- Dynamic Form Modal -->
    <?php include('components/dynamic_form.php'); ?>

    <!-- services section end -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>

lorem*50