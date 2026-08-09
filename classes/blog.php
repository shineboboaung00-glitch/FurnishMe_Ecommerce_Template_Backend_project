<?php
require_once __DIR__ . '/base_model.php';

class Blog extends BaseModel
{
    protected $table = "blog"; # Data Table Name

    protected $fields = ['title', 'description', 'date', 'creator','image']; //Data Column  

    // Service For Validation 
    protected function validateInput($post_data, $file_data, $action)
    {
        $errors = [];
        $title = $post_data['title'] ?? '';
        $description = $post_data['description'] ?? '';
        $data = $post_data['data'] ?? '';
        $creator = $post_data['creator'] ?? '';

        // 1.Title Validation
        if (empty($title)) {
            $errors['title'] = "Title is required.";
        } else if (strlen($title) < 5 || strlen($title) > 25) {
            $errors['title'] = "Title must be between 5 and 25 characters.";
        }

        // 2.Description Validation
        if (empty($description)) {
            $errors['description'] = "Description is required.";
        }

        // 3.Date Validation
        if (empty($description)) {
            $errors['date'] = "Date is required.";
        }

        // 4.Creator Validation
        if (empty($creator)) {
            $errors['creator'] = "Creator is required.";
        } else if (strlen($creator) < 5 || strlen($creator) > 15) {
            $errors['creator'] = "Creator must be between 5 and 15 characters.";
        }

        // 5.Image Validation
        if ($action === 'create' && empty($file_data['image']['name'])) {
            $errors['image'] = "Product image is required.";
        }

        return $errors;
    }
}
