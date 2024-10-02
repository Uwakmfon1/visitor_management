<?php

class Resident
{

    private $conn;
    private $table = 'residents';
    public $id;
    public $name;
    public $email;
    public $phone;
    public $room_number;

    public function __construct($db)
    {
        $this->conn = $db;
    }



    public function create()
    {
        $query = "INSERT INTO $this->table (name, email, phone_number, room_number) VALUES (:name, :email, :phone,:room_number)";
        // $query = 'INSERT INTO '. $this->table .'
        //             SET 
        //             name=:name, 
        //             email=:email,
        //             phone_number=:phone,
        //              room_number = :room_number';

        // check connection
        if ($this->conn === null) {
            return "connection is null";
        }
      

        // prepared statement
        $stmt = $this->conn->prepare($query);

        // // Check if prepare statement was successful
        // if (!$stmt) {
        //     printf("Error preparing statement: %s. \n", $this->conn->errorInfo()[2]);
        //     return false;
        // }

        // sanitization of input
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->room_number = htmlspecialchars(strip_tags($this->room_number));

        // binding params
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':room_number', $this->room_number);

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
