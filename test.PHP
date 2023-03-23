<?php
$read = file("items/reporte.csv");

$arrayValues = array();
foreach ($read as $v) {
  $linea = str_replace(chr(13) . chr(10), ',', $v);
  list($email_, $modelo_, $semilla_, $ancho_, $velocidad_, $tasa_, $valorObtenidoTest_, $tipo_, $cantBajadas_, $densidadSiembra_, $distanciaBajadas_, $resultadoTitulo_, $resultadoNum_) = explode(",", $linea);


  if ($email_ == "gabrielmclerc@gmail.com") {
    array_push($arrayValues, [$email_, $modelo_, $semilla_, $ancho_, $velocidad_, $tasa_, $valorObtenidoTest_, $tipo_, $cantBajadas_, $densidadSiembra_, $distanciaBajadas_, $resultadoTitulo_, $resultadoNum_]);
  }
}


$out = implode("<br><br>", array_map(function ($a) {
  return implode(",", $a);
}, $arrayValues));
?>
<?php
$table = '<table style="border:1px solid black;border-collapse: collapse;text-align:center;"><thead><tr><th style="border:1px solid black;">Modelo</th><th style="border:1px solid black;">Semilla</th><th style="border:1px solid black;">Ancho (Mts.)</th><th style="border:1px solid black;">Velocidad (km/h)</th><th style="border:1px solid black;">Tasa (kg/ha)</th><th style="border:1px solid black;">Valor Obt. (kg/min)</th><th style="border:1px solid black;">Tipo</th><th style="border:1px solid black;">Cantidad Bajadas</th><th style="border:1px solid black;">Densidad siembra</th><th style="border:1px solid black;">Distancia bajadas</th><th style="border:1px solid black;"><b>Resultado</b></th> </tr></thead><tbody>';
 foreach ($arrayValues as $value) { 
  $table .= '<tr><td style="border:1px solid black;">'.$value[1].'</td><td style="border:1px solid black;">'.$value[2] .'</td><td style="border:1px solid black;">'.$value[3] .'</td><td style="border:1px solid black;">'.$value[4] .'</td><td style="border:1px solid black;">'.$value[5] .'</td><td style="border:1px solid black;">'.$value[6] .'</td><td style="border:1px solid black;">'.$value[7] .'</td><td style="border:1px solid black;">'.$value[8] .'</td><td style="border:1px solid black;">'.$value[9] .'</td><td style="border:1px solid black;">'.$value[10] .'</td><td style="border:1px solid black;">'. $value[12].' '. $value[11] .'</td></tr>';
  }
  $table .= '</tbody></table>';
echo $table;