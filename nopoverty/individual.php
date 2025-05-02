<?php
// individual.php
class Individual {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($household_id, $name, $dob, $gender, $education_level, $employment_status, $disability) {
        try {
            $stmt = $this->conn->prepare("CALL CreateIndividual(:household_id, :name, :dob, :gender, :education, :employment, :disability)");
            $stmt->execute([
                ':household_id' => $household_id,
                ':name' => $name,
                ':dob' => $dob,
                ':gender' => $gender,
                ':education' => $education_level,
                ':employment' => $employment_status,
                ':disability' => $disability
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getAll() {
        $stmt = $this->conn->query("CALL GetAllIndividuals()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
