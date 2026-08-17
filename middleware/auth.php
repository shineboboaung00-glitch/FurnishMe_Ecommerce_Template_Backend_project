<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Login Check

function checkAuth()
{
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => false,
                'errors' => ['auth' => 'Unauthorized access. Please login first.']
            ]);
            exit();
        }

        header("Location: /login.php");
        exit();
    }
}


// Admin Role Check

function checkAdmin()
{
    checkAuth();

    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => false,
                'errors' => ['auth' => 'Forbidden! You do not have admin access.']
            ]);
            exit();
        }

        header("Location: /unauthorized.php");
        exit();
    }
}
