<?php
class CompanyController {
    private $db;
    private $company;

    public function __construct($db) {
        $this->db = $db;
        $this->company = new Company($db);
    }

    public function createCompany($data) {
        $this->company->company_id = uniqid();
        $this->company->name = $data['name'];
        $this->company->description = $data['description'];
        $this->company->location_id = $data['location_id'];

        if($this->company->create()) {
            return ["message" => "Company created successfully"];
        }
        return ["message" => "Unable to create company"];
    }
}
