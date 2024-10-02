<?php

class Visitor
{
    
    public $conn;
    private $table='visitors';
    public $id;
    public $name;
    public $email;
    public $phone;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO $this->table (name, email, phone) VALUES (:name, :email, :phone)";

        // check connection
        if ($this->conn === null) {
            return "connection is null";
        }


        // prepared statement
        $stmt = $this->conn->prepare($query);

        // sanitization of input
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        

        // binding params
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        

        // execute statement
        if ($stmt->execute()) {
            return true;
        }

        // return false if not successfull
        printf("Error: %s. \n", $stmt->error);
        return false;
    }

    public function get() {}

    public function update() {}

    public function delete() {}
}
