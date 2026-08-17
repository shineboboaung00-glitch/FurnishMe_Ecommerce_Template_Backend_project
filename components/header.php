<?php
error_reporting(1);
session_start();
?>

<!-- header section starts  -->

<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />


<header class="header">
    <a href="index.php" class="logo"> <i class="ri-menu-line"></i> FurnishMe </a>

    <form action="#" class="search-form">
        <input type="search" placeholder="search here..." id="search-box">
        <label for="search-box" class="ri-search-line"></label>
    </form>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="admin/dashboard.php" class="btn-admin-dashboard">
            <i class="ri-user-settings-line"></i> Dashboard
        </a>
    <?php endif; ?>

    <div class="icons">
        <div id="search-btn" class="ri-search-line"></div>
        <div id="cart-btn" class="ri-shopping-cart-line"></div>

        <?php if (isset($_SESSION['username'])): ?>
            <a href="logout.php" id="logout-btn" class="ri-logout-box-line"></a>
        <?php else: ?>
            <a href="login.php" id="login-btn" class="ri-user-line"></a>
        <?php endif; ?>

        <div id="menu-btn" class="ri-menu-line"></div>
    </div>

</header>

<!-- header section ends  -->

<!-- shopping cart start  -->

<div class="shopping-cart">

    <div class="box">
        <i class="ri-close-line close-icon"></i>
        <img src="image/cart-img-1.jpg" alt="">
        <div class="content">
            <h3>modern furniture</h3>
            <span class="quantity">1</span>
            <span class="multiply">x</span>
            <span class="price">$140.00</span>
        </div>
    </div>

    <div class="box">
        <i class="ri-close-line close-icon"></i>
        <img src="image/cart-img-2.jpg" alt="">
        <div class="content">
            <h3>modern furniture</h3>
            <span class="quantity">1</span>
            <span class="multiply">x</span>
            <span class="price">$140.00</span>
        </div>
    </div>

    <div class="box">
        <i class="ri-close-line close-icon"></i>
        <img src="image/cart-img-3.jpg" alt="">
        <div class="content">
            <h3>modern furniture</h3>
            <span class="quantity">1</span>
            <span class="multiply">x</span>
            <span class="price">$140.00</span>
        </div>
    </div>

    <div class="box">
        <i class="ri-close-line close-icon"></i>
        <img src="image/cart-img-4.jpg" alt="">
        <div class="content">
            <h3>modern furniture</h3>
            <span class="quantity">1</span>
            <span class="multiply">x</span>
            <span class="price">$140.00</span>
        </div>
    </div>

    <h3 class="total"> total : <span>$560.00</span> </h3>
    <a href="#" class="btn">checkout cart</a>

</div>