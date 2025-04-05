<?php
class Location {
    private $conn;
    private $table = 'locations';

    public $location_id;
    public $address;
    public $latitude;
    public $longitude;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                location_id = :location_id,
                address = :address,
                latitude = :latitude,
                longitude = :longitude";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':location_id', $this->location_id);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':latitude', $this->latitude);
        $stmt->bindParam(':longitude', $this->longitude);

        return $stmt->execute();
    }

    public function getNearbyMechanics($lat, $lng, $radius = 10) {
        $query = "SELECT l.*, m.*, u.name as mechanic_name 
                 FROM " . $this->table . " l
                 JOIN mechanics m ON l.location_id = m.location_id
                 JOIN users u ON m.user_id = u.user_id
                 HAVING ( 
                    3959 * acos( cos( radians(:lat) ) 
                    * cos( radians( latitude ) ) 
                    * cos( radians( longitude ) - radians(:lng) ) 
                    + sin( radians(:lat) ) 
                    * sin( radians( latitude ) ) ) 
                 ) < :radius";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lat', $lat);
        $stmt->bindParam(':lng', $lng);
        $stmt->bindParam(':radius', $radius);
        $stmt->execute();
        return $stmt;
    }
}
