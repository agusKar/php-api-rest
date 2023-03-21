<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "../config.php";

$email = (isset($_GET['email']) && $_GET['email']) ? $_GET['email'] : null;

if ($_SERVER["HTTP_AUTHORIZATION"] == $SECRET_TOKEN  && $email != '' && ($handle = fopen("reporte.csv", "r")) !== FALSE) {
    $read = file("reporte.csv");

    $arrayValues = array();
    foreach ($read as $v) {
        $linea = str_replace(chr(13) . chr(10), ',', $v);
        list($email_, $modelo_, $semilla_, $ancho_, $velocidad_, $tasa_, $valorObtenidoTest_, $tipo_, $cantBajadas_, $resultadoNum_,$resultadoTitulo_) = explode(",", $linea);


        if ($email_ == $email) {
            array_push($arrayValues, [$email_, $modelo_, $semilla_, $ancho_, $velocidad_, $tasa_, $valorObtenidoTest_, $tipo_, $cantBajadas_, $resultadoNum_,$resultadoTitulo_]);
        }
    }

    if(count($arrayValues) == 0){
        http_response_code(401);
        echo json_encode( array("status" => false, "message" => "No hay items con este email.") );
    }else{
        $final_array = ["status" => true, "items" => $arrayValues, "count" => count($arrayValues)];
        http_response_code(200);
        echo json_encode($final_array);
    }
} else {
    http_response_code(404);
    echo json_encode( array("status" => false, "message" => "Error.") );
}
