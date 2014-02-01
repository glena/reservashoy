<?php

function get($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	$raw = curl_exec($ch);

	curl_close($ch);

	$response = json_decode($raw);
	
	return $response;
}

function getUrl($apiKey, $page = '')
{
	return "http://datosdemocraticos.com.ar/api/v1/reservas_internacionales_bcra$page.json?apikey=$apiKey";

	//return "http://localhost:3000/api/v1/reservas_internacionales_bcra$page.json?apikey=$apiKey";
}


$cacheFile = 'data.json';

$apiKey = 'c237ff6028b3f772ddd10073bfa5c41a57c7032a';
//$apiKey = 'ee46da455d56a4364dc83bee9039f9c3a49e77f6';

$data = array();

$response = get(getUrl($apiKey));
$data = array_merge($data, $response->data);

$response = get(getUrl($apiKey, '/pagina/2'));
$data = array_merge($data, $response->data);

$response = get(getUrl($apiKey, '/pagina/3'));
$data = array_merge($data, $response->data);


if ($response->estado == 'ok')
{

	uasort ( $data , function ($a, $b) {
		$a = strtotime($a->fecha);
		$b = strtotime($b->fecha);
		
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? 1 : -1;
	});

	$new_data = array(
			'ultimos7dias' => array(),
			'ultimos30dias' => array(),
			'ultimos12meses' => array()
		);

	$ultimos12meses = array();

	//$hoy = time();
	$hoy = strtotime('2014-01-17');

	$hace7dias = strtotime('-7 days',$hoy);
	$hace30dias = strtotime('-30 days',$hoy);
	
	$primeroDeMes = strtotime('-'.(date('d',$hoy)-1).' days',$hoy);
	$hace12meses = strtotime('-12 months',$hoy);

	foreach ($data as $item)
	{
		echo "Procesando " . $item->fecha;
		echo "\n";
		list($dia,$mes,$anio) = explode('/', $item->fecha);
		//$fecha = strtotime($item->fecha);
		$fecha = strtotime("$anio-$mes-$dia");
		$item->fecha = date('Y-m-d', $fecha);

		if (!isset($item->informacion))
		{
			$item->informacion = 'Informacion fecha ' . date('Y-m-d', $fecha);
		}

		if ($fecha >= $hace7dias)
		{
			echo "\t - 7 dias ";
			echo "\n";
			$new_data['ultimos7dias'][] = $item;
		}

		if ($fecha >= $hace30dias)
		{
			echo "\t - 30 dias ";
			echo "\n";
			$new_data['ultimos30dias'][] = $item;
		}

		if ($fecha >= $hace12meses)
		{
			echo "\t - mes " . date('Y-m-01', $fecha);
			echo "\n";
			$ultimos12meses[date('Y-m-01', $fecha)][] = $item;
		}
	}
	
	foreach ($ultimos12meses as $fecha => $mes)
	{
		$monto = 0;
		$informacion = '';

		foreach($mes as $dia)
		{
			$monto += $dia->monto;
			$informacion += trim($item->informacion,' .') . '. ';
		}

		$new_data['ultimos12meses'][] = array(
			"fecha" => $fecha,
			"monto" => $monto / count($mes),
			"informacion" => trim($informacion)
		);
	}


	$f = fopen($cacheFile, 'w');
	fwrite ( $f , json_encode($new_data) );
	fclose($f);
	
}
