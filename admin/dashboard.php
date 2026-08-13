<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🟢 1. Middleware File ကို လှမ်းခေါ်ခြင်း (admin folder ထဲရောက်နေသဖြင့် ../ ဖြင့် ပြန်ထွက်ရပါမည်)
require_once __DIR__ . '/../middleware/auth.php';

// 🛑 2. Admin မဟုတ်ပါက Auto Redirect လုပ်မည့် Middleware စစ်ဆေးခြင်း
checkAdmin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS File -->
    <!-- 🟢 အရှေ့မှာ / (Slash) ခံပေးလိုက်ပါ -->
    <link rel="stylesheet" href="/php/furniture/FurnishMe_Ecommerce_Template_Backend_project/css/style.css">

    <!-- 🟢 CSS Path ပြင်ထားပါသည် ( ../ ဖြင့် root သို့ ပြန်ထွက်ပါ ) -->
    <?php include('../components/css.php'); ?>
</head>

<body>

    <?php include('../components/header.php'); ?>

    <section class="heading" style="padding: 2rem; text-align: center;">
        <h1>Welcome to Admin Dashboard 👑</h1>
        <p>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username'] ?? $_SESSION['name'] ?? 'Admin'); ?></strong></p>

        <div style="margin-top: 2rem;">
            <a href="../shop.php" class="btn">Go to Shop Page</a>
            <a href="../logout.php" class="btn" style="background-color: red;">Logout</a>
        </div>
    </section>

    <?php include('../components/footer.php'); ?>
    <?php include('../components/js.php'); ?>

</body>

</html>