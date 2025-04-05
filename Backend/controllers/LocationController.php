<?php
class LocationController {
    private $db;
    private $location;

    public function __construct($db) {
        $this->db = $db;
        $this->location = new Location($db);
    }

    public function addLocation($data) {
        $this->location->location_id = uniqid();
        $this->location->address = $data['address'];
        $this->location->latitude = $data['latitude'];
        $this->location->longitude = $data['longitude'];

        if($this->location->create()) {
            return ["message" => "Location added successfully"];
        }
        return ["message" => "Unable to add location"];
    }

    public function findNearbyMechanics($lat, $lng, $radius = 10) {
        $result = $this->location->getNearbyMechanics($lat, $lng, $radius);
        return ["mechanics" => $result->fetchAll(PDO::FETCH_ASSOC)];
    }
}
