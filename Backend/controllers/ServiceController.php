<?php
class ServiceController {
    private $db;
    private $service;

    public function __construct($db) {
        $this->db = $db;
        $this->service = new Service($db);
    }

    public function createService($data) {
        $this->service->service_id = uniqid();
        $this->service->name = $data['name'];
        $this->service->description = $data['description'];
        $this->service->price = $data['price'];
        $this->service->category = $data['category'];
        $this->service->estimated_time = $data['estimated_time'];
        $this->service->mechanic_id = $data['mechanic_id'];

        if($this->service->create()) {
            return ["message" => "Service created successfully"];
        }
        return ["message" => "Unable to create service"];
    }

    public function getServices() {
        $result = $this->service->read();
        return ["services" => $result->fetchAll(PDO::FETCH_ASSOC)];
    }
}
