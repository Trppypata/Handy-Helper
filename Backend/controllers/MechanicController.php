<?php
class MechanicController {
    private $db;
    private $mechanic;

    public function __construct($db) {
        $this->db = $db;
        $this->mechanic = new Mechanic($db);
    }

    public function registerMechanic($data) {
        $this->mechanic->mechanic_id = uniqid();
        $this->mechanic->user_id = $data['user_id'];
        $this->mechanic->company_id = $data['company_id'];
        $this->mechanic->experience_years = $data['experience_years'];
        $this->mechanic->availability_status = 'available';
        $this->mechanic->rating = 0;
        $this->mechanic->specialization_id = $data['specialization_id'];
        $this->mechanic->location_id = $data['location_id'];

        if($this->mechanic->create()) {
            return ["message" => "Mechanic registered successfully"];
        }
        return ["message" => "Unable to register mechanic"];
    }

    public function getAvailableMechanics($service_id) {
        $result = $this->mechanic->getAvailableMechanics($service_id);
        return ["mechanics" => $result->fetchAll(PDO::FETCH_ASSOC)];
    }
}
