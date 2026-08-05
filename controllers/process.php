<?php
session_start();

require_once __DIR__ . '/../components/connection.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module = $_POST['module'] ?? '';
    $action_type = $_POST['action_type'] ?? '';
    $item_id = $_POST['item_id'] ?? null;
    $old_image = $_POST['old_image'] ?? '';

    $class_name = null;

    # Module Class Switch
    if ($module === 'categories') {
        require_once __DIR__ . '/../classes/category.php';
        $class_name = "Category";
    }else if ($module === 'product') {
        require_once __DIR__ . '/../classes/product.php';
        $class_name = "Product";
    }

    if ($class_name && class_exists($class_name)) {

        $controller = new $class_name($db);

        if ($action_type === 'create') {
            $data = $controller->create($_POST, $_FILES);
            if ($data['status'] === true) {
                $_SESSION['flash_success'] = 'Created successfully';
            }
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();

        } else if ($action_type === 'update') {

            $controller->id = $item_id;
            $data = $controller->update($_POST, $_FILES, $old_image);

            if ($data['status'] === true) {
                $_SESSION['flash_success'] = 'Updated successfully';
            }
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();

        } else if ($action_type === 'delete') {

            $controller->id = $item_id;

            if ($controller->delete()) {
                $_SESSION['flash_success'] = 'Deleted successfully';
                header('Content-Type: application/json');
                echo json_encode(['status' => true]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['status' => false, 'errors' => ['db' => 'Delete failed']]);
            }
            exit();
        }
    }
}