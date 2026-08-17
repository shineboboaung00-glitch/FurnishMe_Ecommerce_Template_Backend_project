<?php
$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>

<!-- FontAwesome CDN Link -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

<!-- Dashboard Sidebar Panel start  -->
<aside class="sidebar">
    <a href="dashboard.php" class="brand">
        <i class="fa-solid fa-couch"></i> Furnish<span>Me</span>
    </a>

    <ul class="sidebar-menu">
        <li>
            <a href="../admin/dashboard.php" class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="../admin/products.php" class="<?= ($currentPage == 'products.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-couch"></i> Products
            </a>
        </li>
        <li>
            <a href="../admin/categories.php" class="<?= ($currentPage == 'categories.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-layer-group"></i> Categories
            </a>
        </li>
        <li>
            <a href="#" class="<?= ($currentPage == 'orders.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-cart-shopping"></i> Orders
            </a>
        </li>
        <li>
            <a href="../admin/services.php" class="<?= ($currentPage == 'services.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-hand-holding-heart"></i> Services
            </a>
        </li>
        <li>
            <a href="../admin/team.php" class="<?= ($currentPage == 'team.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i> Our Team
            </a>
        </li>
        <li>
            <a href="../admin/blogs.php" class="<?= ($currentPage == 'blog.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-newspaper"></i> Blogs
            </a>
        </li>
        <li>
            <a href="../admin/messages.php" class="<?= ($currentPage == 'messages.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-envelope"></i> Messages
            </a>
        </li>
        <li>
            <a href="../admin/newsletter.php" class="<?= ($currentPage == 'newsletter.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-envelope-open-text"></i> Newsletters
            </a>
        </li>
        <li>
            <a href="#" class="<?= ($currentPage == 'settings.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-gear"></i> Settings
            </a>
        </li>
        <li>
            <a href="../logout.php">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </li>
    </ul>
</aside>
<!-- Dashboard Sidebar Panel end  -->