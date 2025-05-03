<?php

class Household {
    private $conn;

    public function __construct($database) {
        $this->conn = $database;
    }

    public function create($head_name, $address, $region, $registered_date) {
        try {
            $stmt = $this->conn->prepare("CALL CreateHousehold(:head_name, :address, :region, :registered_date)");
            $stmt->execute([
                ':head_name' => $head_name,
                ':address' => $address,
                ':region' => $region,
                ':registered_date' => $registered_date
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getAll() {
        $stmt = $this->conn->query("CALL GetAllHouseholds()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>