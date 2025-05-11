<?php
class Program {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new program
    public function create($name, $description, $provider, $eligibility_criteria) {
        try {
            $stmt = $this->conn->prepare("CALL CreateProgram(:name, :description, :provider, :eligibility)");
            $stmt->execute([
                ':name' => $name,
                ':description' => $description,
                ':provider' => $provider,
                ':eligibility' => $eligibility_criteria
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Read all programs
    public function getAll() {
        $stmt = $this->conn->query("CALL GetAllPrograms()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update an existing program
    public function update($id, $name, $description, $provider, $eligibility_criteria) {
        try {
            $stmt = $this->conn->prepare("CALL UpdateProgram(:id, :name, :description, :provider, :eligibility)");
            $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':description' => $description,
                ':provider' => $provider,
                ':eligibility' => $eligibility_criteria
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Delete a program
    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("CALL DeleteProgram(:id)");
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
?>
