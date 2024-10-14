<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../../../Models/Visits.php';
include_once '../../../../config/Database.php';

$database = new Database();
$db = $database->connect();


// instantiate new resident
$visits = new Visits($db);

$data = json_decode(file_get_contents("php://input"));

$visits->resident_id = $data->resident_id;
$visits->visitor_id = $data->visitor_id;

// $visits->delete();

if($visits->delete()){
    echo json_encode(array("message"=>"Deleted data successfully"));
}else{
    echo json_encode(array("message"=>"Data could not be deleted"));
}
