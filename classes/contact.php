<?php
require_once __DIR__ . '/base_model.php';

class Contact extends BaseModel
{
    protected $table = "contact";

    protected $fields = ['name', 'phone', 'email', 'message']; 

    protected function validateInput($post_data, $file_data, $action)
    {
        $errors = [];
        $name = $post_data['name'] ?? '';
        $phone = $post_data['phone'] ?? '';
        $email = $post_data['email'] ?? '';
        $message = $post_data['message'] ?? ''; 

        // 1. Name Validation
        if (empty($name)) {
            $errors['name'] = "Name is required.";
        } else if (strlen($name) < 5 || strlen($name) > 25) {
            $errors['name'] = "Name must be between 5 and 25 characters.";
        }

        // 2. Phone Validation
        if (empty($phone)) {
            $errors['phone'] = "Phone Number is required.";
        }

        // 3. Email Validation
        if (empty($email)) {
            $errors['email'] = "Email is required.";
        }

        // 4. Message Validation
        if (empty($message)) {
            $errors['message'] = "Message is required.";
        }

        return $errors;
    }
}