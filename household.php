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

    // Update household
    public function update($household_id, $head_name, $address, $region, $registered_date) {
        try {
            $stmt = $this->conn->prepare("CALL UpdateHousehold(:household_id, :head_name, :address, :region, :registered_date)");
            $stmt->execute([
                ':household_id' => $household_id,
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
        $stmt = $this->conn->query("CALL GetAllHouseholds");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Delete Household by ID
    public function delete($household_id) {
        try {
            // Assuming you have a stored procedure named 'DeleteHousehold'
            $stmt = $this->conn->prepare("CALL DeleteHousehold(:household_id)");
            $stmt->execute([':household_id' => $household_id]);

            // Check if the delete was successful by the number of affected rows
            if ($stmt->rowCount() > 0) {
                return true; // Deletion successful
            } else {
                return "Error: Household not found or deletion failed.";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

}
?>
