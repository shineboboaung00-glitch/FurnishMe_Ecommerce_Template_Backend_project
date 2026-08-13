<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/components/connection.php';

$error_message = '';

if (isset($_POST['click'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($password !== $password_confirm) {
        $error_message = "Password နှစ်ခု မတူညီပါ။ ပြန်လည်စစ်ဆေးပါ။";
    } else {
        $database = new Database();
        $db = $database->getConnection();

        // 🟢 Username သို့မဟုတ် Email တည်ရှိပြီးသား ဟုတ်/မဟုတ် စစ်ဆေးခြင်း[cite: 11]
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
        $stmt->execute([$email, $username]);

        if ($stmt->rowCount() > 0) {
            $error_message = "ဤ Username သို့မဟုတ် Email ဖြင့် အကောင့်ဖွင့်ပြီးသား ဖြစ်နေပါသည်။";
        } else {
            // Password Hashing[cite: 11]
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $role = 'user'; // Normal user အဖြစ် Default သတ်မှတ်ခြင်း[cite: 11]

            // 🟢 Insert Query[cite: 11]
            $insert_stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $success = $insert_stmt->execute([$username, $email, $hashed_password, $role]);

            if ($success) {
                session_regenerate_id(true);

                // 🟢 Auto Log-in ပေးပြီး Index.php သို့ ပို့ခြင်း[cite: 11]
                $_SESSION['user_id'] = $db->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['user_role'] = $role;

                header('Location: index.php');
                exit();
            } else {
                $error_message = "အကောင့်သစ်ပြုလုပ်ရာတွင် အမှားအယွင်းရှိနေပါသည်။";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <?php include('components/css.php'); ?>
</head>
<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <div class="register-form">
        <form action="register.php" method="post">
            <h3>Register Form</h3>

            <?php if (!empty($error_message)) { ?>
                <div class="error-message" style="color: red; margin-bottom: 1rem; font-size: 1.4rem;">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php } ?>

            <input type="text" placeholder="Enter your username" name="username" class="box" required>
            <input type="email" placeholder="Enter your email" name="email" class="box" required>
            <input type="password" placeholder="Enter your password" name="password" class="box" required>
            <input type="password" placeholder="Confirm your password" name="password_confirm" class="box" required>
            <input type="submit" value="Register Now" name="click" class="btn">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>
</html>