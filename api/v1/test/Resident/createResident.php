<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../../../Models/Resident.php';
include_once '../../../../config/Database.php';

$database = new Database();
$db = $database->connect();

// instantiate new resident
$resident = new Resident($db);

// getting the raw posted data in json format
$data = json_decode(file_get_contents("php://input"));



$resident->name = $data->name;
$resident->email = $data->email;
$resident->phone = $data->phone;
$resident->room_number = $data->room_number;

if($resident->create())
{
    echo json_encode(
        array("message"=>"new resident added")
    );
}else {
    echo json_encode(
        array("message"=>"new resident could not be added")
    );
}