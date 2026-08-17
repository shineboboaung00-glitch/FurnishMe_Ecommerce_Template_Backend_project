<!-- closer btn  -->

<div id="closer" class="ri-close-line"></div>

<!-- navbar start  -->

<nav class="navbar">
    <a href="index.php">home</a>
    <a href="shop.php">shop</a>
    <a href="about.php">about</a>
    <a href="team.php">team</a>
    <a href="blog.php">blog</a>
    <a href="contact.php">contact</a>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="admin/dashboard.php" class="admin-dashboard-link">
            <i class="ri-user-settings-line"></i> Admin Dashboard
        </a>
    <?php endif; ?>

</nav>

<!-- navbar end  -->