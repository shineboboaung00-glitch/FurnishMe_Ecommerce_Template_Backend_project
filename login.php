<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Already Logged In 
if (isset($_SESSION['user_id'])) {
    if (($_SESSION['user_role'] ?? '') === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit();
}

require_once __DIR__ . '/components/connection.php';

$error_message = '';
$saved_username_or_email = $_COOKIE['username_or_email'] ?? '';

if (isset($_POST['login'])) {
    $username_or_email = trim($_POST['username_or_email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username_or_email) && !empty($password)) {

        // Database Connection

        $database = new Database();
        $db = $database->getConnection();

        // PDO Prepared Statement SQL Injection
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$username_or_email, $username_or_email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'] ?? 'user';

            // Remember Me Cookie
            if (isset($_POST['remember'])) {
                setcookie('username_or_email', $username_or_email, time() + (30 * 24 * 60 * 60), "/");
            } else {
                if (isset($_COOKIE['username_or_email'])) {
                    setcookie('username_or_email', '', time() - 3600, "/");
                }
            }

            if ($_SESSION['user_role'] === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error_message = "Username/Email သို့မဟုတ် Password မှားယွင်းနေပါသည်။";
        }
    } else {
        $error_message = "အချက်အလက်များကို ပြည့်စုံစွာ ဖြည့်စွက်ပါ၊၊";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php include('components/css.php'); ?>
</head>

<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <div class="login-form">
        <form action="login.php" method="post">
            <h3>Login Form</h3>

            <?php if (!empty($error_message)) { ?>
                <div class="error-message" style="color: red; margin-bottom: 1rem; font-size: 1.4rem;">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php } ?>

            <input type="text" placeholder="Enter your username or email" name="username_or_email" class="box" value="<?php echo htmlspecialchars($saved_username_or_email); ?>" required>
            <input type="password" placeholder="Enter your password" name="password" class="box" required>

            <div class="remember">
                <input type="checkbox" name="remember" id="remember-me" <?php echo !empty($saved_username_or_email) ? 'checked' : ''; ?>>
                <label for="remember-me">Remember me</label>
            </div>

            <input type="submit" value="Login Now" name="login" class="btn">
            <p>Forgot password? <a href="#">Click here</a></p>
            <p>Don't have an account? <a href="register.php">Create now</a></p>
        </form>
    </div>

    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>