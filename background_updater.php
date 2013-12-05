<?

function get($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_VERBOSE, true);
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
}


$cacheFile = '/usr/share/nginx/www/reservashoy/data.tsv';

$apiKey = 'c237ff6028b3f772ddd00073bfa5c41a57c7032a';

$data = array();

$response = get(getUrl($apiKey));
$data = array_merge($data, $response->data);

$response = get(getUrl($apiKey, '/pagina/2'));
$data = array_merge($data, $response->data);


if ($response->estado == 'ok')
{

	uasort ( $data , function ($a, $b) {
		list($dia, $mes, $anio) = explode('/',$a->FECHA);
		$a = strtotime("$anio-$mes-$dia");
		
		list($dia, $mes, $anio) = explode('/',$b->FECHA);
		$b = strtotime("$anio-$mes-$dia");
		
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	});

	$f = fopen($cacheFile, 'w');

	fwrite ( $f , "fecha\tmonto\n" );
	foreach ($data as $line)
	{
		$fecha = trim($line->FECHA, " \r\n");
		$monto = trim($line->MONTO, " \r\n");
		fwrite ( $f , "{$fecha}\t{$monto}\n" );
	}
	
	fclose($f);
	
}
