<?php
class SpecializationController {
    private $db;
    private $specialization;

    public function __construct($db) {
        $this->db = $db;
        $this->specialization = new Specialization($db);
    }

    public function createSpecialization($data) {
        $this->specialization->specialization_id = uniqid();
        $this->specialization->name = $data['name'];
        $this->specialization->description = $data['description'];

        if($this->specialization->create()) {
            return ["message" => "Specialization created successfully"];
        }
        return ["message" => "Unable to create specialization"];
    }

    public function getAllSpecializations() {
        $result = $this->specialization->getAll();
        return ["specializations" => $result->fetchAll(PDO::FETCH_ASSOC)];
    }
}
