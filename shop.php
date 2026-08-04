<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/components/connection.php';
require_once __DIR__ . '/classes/category.php';

$database = new Database();
$db = $database->getConnection();

$category_object = new Category($db);
$categories = $category_object->read();

$form_errors = $_SESSION['form_errors'] ?? null;
$old_input = $_SESSION['old_input'] ?? null;

unset($_SESSION['form_errors'], $_SESSION['old_input']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <?php include('components/css.php'); ?>

    <script>
        window.FORM_ERRORS = <?php echo json_encode($form_errors); ?>;
        window.OLD_INPUT = <?php echo json_encode($old_input); ?>;
    </script>
</head>

<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <!-- heading section start -->
    <section class="heading">
        <h3>our shop</h3>
        <p><a href="index.php">home</a> / <span>shop</span></p>
    </section>

    <!-- category section start -->
    <section class="category">
        <h1 class="title">
            <span>our categories</span>
            <button onclick="openDynamicModal({
                module: 'categories',
                action: 'create',
                title: 'Add New Category',
                fields: [
                    { name: 'name', label: 'Category Name', type: 'text', placeholder: 'Enter category name' },
                    { name: 'image', label: 'Category Image', type: 'file' }
                ]
            })" class="btn title-btn">Add new category</button>
        </h1>

        <div class="box-container">
            <?php
            if ($categories && $categories->rowCount() > 0):
                while ($row = $categories->fetch(PDO::FETCH_ASSOC)):
                    $id = $row['id'];
                    $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                    $js_safe_name = addslashes($name);
                    $image = $row['image'] ? 'uploads/' . $row['image'] : 'static/default.jpg';
            ?>
                    <div class="box">
                        <a href="#">
                            <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>">
                            <h3><?php echo $name; ?></h3>
                        </a>

                        <button onclick="openDynamicModal({
                            module: 'categories',
                            action: 'update',
                            title: 'Edit Category',
                            fields: [
                                { name: 'name', label: 'Category Name', type: 'text' },
                                { name: 'image', label: 'New Image (Optional)', type: 'file' }
                            ],
                            data: { 
                                id: '<?php echo $id; ?>', 
                                name: '<?php echo $js_safe_name; ?>',
                                old_image: '<?php echo $row['image']; ?>' 
                            }
                        })" class="btn">Update</button>

                        <button onclick="openDynamicModal({
                            module: 'categories',
                            action: 'delete',
                            title: 'Delete Category',
                            message: 'Are you sure you want to delete <?php echo $js_safe_name; ?>?',
                            data: { id: '<?php echo $id; ?>' }
                        })" class="btn">Delete</button>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <p class="no_found_warnning">No categories found!</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Dynamic Form Modal -->
    <?php include('components/dynamic_form.php'); ?>

    <!-- products section start -->

    <section class="products">
        <h1 class="title"> <span>our products</span> <button onclick="window.location.href='product_create.php'" class="btn title-btn">Add new product</button></h1>

        <div class="box-container">

            <div class="box">
                <div class="icons">
                    <a href="product_update.php?id=" class="btn">Update</a>
                    <a href="shop.php?action=delete&id=" onclick="">Delete</a>
                </div>
                <div class="image">
                    <img src="uploads/" alt="">
                </div>
                <div class="content">
                    <div class="price">$</div>
                    <h3></h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span></span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- products section end -->

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

    <script src="js/script.js"></script>

</body>

</html>