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
    list($email_, $modelo_, $semilla_, $ancho_, $velocidad_, $tasa_, $valorObtenidoTest_, $tipo_, $cantBajadas_, $densidadSiembra_, $distanciaBajadas_, $resultadoTitulo_, $resultadoNum_) = explode(",", $linea);


    if ($email_ == $email) {
      array_push($arrayValues, [$email_, $modelo_, $semilla_, $ancho_, $velocidad_, $tasa_, $valorObtenidoTest_, $tipo_, $cantBajadas_, $densidadSiembra_, $distanciaBajadas_, $resultadoTitulo_, $resultadoNum_]);
    }
  }

  if (count($arrayValues) > 0) {
    $table = '<table style="border:1px solid black;border-collapse: collapse;text-align:center;"><thead><tr><th style="border:1px solid black;">Modelo</th><th style="border:1px solid black;">Semilla</th><th style="border:1px solid black;">Ancho (Mts.)</th><th style="border:1px solid black;">Velocidad (km/h)</th><th style="border:1px solid black;">Tasa (kg/ha)</th><th style="border:1px solid black;">Valor Obt. (kg/min)</th><th style="border:1px solid black;">Tipo</th><th style="border:1px solid black;">Cantidad Bajadas</th><th style="border:1px solid black;">Densidad siembra</th><th style="border:1px solid black;">Distancia bajadas</th><th style="border:1px solid black;"><b>Resultado</b></th> </tr></thead><tbody>';
    foreach ($arrayValues as $value) { 
      $table .= '<tr><td style="border:1px solid black;">'.$value[1].'</td><td style="border:1px solid black;">'.$value[2] .'</td><td style="border:1px solid black;">'.$value[3] .'</td><td style="border:1px solid black;">'.$value[4] .'</td><td style="border:1px solid black;">'.$value[5] .'</td><td style="border:1px solid black;">'.$value[6] .'</td><td style="border:1px solid black;">'.$value[7] .'</td><td style="border:1px solid black;">'.$value[8] .'</td><td style="border:1px solid black;">'.$value[9] .'</td><td style="border:1px solid black;">'.$value[10] .'</td><td style="border:1px solid black;">'. $value[12].' '. $value[11] .'</td></tr>';
      }
      $table .= '</tbody></table>';
    $body = "
  <p>El siguiente mensaje fue enviado desde el calculador de Duam:</p>
  <p><strong>Email:</strong> $email</p>
  <p><strong>Reportes:</strong> $table</p>";
    require "class.phpmailer.php";
    $mail = new phpmailer();
    $mail->PluginDir = "";
    $mail->Mailer = "smtp";
    $mail->Host = "mail.duam.com.ar";
    $mail->SMTPAuth = true;
    $mail->Username = "info@duam.com.ar";
    $mail->Password = "SEe89!CPecz";
    $mail->From = "$email";
    $mail->FromName = "Calculador Duam";
    $mail->Timeout = 30;
    $mail->AddAddress($email);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = "Envio de reportes Calculador";
    $mail->Body = "$body";
    $mail->MsgHTML($body);
    $mail->IsHTML(true);
    $mail->AltBody =
      "E-mail: $email
     Reportes: $table";

    $exito = $mail->Send();

    $intentos = 1;
    while ((!$exito) && ($intentos < 5)) {
      sleep(5);
      $exito = $mail->Send();
      $intentos = $intentos + 1;
    }
    if (!$exito) {
      $finalMsg = "Hubo problemas intentando enviar tu mensaje." . $mail->ErrorInfo;
    }
    if ($exito) {
      http_response_code(200);
      echo json_encode(array("status" => true, "message" => "El email se envio correctamente"));
    } else {
      http_response_code(404);
      echo json_encode(array("status" => false, "message" => "Error al enviar el email."));
    }

  }else{
    http_response_code(404);
    echo json_encode(array("status" => false, "message" => "No hay items con este email."));
  }
} else {
  http_response_code(404);
  echo json_encode(array("status" => false, "message" => "Error en la autentificacion."));
}
