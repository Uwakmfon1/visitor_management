<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../../../Models/Visitor.php';
include_once '../../../../config/Database.php';

$database = new Database();
$db = $database->connect();

// instantiate new resident
$visitor = new Visitor($db);

// getting the raw posted data in json format
$data = json_decode(file_get_contents("php://input"));



$visitor->name = $data->name;
$visitor->email = $data->email;
$visitor->phone = $data->phone;


if($visitor->create())
{
    echo json_encode(
        array("message"=>"new visitor added")
    );
}else {
    echo json_encode(
        array("message"=>"new visitor could not be added")
    );
}