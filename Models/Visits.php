<?php

class Visits
{

    private $conn;
    private $table = 'visits';

    public $visitor_id;
    public $resident_id;
    public $resident_phone;
    public $resident_room;
    public $visit_purpose;
    public $check_out;
    public $check_in;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create() {}

    public function get() {}

    public function update() {}

    public function delete() {}
}
