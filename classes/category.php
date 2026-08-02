<?php

require_once __DIR__ . '/../traits/uploadtrait.php';

class Category
{
    use uploadtrait;

    private $conn;
    private $table = "category";

    public $id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 1.Create
    public function create($post_data, $file_data)
    {

        // Data Check
        $errors = $this->vaildateInput($post_data);

        // Image File Check
        if (empty($file_data['image']['name'])) {
            $errors['image'] = "Image is required";
        }

        // Error Check
        if (!empty($errors)) {
            return ['status' => false, 'errors' => $errors];
        }

        $image_name = $this->uploadImage($file_data['image'], 'uploads');

        $data = "INSERT INTO " . $this->table . " (name, image) VALUES (:name, :image)";

        $read_data = $this->conn->prepare($data);

        $name = htmlspecialchars(strip_tags($post_data['name']));

        $read_data->bindparam(":name", $name);
        $read_data->bindparam(":image", $image_name);

        if ($read_data->execute()) {
            return ['status' => true];
        }
        return ['status' => false];
    }

    // 2.Read All 
    public function read()
    {
        $data = " SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $read_data = $this->conn->prepare($data);
        $read_data->execute();
        return $read_data;
    }

    // Read Single 
    public function readone()
    {
        $data = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $read_data = $this->conn->prepare($data);
        $read_data->bindparam(":id", $this->id);
        $read_data->execute();
        return $read_data->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Updata 
    public function update($post_data, $file_data, $old_image)
    {
        $errors = $this->vaildateInput($post_data);

        if (!empty($errors)) {
            return ['status' => false, 'errors' => $errors];
        }

        if (!empty($file_data['image']['name'])) {
            $this->deleteimage($old_image, 'uploads');
            $image_name = $this->uploadImage($file_data['image'], 'uploads');
        } else {
            $image_name = $old_image;
        }

        $data = "UPDATE " . $this->table . " SET name = :name, image = :image WHERE id = :id";


        $read_data = $this->conn->prepare($data);

        $name = htmlspecialchars(strip_tags($post_data['name']));

        $read_data->bindparam(":name", $name);
        $read_data->bindparam(":image", $image_name);
        $read_data->bindparam(":id", $this->id);

        if ($read_data->execute()) {
            return ['status' => true];
        }
        return ['status' => false, "errors" => ['db' => 'Update Failed']];
    }

    // 4.Delete 

    public function delete()
    {
        $old_image = $this->readone();
        if ($old_image && !empty($old_image['image'])) {
            $this->deleteimage($old_image['image'], 'uploads');
        }

        $data = " DELETE FROM " . $this->table . " WHERE id = :id ";
        $read_data = $this->conn->prepare($data);
        $read_data->bindparam(":id", $this->id);

        return $read_data->execute();
    }

    private function vaildateInput($data)
    {

        $errors = [];

        $name = $data['name'] ?? '';

        if (empty($name)) {
            $errors['name'] = "Category name is required.";
        } else if (strlen($name) < 5 || strlen($name) > 20) {
            $errors['name'] = "Category name must be between 5 and 20 characters long.";
        }

        return $errors;
    }
}
