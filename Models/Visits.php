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
    public $check_out;
    public $check_in;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create() {


        $query = "INSERT INTO $this->table(visitor_id, resident_id, resident_phone,visit_purpose, check_in, check_out) VALUES(:visistor_id, :resident_id, :resident_phone,:purpose, :check_in, :check_out)";
       
        $select_query = "SELECT resident_id AS r_id from $this->residents_table WHERE resident_name = $this->resident_name AND phone_number = $this->resident_phone =  UNION SELECT visitor_id AS v_id FROM $this->visitors_table WHERE visitor_name = $this->visitor_name AND phone = $this->visitor_phone";
        // prepared statement
        $stmt = $this->conn->prepare($query);

        // sanitize input
        $this->visitor_id = htmlspecialchars(strip_tags($this->visitor_id));
        $this->resident_id = htmlspecialchars(strip_tags($this->resident_id));
        $this->resident_phone = htmlspecialchars(strip_tags($this->resident_phone));
        $this->resident_room = htmlspecialchars(strip_tags($this->resident_room));
        $this->visit_purpose = htmlspecialchars(strip_tags($this->visit_purpose));
        $this->check_in = htmlspecialchars(strip_tags($this->check_in));
        $this->check_out = htmlspecialchars(strip_tags($this->check_out));


        // bind params
        $stmt->bindParam(":visitor_id",$this->visitor_id);
        $stmt->bindParam(":resident_id",$this->resident_id);
        $stmt->bindParam(":resident_phone",$this->resident_phone);
        $stmt->bindParam(":resident_room",$this->resident_room);
        $stmt->bindParam(":visit_purpose",$this->visit_purpose);
        $stmt->bindParam(":check_in",$this->check_in);
        $stmt->bindParam(":check_out",$this->check_out);

        // execute statement
        $stmt->execute();
    }

    public function get() {

    }

    public function update() {

    }

    public function delete() {

    }
}
