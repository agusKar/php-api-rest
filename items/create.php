<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: origin, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "../config.php";
$data = json_decode(file_get_contents("php://input"));
$email = $data->email;

if ($_SERVER["HTTP_AUTHORIZATION"] == $SECRET_TOKEN && $email != '') {

    // comprobar valores
    $modelo = $data->modelo;
    $semilla = $data->semilla;
    $ancho = $data->ancho;
    $velocidad = $data->velocidad;
    $tasa = $data->tasa;
    $valorObtenidoTest = $data->valorObtenidoTest;
    $tipo = $data->tipo;
    $cantBajadas = $data->cantBajadas;
    $densidadSiembra = $data->densidadSiembra;
    $distanciaBajadas = $data->distanciaBajadas;
    $resultadoNum = $data->resultadoNum;
    $resultadoTitulo = $data->resultadoTitulo;

    if (
        !is_null($modelo) &&
        !is_null($semilla) &&
        !is_null($ancho) &&
        !is_null($velocidad) &&
        !is_null($tasa) &&
        !is_null($valorObtenidoTest) &&
        !is_null($tipo) &&
        !is_null($cantBajadas) &&
        !is_null($densidadSiembra) &&
        !is_null($distanciaBajadas) &&
        !is_null($resultadoNum) &&
        !is_null($resultadoTitulo)
    ) {
        $final_array = ["status" => true, "message" => "Se grabo con exito los datos en nuestro CSV."];
        
        $h=fopen("reporte.csv","a");
        fwrite($h,($email.','.$modelo.','.$semilla.','.$ancho.','.$velocidad.','.$tasa.','.$valorObtenidoTest.','.$tipo.','.$cantBajadas.','.$densidadSiembra.','.$distanciaBajadas.','.$resultadoTitulo.','.$resultadoNum.chr(13).chr(10)));
        fclose($h);

        http_response_code(200);
        echo json_encode($final_array);
    } else {
        $final_array = ["status" => false, "message" => "Hubo un error, vuelva a intentarlo mas tarde."];
        http_response_code(401);
        echo json_encode($final_array);
    }
} else {
    http_response_code(401);
    echo json_encode(
        array("status" => false, "message" => "Faltan datos, vuelva a intentarlo mas tarde.")
    );
}
