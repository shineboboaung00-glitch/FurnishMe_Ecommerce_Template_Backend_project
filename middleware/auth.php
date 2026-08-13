<?php
// middleware/auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * 1. Login ဝင်ထားခြင်း ရှိ/မရှိ စစ်ဆေးသည့် Middleware
 */
function checkAuth()
{
    // Session တွင် user_id မရှိပါက (သို့မဟုတ်) ဗလာဖြစ်နေပါက
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        
        // --- (A) AJAX / Fetch Request ဖြစ်ပါက JSON Error ပြန်မည် ---
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => false,
                'errors' => ['auth' => 'Unauthorized access. Please login first.']
            ]);
            exit();
        }
        
        // --- (B) ပုံမှန် Page View (HTML) Request ဖြစ်ပါက Login Page သို့ ပို့မည် ---
        header("Location: /login.php");
        exit();
    }
}

/**
 * 2. Admin Role ဟုတ်/မဟုတ် စစ်ဆေးသည့် Middleware
 */
function checkAdmin()
{
    checkAuth(); // ပထမဦးစွာ Login ဝင်ထားလား အရင်စစ်မည်

    // Admin မဟုတ်ပါက (ဥပမာ 'user' ဖြစ်ပါက)
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        
        // --- (A) AJAX Request ဖြစ်ပါက JSON Error ပြန်မည် ---
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => false,
                'errors' => ['auth' => 'Forbidden! You do not have admin access.']
            ]);
            exit();
        }

        // --- (B) ပုံမှန် Page View ဖြစ်ပါက Access Denied (သို့) dashboard သို့ ပို့မည် ---
        header("Location: /unauthorized.php"); 
        // သို့မဟုတ် header("Location: /admin/dashboard.php");
        exit();
    }
}