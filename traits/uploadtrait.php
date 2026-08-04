<?php 

trait uploadtrait {
    public function uploadImage ($file, $folder = "uploads") {
        if (!empty($file['name'])) {

            $target_dir = __DIR__ . '/../' . $folder;

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name = time() . '_' . basename($file['name']);
            $target_path = $target_dir . '/' . $file_name;

            if (move_uploaded_file($file['tmp_name'], $target_path )) {
                return $file_name;
            }
        }
        return null;
    }

    public function deleteimage($file_name, $folder = 'uploads') {
        $target_dir = __DIR__ . '/../' . $folder;
        $file_path = $target_dir . '/' . $file_name;

        if (!empty($file_name) && file_exists($file_path)) {
            unlink($file_path);
        }
    }

}

?>