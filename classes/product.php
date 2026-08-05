<?php

require_once __DIR__ . '/base_model.php';

class Product extends BaseModel
{
    protected $table = "product"; # Data Table Name
    
    protected $fields = ['name', 'price', 'quantity', 'rating', 'image']; //Data Column 

    // Product For Validation 
    protected function validateInput($post_data, $file_data, $action)
    {
        $errors = [];
        $name = $post_data['name'] ?? '';
        $price = $post_data['price'] ?? '';
        $quantity = $post_data['quantity'] ?? '';
        $rating = $post_data['rating'] ?? ''; 

        // 1. Name Validation
        if (empty($name)) {
            $errors['name'] = "Product name is required.";
        } else if (strlen($name) < 5 || strlen($name) > 50) {
            $errors['name'] = "Product name must be between 5 and 50 characters.";
        }

        // 2. Price Validation
        if (empty($price) && $price !== '0') {
            $errors['price'] = "Price is required.";
        } else if (!is_numeric($price)) {
            $errors['price'] = "Price must be a valid number.";
        } else if ($price < 0) {
            $errors['price'] = "Price cannot be negative.";
        } else if ($price > 10000000) {
            $errors['price'] = "Price cannot exceed 10,000,000.";
        }

        // 3. Quantity Validation
        if (empty($quantity) && $quantity !== '0') {
            $errors['quantity'] = "Quantity is required.";
        } else if (!is_numeric($quantity)) {
            $errors['quantity'] = "Quantity must be a valid number.";
        } else if ($quantity < 0) {
            $errors['quantity'] = "Quantity cannot be negative.";
        } else if ($quantity > 100000) { 
            $errors['quantity'] = "Quantity cannot exceed 100,000.";
        }

        // 4. Rating Validation
        if (empty($rating) && $rating !== '0') {
            $errors['rating'] = "Rating is required.";
        } else if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            $errors['rating'] = "Rating must be between 1 and 5.";
        }

        // 5. Image Validation
        if ($action === 'create' && empty($file_data['image']['name'])) {
            $errors['image'] = "Product image is required.";
        }

        return $errors;
    }
}