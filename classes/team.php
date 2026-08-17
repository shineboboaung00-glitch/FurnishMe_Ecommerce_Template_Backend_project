<?php
require_once __DIR__ . '/base_model.php';

class Team extends BaseModel
{
    protected $table = "team"; # Data Table Name

    protected $fields = ['name', 'position', 'image']; //Data Column  

    // Team For Validation 
    protected function validateInput($post_data, $file_data, $action)
    {
        $errors = [];
        $name     = $post_data['name'] ?? '';
        $position = $post_data['position'] ?? '';

        // 1. Name Validation
        if (empty($name)) {
            $errors['name'] = "Name is required.";
        } else if (strlen($name) < 5 || strlen($name) > 25) {
            $errors['name'] = "Name must be between 5 and 25 characters.";
        }

        // 2. Position Validation
        if (empty($position)) {
            $errors['position'] = "Position is required.";
        } else if (strlen($position) < 5 || strlen($position) > 30) {
            $errors['position'] = "Position must be between 5 and 30 characters.";
        }

        // 3. Image Validation
        if ($action === 'create' && empty($file_data['image']['name'])) {
            $errors['image'] = "Member image is required.";
        }

        return $errors;
    }
}
