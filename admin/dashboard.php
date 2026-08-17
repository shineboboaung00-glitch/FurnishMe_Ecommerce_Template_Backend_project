<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Admin Auth Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit();
}

require_once __DIR__ . '/../components/connection.php';
require_once __DIR__ . '/../classes/product.php';
require_once __DIR__ . '/../classes/blog.php';
require_once __DIR__ . '/../classes/contact.php';
require_once __DIR__ . '/../classes/newsletter.php';
require_once __DIR__ . '/../classes/service.php';
require_once __DIR__ . '/../classes/team.php';
require_once __DIR__ . '/../classes/category.php';

$database = new Database();
$db = $database->getConnection();

$product_object = new Product($db);
$total_products = $product_object->read() ? $product_object->read()->rowCount() : 0;

$category_object = new Category($db);
$total_categories = $category_object->read() ? $category_object->read()->rowCount() : 0;

$blog_object = new Blog($db);
$total_blog = $blog_object->read() ? $blog_object->read()->rowCount() : 0;

$service_object = new Service($db);
$total_services = $service_object->read() ? $service_object->read()->rowCount() : 0;

$team_object = new Team($db);
$total_team = $team_object->read() ? $team_object->read()->rowCount() : 0;

$message_object = new Contact($db);
$messages = $message_object->read() ? $message_object->read()->rowCount() : 0;

$newsletter_object = new Newsletter($db);
$total_newsletters = $newsletter_object->read() ? $newsletter_object->read()->rowCount() : 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FurnishMe - Admin Dashboard</title>

    <!-- CSS Link -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body class="admin-body">

    <!-- 1. Sidebar Panel -->
    <?php include_once(__DIR__ . '/../components/dashboard_sidebar.php'); ?>

    <!-- 2. Main Content Area -->
    <main class="main-content">

        <!-- Top Navbar -->
        <?php include_once(__DIR__ . '/../components/dashboard_header.php'); ?>


        <!-- Dashboard Body Area -->
        <div class="dashboard-body">

            <!-- Welcome Title -->
            <div class="welcome-header">
                <h1>FurnishMe Management</h1>
                <p>Welcome to FurnishMe Admin Panel.</p>
            </div>

            <!-- Stats Overview -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="info">
                        <p>Total Products</p>
                        <h3><?php echo number_format($total_products); ?></h3>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-couch"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="info">
                        <p>Categories</p>
                        <h3><?php echo number_format($total_categories); ?></h3>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="info">
                        <p>Total Blogs</p>
                        <h3><?php echo number_format($total_blog); ?></h3>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="info">
                        <p>Messages</p>
                        <h3><?php echo number_format($messages); ?></h3>
                    </div>
                    <div class="icon-box">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Management Section -->
            <h2 class="section-title">Management Sections</h2>
            <div class="admin-category-grid">
                <a href="shop.php" class="admin-box">
                    <i class="fa-solid fa-box-archive"></i>
                    <h3>Products (<?php echo $total_products; ?>)</h3>
                </a>
                <a href="shop.php" class="admin-box">
                    <i class="fa-solid fa-layer-group"></i>
                    <h3>Categories (<?php echo $total_categories; ?>)</h3>
                </a>
                <a href="services.php" class="admin-box">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                    <h3>Services (<?php echo $total_services; ?>)</h3>
                </a>
                <a href="team.php" class="admin-box">
                    <i class="fa-solid fa-users"></i>
                    <h3>Our Team (<?php echo $total_team; ?>)</h3>
                </a>
                <a href="blog.php" class="admin-box">
                    <i class="fa-solid fa-newspaper"></i>
                    <h3>Blogs (<?php echo $total_blog; ?>)</h3>
                </a>
                <a href="contact.php" class="admin-box">
                    <i class="fa-solid fa-envelope"></i>
                    <h3>Messages (<?php echo $messages; ?>)</h3>
                </a>
            </div>


            <!-- Recent Orders Section (Div-based Grid Table) -->
            <h2 class="section-title">Recent Orders</h2>
            <div class="orders-table-container">
                <div class="orders-table">
                    <!-- Table Header -->
                    <div class="orders-header">
                        <div class="col col-id">Order ID</div>
                        <div class="col col-customer">Customer</div>
                        <div class="col col-items">Items</div>
                        <div class="col col-total">Total</div>
                        <div class="col col-status">Status</div>
                        <div class="col col-action">Action</div>
                    </div>

                    <!-- Row 1 -->
                    <div class="orders-row">
                        <div class="col col-id"><strong>#ORD-9082</strong></div>
                        <div class="col col-customer">
                            <strong>Aung Ko Ko</strong>
                            <small class="subtext">aung@gmail.com</small>
                        </div>
                        <div class="col col-items">Flexible Chair (x1)</div>
                        <div class="col col-total"><strong>$180.00</strong></div>
                        <div class="col col-status"><span class="badge badge-success">Completed</span></div>
                        <div class="col col-action">
                            <button class="btn-action">✏️ Edit</button>
                            <button class="btn-action btn-delete">🗑️ Delete</button>
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="orders-row">
                        <div class="col col-id"><strong>#ORD-9081</strong></div>
                        <div class="col col-customer">
                            <strong>Su Su San</strong>
                            <small class="subtext">susu@gmail.com</small>
                        </div>
                        <div class="col col-items">Minimalist Wooden Sofa (x1)</div>
                        <div class="col col-total"><strong>$450.00</strong></div>
                        <div class="col col-status"><span class="badge badge-warning">Pending</span></div>
                        <div class="col col-action">
                            <button class="btn-action">✏️ Edit</button>
                            <button class="btn-action btn-delete">🗑️ Delete</button>
                        </div>
                    </div>

                    <!-- Row 3 -->
                    <div class="orders-row">
                        <div class="col col-id"><strong>#ORD-9080</strong></div>
                        <div class="col col-customer">
                            <strong>Kyaw Swar</strong>
                            <small class="subtext">kyaw@gmail.com</small>
                        </div>
                        <div class="col col-items">Nordic Bath Tub (x1)</div>
                        <div class="col col-total"><strong>$720.00</strong></div>
                        <div class="col col-status"><span class="badge badge-info">Shipped</span></div>
                        <div class="col col-action">
                            <button class="btn-action">✏️ Edit</button>
                            <button class="btn-action btn-delete">🗑️ Delete</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>

</html>