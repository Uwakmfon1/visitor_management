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
        $this->resident_name = htmlspecialchars(strip_tags($this->resident_name));
        $this->resident_phone = htmlspecialchars(strip_tags($this->resident_phone));
        $this->resident_room = htmlspecialchars(strip_tags($this->resident_room));
        $this->visitor_name = htmlspecialchars(strip_tags($this->visitor_name));
        $this->visitor_phone = htmlspecialchars(strip_tags($this->visitor_phone));
        $this->visit_purpose = htmlspecialchars(strip_tags($this->visit_purpose));
        $this->check_in = date('Y-m-d H:i:s');
        $this->check_out = null;

        $select_resident_query = "SELECT id, room_number FROM $this->residents_table WHERE name = :resident_name AND phone_number=:resident_phone";
        $stmt = $this->conn->prepare($select_resident_query);

        
        
        $stmt->bindParam(":resident_name", $this->resident_name, PDO::PARAM_STR);
        $stmt->bindParam(":resident_phone", $this->resident_phone, PDO::PARAM_STR);
        

        // execute
        $stmt->execute();
        $resident = $stmt->fetch(PDO::FETCH_ASSOC);
        $resident_id = $resident['id'];
        $resident_room = $resident['room_number'];

        $select_visitor_query = "SELECT id FROM $this->visitors_table WHERE name = :visitor_name AND phone =:visitor_phone";
        $stmt = $this->conn->prepare($select_visitor_query);


        $stmt->bindParam(":visitor_name", $this->visitor_name, PDO::PARAM_STR);
        $stmt->bindParam(":visitor_phone", $this->visitor_phone, PDO::PARAM_STR);
        // $stmt->bindParam(":visit_purpose", $this->visit_purpose, PDO::PARAM_STR);

        // execute
        $stmt->execute();
        $visitor = $stmt->fetch(PDO::FETCH_ASSOC);
        $visitor_id = $visitor['id'];


        // Insert sanitized and bound data into visits table
        $insert_query = "INSERT INTO $this->table (visitor_id, resident_id, resident_phone, resident_room, visit_purpose, check_in, check_out) 
        VALUES(:visitor_id, :resident_id, :resident_phone,:resident_room, :purpose, :check_in, :check_out)";
        $stmt = $this->conn->prepare($insert_query);

        // Bind the sanitized and fetched values
        $stmt->bindParam(':visitor_id', $visitor_id, PDO::PARAM_INT);  // Assuming it's an integer
        $stmt->bindParam(':resident_id', $resident_id, PDO::PARAM_INT);  // Assuming it's an integer
        $stmt->bindParam(':resident_phone', $this->resident_phone, PDO::PARAM_STR);
        $stmt->bindParam(':resident_room', $resident_room, PDO::PARAM_STR);
        $stmt->bindParam(':purpose', $this->visit_purpose, PDO::PARAM_STR);  // Assuming you sanitize purpose elsewhere
        $stmt->bindParam(':check_in', $this->check_in, PDO::PARAM_STR);  // Assuming datetime as string
        $stmt->bindParam(':check_out', $this->check_out, PDO::PARAM_STR);  // Assuming datetime as string

    
        // Execute the insert query
        if ($stmt->execute()) {
            echo "Visit successfully recorded.";
        } else {
            echo "Error inserting visit.";
        }

     
    }

    public function get() {

        $sql =  "SELECT * from $this->table";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

       $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
       echo json_encode($results);


    }

    
    public function update_checkout() {
        
        // sanitize input
        $this->check_out = htmlspecialchars(strip_tags($this->check_out));

        // select the data you want to update
        $select_column_query = "SELECT * FROM $this->table WHERE visitor_id = :visitor_id AND resident_id =:resident_id";
    
        // prepared statement
        $stmt = $this->conn->prepare($select_column_query);

        // bind parameters
        $stmt->bindParam(':visitor_id',$this->visitor_id, PDO::PARAM_INT);
        $stmt->bindParam(':resident_id',$this->resident_id, PDO::PARAM_INT);
    
        // final selection of data
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        $visitor_id = $results['visitor_id'];
        $resident_id = $results['resident_id'];

        //query to execute   
       $update_checkout_query = "UPDATE $this->table SET check_out = :check_out WHERE visitor_id = $visitor_id AND resident_id = $resident_id";
   
        // execution
        $stmt = $this->conn->prepare($update_checkout_query);
        $stmt->bindParam(':check_out', $this->check_out, PDO::PARAM_STR);
        $stmt->execute();
        
    }


    public function delete(){
        // fetch specific data you want to delete

        // sanitize input
        $this->visitor_id = htmlspecialchars(strip_tags($this->visitor_id));
        $this->resident_id = htmlspecialchars(strip_tags($this->resident_id));

        $delete_query = "DELETE FROM $this->table WHERE visitor_id = :visitor_id and resident_id = :resident_id";

        $stmt = $this->conn->prepare($delete_query);

        $stmt->bindParam(':visitor_id',$this->visitor_id,PDO::PARAM_INT);
        $stmt->bindParam(':resident_id',$this->resident_id,PDO::PARAM_INT);

        $stmt->execute();

    }



    public function deleteAll() {

        $delete_query = "DELETE FROM $this->table";

        $stmt = $this->conn->prepare($delete_query);

        $stmt->execute();
    }

    protected function checkout(){
        // code 
    }
}
