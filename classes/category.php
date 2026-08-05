<?php 

require_once __DIR__ . '/base_model.php';

class Category extends BaseModel {
    protected $table = "category"; # Data Table Name
    protected $fields = ['name', 'image']; //Data Column 

    // Category For Validation 
    protected function validateInput($post_data, $file_data, $action)
    {
        $errors = [];
        $name = $post_data['name'] ?? '';

        if (empty($name)) {
            $errors['name'] = "Category name is required.";
        }else if (strlen($name) <5 || strlen($name) >20 ) {
            $errors['name'] = "Category name must be between 5 and 20 characters long.";
        }

        if ($action === 'create' && empty($file_data['image']['name'])) {
            $errors['image'] = "Image is required.";
        }

        return $errors;

    }
}
?>