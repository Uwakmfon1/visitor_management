<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../../../Models/Visits.php';
include_once '../../.././config/Database.php';


$database = new Database();
$db = $database->connect();

// instantiate new resident
$visits = new Visits($db);


// getting the raw posted data in json format
$data = json_decode(file_get_contents("php://input"));



$visits->visitor_id = $data->visitor_id;
$visits->resident_id = $data->resident_id;
$visits->resident_phone = $data->resdent_phone;
// $visits->resident_room = $data->resident_room;
$visits->visit_purpose = $data->visit_purpose;
$visits->check_in = $data->check_in;
$visits->check_out = $data->check_out;


if($visits->create())
{
    echo json_encode(
        array("message"=>"new visits added")
    );
}else {
    echo json_encode(
        array("message"=>"new visits could not be added")
    );
}


