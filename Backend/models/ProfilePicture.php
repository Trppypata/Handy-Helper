<?php
class ProfilePicture {
    private $conn;
    private $table = 'profile_pictures';

    public $profile_picture_id;
    public $user_id;
    public $image_url;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                profile_picture_id = :profile_picture_id,
                user_id = :user_id,
                image_url = :image_url";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':profile_picture_id', $this->profile_picture_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':image_url', $this->image_url);

        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET 
                image_url = :image_url 
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':user_id', $this->user_id);

        return $stmt->execute();
    }
}
