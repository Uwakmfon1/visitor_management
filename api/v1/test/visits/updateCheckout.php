<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../../../Models/Visits.php';
// include_once '../../.././config/Database.php';
include_once '../../../../config/Database.php';

$database = new Database();
$db = $database->connect();

// instantiate new resident
$visits = new Visits($db);


// getting the raw posted data in json format
$data = json_decode(file_get_contents("php://input"));



// $visits->resident_phone = $data->resident_phone;
// $visits->resident_name = $data->resident_name;
// $visits->visit_purpose = $data->visit_purpose;
// $visits->visitor_name = $data->visitor_name;
// $visits->visitor_phone = $data->visitor_phone;
// $visits->check_in = $data->check_in;

$visits->resident_id = $data->resident_id;
$visits->visitor_id = $data->visitor_id;
$visits->check_out = $data->check_out;


if($visits->update_checkout())
{
    echo json_encode(
        array("message"=>"visits checkout column updated")
    );
}else {
    echo json_encode(
        array("message"=>"new visits could not be added")
    );
}


