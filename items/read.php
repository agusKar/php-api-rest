<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

$email = (isset($_GET['email']) && $_GET['email']) ? $_GET['email'] : null;

if (($handle = fopen("reporte.csv", "r")) !== FALSE) {
    $read=file("reporte.csv");
    $reg = 0;
    
    $arrayValues = array();
    foreach ($read as $v) {
        $linea = str_replace(chr(13).chr(10), ',', $v);
        list($email_, $saludo, $nombre)=explode(",",$linea);
        

        if ($email_ == $email) {
            array_push($arrayValues,[$email,$saludo, $nombre]);
        }
    }
    
    $final_array = ["items" => $arrayValues, "count"=> count($arrayValues)];
    http_response_code(200);
    echo json_encode($final_array);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No item found.")
    );
}
