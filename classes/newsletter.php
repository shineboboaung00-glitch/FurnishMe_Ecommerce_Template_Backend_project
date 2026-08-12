<?php
require_once __DIR__ . '/base_model.php';

class Newsletter extends BaseModel
{

    protected $table = "newsletter"; # Data Table Name

    protected $fields = ['email']; //Data Column  

    // Newsletter For Validation 
    protected function validateInput($post_data, $file_data, $action)
    {
        $errors = [];

        // 1. Email Validation
        if (empty($post_data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($post_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        return $errors;
    }
}
