<?php
require_once __DIR__ . '/../traits/uploadtrait.php';

class BaseModel
{
    use uploadtrait;

    protected $conn;
    protected $table;
    protected $fields = []; // Database Column
    public $id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 1. Dynamic Create
    public function create($post_data, $file_data)
    {
        $errors = $this->validateInput($post_data, $file_data, 'create');
        if (!empty($errors)) {
            return ['status' => false, 'errors' => $errors];
        }

        $image_name = null;
        if (!empty($file_data['image']['name'])) {
            $image_name = $this->uploadImage($file_data['image'], 'uploads');
        }

        $columns = $this->fields;
        $placeholders = array_map(fn($col) => ":$col", $columns);

        $sql = "INSERT INTO " . $this->table . " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->conn->prepare($sql);

        foreach ($columns as $column) {
            if ($column === 'image') {
                $stmt->bindValue(":$column", $image_name);
            } else {
                $value = htmlspecialchars(strip_tags($post_data[$column] ?? ''));
                $stmt->bindValue(":$column", $value);
            }
        }

        if ($stmt->execute()) {
            return ['status' => true];
        }
        return ['status' => false, 'errors' => ['db' => 'Create failed']];
    }

    // 2. Dynamic Update
    public function update($post_data, $file_data, $old_image)
    {
        $errors = $this->validateInput($post_data, $file_data, 'update');
        if (!empty($errors)) {
            return ['status' => false, 'errors' => $errors];
        }

        if (!empty($file_data['image']['name'])) {
            $this->deleteimage($old_image, 'uploads');
            $image_name = $this->uploadImage($file_data['image'], 'uploads');
        } else {
            $image_name = $old_image;
        }

        $set_clauses = array_map(fn($col) => "$col = :$col", $this->fields);
        $sql = "UPDATE " . $this->table . " SET " . implode(', ', $set_clauses) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        foreach ($this->fields as $column) {
            if ($column === 'image') {
                $stmt->bindValue(":$column", $image_name);
            } else {
                $value = htmlspecialchars(strip_tags($post_data[$column] ?? ''));
                $stmt->bindValue(":$column", $value);
            }
        }
        $stmt->bindValue(":id", $this->id);

        if ($stmt->execute()) {
            return ['status' => true];
        }
        return ['status' => false, 'errors' => ['db' => 'Update failed']];
    }

    // 3. Read All
    public function read()
    {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    // 4. Read Single
    public function readone()
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. Dynamic Delete
    public function delete()
    {
        $old_data = $this->readone();
        if ($old_data && !empty($old_data['image'])) {
            $this->deleteimage($old_data['image'], 'uploads');
        }

        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    
    protected function validateInput($post_data, $file_data, $action)
    {
        return [];
    }
}