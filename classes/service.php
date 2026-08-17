<?php
require_once __DIR__ . '/base_model.php';

class Service extends BaseModel
{
    protected $table = "service"; # Data Table Name

    protected $fields = ['title', 'description', 'image']; //Data Column  

    // Service For Validation 
    protected function validateInput($post_data, $file_data, $action)
    {
        $errors = [];
        $title = $post_data['title'] ?? '';
        $description = $post_data['description'] ?? '';

        // 1.Title Validation
        if (empty($title)) {
            $errors['title'] = "Service Title is required.";
        } else if (strlen($title) < 5 || strlen($title) > 25) {
            $errors['title'] = "Service Title must be between 5 and 25 characters.";
        }

        // 2.Description Validation
        if (empty($description)) {
            $errors['description'] = "Description is required.";
        }

        // 3.Image Validation
        if ($action === 'create' && empty($file_data['image']['name'])) {
            $errors['image'] = "Service image is required.";
        }

        return $errors;
    }
}
