<?php
class ProfilePictureController {
    private $db;
    private $profilePicture;

    public function __construct($db) {
        $this->db = $db;
        $this->profilePicture = new ProfilePicture($db);
    }

    public function uploadProfilePicture($data) {
        $this->profilePicture->profile_picture_id = uniqid();
        $this->profilePicture->user_id = $data['user_id'];
        $this->profilePicture->image_url = $data['image_url'];

        if($this->profilePicture->create()) {
            return ["message" => "Profile picture uploaded successfully"];
        }
        return ["message" => "Unable to upload profile picture"];
    }

    public function updateProfilePicture($user_id, $data) {
        $this->profilePicture->user_id = $user_id;
        $this->profilePicture->image_url = $data['image_url'];

        if($this->profilePicture->update()) {
            return ["message" => "Profile picture updated successfully"];
        }
        return ["message" => "Unable to update profile picture"];
    }
}
