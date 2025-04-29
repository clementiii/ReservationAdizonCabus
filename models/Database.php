<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'hotel_db'; // Make sure this matches your database name
    private $username = 'root';
    private $password = ''; // Default XAMPP password is empty
    private $conn;

    // DB Connect
    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            // In a real app, log this error instead of echoing
            echo 'Connection Error: ' . $e->getMessage();
            // Optionally re-throw or handle more gracefully
            // throw $e; 
            // For now, returning null or exiting might be better than echoing here
            // as it could interfere with controller logic.
            return null; // Indicate connection failure
        }

        return $this->conn;
    }
}
?>