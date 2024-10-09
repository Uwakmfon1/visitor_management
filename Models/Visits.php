<?php

class Visits
{

    private $conn;
    private $table = 'visits';
    private $residents_table = "residents";
    private $visitors_table = "visitors";
    public $visitor_id;
    public $visitor_name;
    public $visitor_phone;
    public $resident_id;
    public $resident_name;
    public $resident_phone;
    public $resident_room;
    public $visit_purpose;
    public $check_in;
    public $check_out;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {

        // sanitize input
        $this->visitor_id = htmlspecialchars(strip_tags($this->visitor_id));
        $this->resident_id = htmlspecialchars(strip_tags($this->resident_id));
        $this->resident_phone = htmlspecialchars(strip_tags($this->resident_phone));
        $this->visit_purpose = htmlspecialchars(strip_tags($this->visit_purpose));
        $this->check_in = date('Y-m-d H:i:s');
        $this->check_out = "0000-00-00 00:00:00";
        $this->check_out = null;

        // $query = "INSERT INTO $this->table(visitor_id, resident_id, resident_phone,visit_purpose, check_in, check_out) VALUES(:visistor_id, :resident_id, :resident_phone,:purpose, :check_in, :check_out)";

        // $select_query = "SELECT resident_id AS r_id from $this->residents_table WHERE resident_name = $this->resident_name AND phone_number = $this->resident_phone  UNION SELECT visitor_id AS v_id FROM $this->visitors_table WHERE visitor_name = $this->visitor_name AND phone = $this->visitor_phone";

        $select_resident_query = "SELECT resident_id FROM $this->residents_table WHERE resident_name = :resident_name AND phone_number=:resident_phone";

        $stmt = $this->conn->prepare($select_resident_query);

        // $stmt->bindParam(":resident_id", $this->resident_id, PDO::PARAM_INT);
        $stmt->bindParam(":resident_name", $this->resident_name, PDO::PARAM_STR);
        $stmt->bindParam(":resident_phone", $this->resident_phone, PDO::PARAM_STR);
        // $stmt->bindParam(":resident_room", $this->resident_room, PDO::PARAM_STR);

        // execute
        $stmt->execute();
        $resident = $stmt->fetch(PDO::FETCH_ASSOC);
        $resident_id = $resident['resident_id'];


        $select_visitor_query = "SELECT visitor_id FROM $this->visitors_table WHERE visitor_name = :visitor_name AND phone =:visitor_phone";
        $stmt = $this->conn->prepare($select_visitor_query);


        // $stmt->bindParam(":visitor_id", $this->visitor_id, PDO::PARAM_INT);
        $stmt->bindParam(":visitor_name", $this->visitor_name, PDO::PARAM_STR);
        $stmt->bindParam(":visitor_phone", $this->visitor_name, PDO::PARAM_STR);
        $stmt->bindParam(":visit_purpose", $this->visit_purpose, PDO::PARAM_STR);
        // execute
        $stmt->execute();
        $visitor = $stmt->fetch(PDO::FETCH_ASSOC);
        $visitor_id = $visitor['visitor_id'];




        // Insert sanitized and bound data into visits table
        $insert_query = "INSERT INTO $this->table (visitor_id, resident_id, resident_phone, visit_purpose, check_in, check_out) 
        VALUES(:visitor_id, :resident_id, :resident_phone, :purpose, :check_in, :check_out)";
        $stmt = $this->conn->prepare($insert_query);

        // Bind the sanitized and fetched values
        $stmt->bindParam(':visitor_id', $visitor_id, PDO::PARAM_INT);  // Assuming it's an integer
        $stmt->bindParam(':resident_id', $resident_id, PDO::PARAM_INT);  // Assuming it's an integer
        $stmt->bindParam(':resident_phone', $this->resident_phone, PDO::PARAM_STR);
        $stmt->bindParam(':purpose', $this->visit_purpose, PDO::PARAM_STR);  // Assuming you sanitize purpose elsewhere
        $stmt->bindParam(':check_in', $this->check_in, PDO::PARAM_STR);  // Assuming datetime as string
        $stmt->bindParam(':check_out', $this->check_out, PDO::PARAM_STR);  // Assuming datetime as string

       if(!$this->check_out){
        $update_checkout_query = "UPDATE $this->table 
                 SET check_out = :check_out 
                 WHERE visitor_id = :visitor_id AND resident_id = :resident_id";
       }
        // Execute the insert query
        if ($stmt->execute()) {
            echo "Visit successfully recorded.";
        } else {
            echo "Error inserting visit.";
        }
    }

    public function get() {}

    public function update() {}

    public function delete() {}
}
