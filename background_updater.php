<?php
echo "Inicio \n";
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
	return "http://datosdemocraticos.com.ar/api/v1/reservas_internacionales_bcra$page.json?apikey=$apiKey&reverse=1";

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
		$fecha = strtotime($item->fecha);
		$item->fecha = date('Y-m-d', $fecha);

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
			if (trim($dia->informacion) != '')
			{
				$informacion .= trim($dia->informacion,' .') . '. ';
			}
		}

		$o = new stdClass();
		$o->fecha = $fecha;
		$o->monto = ceil($monto / count($mes));
		$o->informacion = trim($informacion);

		$new_data['ultimos12meses'][] = $o;
	}

	function sortDesc ($a, $b) {
		$a = strtotime($a->fecha);
		$b = strtotime($b->fecha);
		
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	}

	uasort ( $new_data['ultimos7dias'] , 'sortDesc');
	uasort ( $new_data['ultimos30dias'] , 'sortDesc');
	uasort ( $new_data['ultimos12meses'] , 'sortDesc');

	$new_data['ultimos7dias'] = array_values($new_data['ultimos7dias']);
	$new_data['ultimos30dias'] = array_values($new_data['ultimos30dias']);
	$new_data['ultimos12meses'] = array_values($new_data['ultimos12meses']);

	$f = fopen($cacheFile, 'w');
	fwrite ( $f , json_encode($new_data) );
	fclose($f);
	
}

echo "Fin \n";