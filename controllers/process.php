<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../components/connection.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module = $_POST['module'] ?? '';
    $action_type = $_POST['action_type'] ?? '';
    $item_id = $_POST['item_id'] ?? null;
    $old_image = $_POST['old_image'] ?? '';

    $class_name = null;

    if ($module === 'categories') {
        require_once __DIR__ . '/../classes/category.php';
        $class_name = "Category";
    }

    if ($class_name && class_exists($class_name)) {

        $controller = new $class_name($db);

        if ($action_type === 'create') {
            $data = $controller->create($_POST, $_FILES);

            if ($data['status'] === true) {
                $_SESSION['flash_success'] = 'Created successfully';
            } else {
                $_SESSION['form_errors'] = $data['errors'];
                $_SESSION['old_input'] = $_POST;
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();

        } else if ($action_type === 'update') {

            $controller->id = $item_id;
            $data = $controller->update($_POST, $_FILES, $old_image);

            if ($data['status'] === true) {
                $_SESSION['flash_success'] = 'Updated successfully';
            } else {
                $_SESSION['form_errors'] = $data['errors'];
                $_SESSION['old_input'] = $_POST;
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();

        } else if ($action_type === 'delete') {

            $controller->id = $item_id;

            if ($controller->delete()) {
                $_SESSION['flash_success'] = 'Deleted successfully';
            } else {
                $_SESSION['form_errors'] = ['db' => 'Delete failed'];
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}