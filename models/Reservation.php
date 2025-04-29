<?php
require_once __DIR__ . '/Database.php';

class Reservation
{
    private $conn;
    private $table = 'reservations';

    // Reservation Properties (match your DB columns)
    public $id;
    public $name;
    public $address;
    public $contact;
    public $check_in;
    public $check_out;
    public $room_capacity;
    public $room_type;
    public $payment_type;
    public $rate;
    public $days;
    public $total;
    public $status;
    public $created_at;

    // Constructor with DB
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Calculate Rate (moved from original index.php)
    private function calculateRoomRate($capacity, $type)
    {
        $rates = [
            'single' => ['regular' => 100.00, 'deluxe' => 300.00, 'suite' => 500.00],
            'double' => ['regular' => 200.00, 'deluxe' => 500.00, 'suite' => 800.00],
            'family' => ['regular' => 500.00, 'deluxe' => 750.00, 'suite' => 1000.00]
        ];
        // Add validation or default return if keys don't exist
        $capacityLower = strtolower($capacity);
        $typeLower = strtolower($type);
        if (isset($rates[$capacityLower]) && isset($rates[$capacityLower][$typeLower])) {
            return $rates[$capacityLower][$typeLower];
        }
        return 0; // Or throw an exception for invalid input
    }

    // Calculate Total Cost including discounts/charges (moved from original index.php)
    public function calculateTotalCost($data)
    {
        $this->rate = $this->calculateRoomRate($data['room_capacity'], $data['room_type']);
        if ($this->rate === 0) {
            throw new Exception("Invalid room capacity or type provided.");
        }

        // Basic validation for dates
        if (empty($data['check_in']) || empty($data['check_out']) || strtotime($data['check_out']) <= strtotime($data['check_in'])) {
            throw new Exception("Invalid check-in or check-out dates provided.");
        }

        $this->days = (strtotime($data['check_out']) - strtotime($data['check_in'])) / (60 * 60 * 24);
        if ($this->days <= 0) {
            throw new Exception("Check-out date must be after check-in date.");
        }

        $this->total = $this->rate * $this->days;

        // Apply payment type charges/discounts
        switch (strtolower($data['payment_type'])) {
            case 'check':
                $this->total *= 1.05; // Add 5%
                break;
            case 'credit':
                $this->total *= 1.10; // Add 10%
                break;
            case 'cash':
                if ($this->days >= 6) {
                    $this->total *= 0.85; // 15% discount
                } elseif ($this->days >= 3) {
                    $this->total *= 0.90; // 10% discount
                }
                break;
            default:
                throw new Exception("Invalid payment type provided.");
        }

        // Assign other properties from input data
        $this->name = htmlspecialchars(strip_tags($data['name']));
        $this->address = htmlspecialchars(strip_tags($data['address']));
        $this->contact = htmlspecialchars(strip_tags($data['contact']));
        $this->check_in = $data['check_in'];
        $this->check_out = $data['check_out'];
        $this->room_capacity = $data['room_capacity'];
        $this->room_type = $data['room_type'];
        $this->payment_type = $data['payment_type'];
        $this->status = 'pending'; // Default status

        return $this->total;
    }


    // Create Reservation (moved from process_reservation.php)
    public function create()
    {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' SET
                    name = :name,
                    address = :address,
                    contact = :contact,
                    check_in = :check_in,
                    check_out = :check_out,
                    room_capacity = :room_capacity,
                    room_type = :room_type,
                    payment_type = :payment_type,
                    rate = :rate,
                    days = :days,
                    total = :total,
                    status = :status'; // status is set in calculateTotalCost

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind data - properties are already sanitized/set in calculateTotalCost
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':contact', $this->contact);
        $stmt->bindParam(':check_in', $this->check_in);
        $stmt->bindParam(':check_out', $this->check_out);
        $stmt->bindParam(':room_capacity', $this->room_capacity);
        $stmt->bindParam(':room_type', $this->room_type);
        $stmt->bindParam(':payment_type', $this->payment_type);
        $stmt->bindParam(':rate', $this->rate);
        $stmt->bindParam(':days', $this->days);
        $stmt->bindParam(':total', $this->total);
        $stmt->bindParam(':status', $this->status);


        // Execute query
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId(); // Get the ID of the created reservation
            return true;
        }

        // Print error if something goes wrong
        // In a real app, log this error instead of printing
        printf("Error: %s.\n", $stmt->errorInfo()[2]); // Get specific PDO error

        return false;
    }

    // Add methods for Read (getAll, getSingle), Update, Delete later for Admin panel
    // Read all reservations
    public function readAll()
    {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get single reservation by ID
    public function readSingle($id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Set properties from the fetched row
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->address = $row['address'];
            $this->contact = $row['contact'];
            $this->check_in = $row['check_in'];
            $this->check_out = $row['check_out'];
            $this->room_capacity = $row['room_capacity'];
            $this->room_type = $row['room_type'];
            $this->payment_type = $row['payment_type'];
            $this->rate = $row['rate'];
            $this->days = $row['days'];
            $this->total = $row['total'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;

    }
    // Update reservation status
    public function updateStatus($id, $status)
    {
        $query = 'UPDATE ' . $this->table . ' SET status = :status WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $id = htmlspecialchars(strip_tags($id));
        $status = htmlspecialchars(strip_tags($status));

        // Bind data
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->errorInfo()[2]);
        return false;
    }

    // Delete reservation
    public function delete($id)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $id = htmlspecialchars(strip_tags($id));

        // Bind data
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->errorInfo()[2]);
        return false;
    }

}
?>