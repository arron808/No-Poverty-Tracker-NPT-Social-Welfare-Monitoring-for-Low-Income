<?php
// program.php
class Program {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

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
}
?>