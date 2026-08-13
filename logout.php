<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🟢 Session Variables အားလုံးကို ဖျက်ထုတ်ခြင်း
$_SESSION = array();

// 🟢 Session Cookie ကို ဖျက်စီးခြင်း
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Session ကို လုံးဝ ဖျက်ဆီးခြင်း[cite: 10]
session_destroy();

// Login page သို့ ပြန်ညွှန်းခြင်း[cite: 10]
header('Location: login.php');
exit();
?>