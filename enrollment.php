<?php
class Enrollment {
    private $conn;
    private $table = 'enrollment';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($household_id, $program_id) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO $this->table (household_id, program_id, date_enrolled) VALUES (?, ?, NOW())");
            $stmt->execute([$household_id, $program_id]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function update($enrollment_id, $household_id, $program_id) {
        try {
            $stmt = $this->conn->prepare("UPDATE $this->table SET household_id = ?, program_id = ? WHERE enrollment_id = ?");
            $stmt->execute([$household_id, $program_id, $enrollment_id]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function delete($enrollment_id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE enrollment_id = ?");
            $stmt->execute([$enrollment_id]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getAll() {
        try {
            $query = "
                SELECT e.enrollment_id, e.household_id, e.program_id, e.date_enrolled,
                       h.head_name, p.name AS program_name
                FROM $this->table e
                JOIN households h ON e.household_id = h.household_id
                JOIN programs p ON e.program_id = p.program_id
                ORDER BY e.date_enrolled DESC
            ";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
