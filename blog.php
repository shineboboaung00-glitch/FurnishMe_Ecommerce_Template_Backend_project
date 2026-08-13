<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/components/connection.php';
require_once __DIR__ . '/classes/blog.php';

$isAdmin = isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

$database = new Database();
$db = $database->getConnection();

$blog_object = new Blog($db);
$blog = $blog_object->read();

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

    <?php
    // session_start();

    // if (!isset($_SESSION['username'])) {
    //     header('Location: login.php');
    //     exit();
    // }
    ?>



    <?php include('components/header.php'); ?>


    <?php include('components/navbar.php'); ?>

    <!-- heading section start -->

    <section class="heading">
        <h3>our shop</h3>
        <p><a href="index.php">home</a> / <span>blog</span></p>
    </section>

    <!-- heading section end -->

    <!-- blog section start -->

    <section class="blog">
        <h1 class="title">
            <span>our blogs</span>
            <?php if ($isAdmin): ?>
                <button onclick="openDynamicModal({
                module: 'blog',
                action: 'create',
                title: 'Add New Blog',
                fields: [
                    { name: 'title', label: 'Blog Title', type: 'text', placeholder: 'Enter Blog Title' },
                    { name: 'description', label: 'Description', type: 'textarea', placeholder: 'Enter Description' },
                    { name: 'date', label: 'Date', type: 'date', placeholder: 'Enter Date' },
                    { name: 'creator', label: 'Creator Name', type: 'text', placeholder: 'Enter Creator Name' },
                    { name: 'image', label: 'Blog Image', type: 'file' }
                ]
            })" class="btn title-btn">Add New Blog</button>
            <?php endif; ?>
        </h1>

        <div class="box-container">

            <?php
            if ($blog && $blog->rowCount() > 0):
                while ($row = $blog->fetch(PDO::FETCH_ASSOC)):
                    $blog_id = $row['id'];
                    $blog_title = $row['title'] ?? '';
                    $blog_description = $row['description'] ?? '';
                    $blog_date = $row['date'] ?? '';
                    $blog_creator = $row['creator'] ?? '';
                    $raw_image = $row['image'] ?? '';
                    $blog_image = $raw_image ? 'uploads/' . $raw_image : 'static/default.jpg';
            ?>

                    <div class="box">
                        <div class="image">
                            <img src="<?php echo htmlspecialchars($blog_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($blog_title, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="content">
                            <h3><?php echo htmlspecialchars($blog_title, ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><?php echo htmlspecialchars($blog_description, ENT_QUOTES, 'UTF-8'); ?></p>

                            <!-- UPDATE BUTTON -->
                            <?php if ($isAdmin): ?>
                                <button onclick='openDynamicModal({
                        module: "blog",
                        action: "update",
                        title: "Edit blog",
                        fields: [
                            { name: "title", label: "Blog Title", type: "text" },
                            { name: "description", label: "Description", type: "textarea" },
                            { name: "date", label: "Date", type: "date"},
                            { name: "creator", label: "Creator Name", type: "text"},
                            { name: "image", label: "Team Mamber New Image (Optional)", type: "file" }
                            
                        ],
                        data: <?php echo json_encode([
                                    'id' => (string)$blog_id,
                                    'title' => $blog_title,
                                    'description' => $blog_description,
                                    'date' => $blog_date,
                                    'creator' => $blog_creator,
                                    'old_image' => $raw_image
                                ], JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS); ?>
                    })' class="btn">Update</button>
                            <?php endif; ?>

                            <!-- DELETE BUTTON -->
                            <?php if ($isAdmin): ?>
                                <button onclick='openDynamicModal({
                        module: "blog",
                        action: "delete",
                        title: "Delete Blog",
                        message: <?php echo json_encode("Are you sure you want to delete " . $blog_title . "?"); ?>,
                        data: { id: "<?php echo $blog_id; ?>" }
                    })' class="btn">Delete</button>
                            <?php endif; ?>


                            <div class="icons">
                                <i class="fas fa-calendar"><?php echo htmlspecialchars($blog_date, ENT_QUOTES, 'UTF-8'); ?></i>
                                <i class="fas fa-user">by <?php echo htmlspecialchars($blog_creator, ENT_QUOTES, 'UTF-8'); ?></i>
                            </div>
                        </div>
                    </div>

                <?php
                endwhile;
            else:
                ?>
                <p class="no_found_warnning">No blogs found!</p>
            <?php endif; ?>



        </div>
    </section>

    <!-- blog section end -->


    <!-- Dynamic Form Modal -->
    <?php include('components/dynamic_form.php'); ?>

    <?php include('components/footer.php'); ?>

    <?php include('components/js.php'); ?>

</body>

</html>