<?php
class UserController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($db);
    }

    public function register($data) {
        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        $this->user->phone_number = $data['phone_number'];
        $this->user->user_type = $data['user_type'];
        $this->user->password = $data['password'];
        $this->user->user_id = uniqid();

        if($this->user->create()) {
            return ["message" => "User registered successfully"];
        }
        return ["message" => "Unable to register user"];
    }

    public function getUsers() {
        $result = $this->user->read();
        $users = $result->fetchAll(PDO::FETCH_ASSOC);
        return ["users" => $users];
    }

    public function getUser($id) {
        $result = $this->user->read($id);
        $user = $result->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : ["message" => "User not found"];
    }

    public function updateUser($id, $data) {
        $this->user->user_id = $id;
        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        $this->user->phone_number = $data['phone_number'];
        $this->user->user_type = $data['user_type'];

        if($this->user->update()) {
            return ["message" => "User updated successfully"];
        }
        return ["message" => "Unable to update user"];
    }

    public function deleteUser($id) {
        $this->user->user_id = $id;
        if($this->user->delete()) {
            return ["message" => "User deleted successfully"];
        }
        return ["message" => "Unable to delete user"];
    }
}
?>
