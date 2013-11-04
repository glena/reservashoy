<?

if ($_SERVER["HTTP_HOST"] != 'reservashoy.com.ar')
{
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: http://reservashoy.com.ar/");
	exit();
}

?>


<!DOCTYPE html>
<html>
  <head>
  
	<!--[if lt IE 9]><script type="text/javascript" src="flashcanvas.js"></script><![endif]-->
	<script type="text/javascript" src="canvg.js"></script> 
	<script type="text/javascript" src="rgbcolor.js"></script>
	<script type="text/javascript" src="grChartImg.js"></script>   
<?
$cacheFile = 'chart.data';
if (!file_exists($cacheFile) || filemtime ( $cacheFile ) + 86400 < time()) /*si hace mas de un dia*/
{
	echo "<!-- RELOAD -->";
	$apiKey = 'apikey';
	$url = "http://datosdemocraticos.com.ar/api/v1/reservas_internacionales_bcra.json?apikey=$apiKey";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	$raw = curl_exec($ch);

	curl_close($ch);

	$response = json_decode($raw);

	if ($response->estado == 'ok')
	{
		file_put_contents($cacheFile,$raw);
	}
}
else
{
	echo "<!-- CACHE -->";
	$raw = file_get_contents($cacheFile);
	$response = json_decode($raw);
}

$datos = $response->data;

uasort ( $datos , function ($a, $b) {
	list($dia, $mes, $anio) = explode('/',$a->FECHA);
	$a = strtotime("$anio-$mes-$dia");
	
	list($dia, $mes, $anio) = explode('/',$b->FECHA);
	$b = strtotime("$anio-$mes-$dia");
	
	if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
});

$chartData = '';
$lastMonto = null;
$lastFecha = null;
foreach ($datos as $dato)
{
	list($dia, $mes, $anio) = explode('/',$dato->FECHA);
	$fecha = strtotime("$anio-$mes-$dia");

	if (is_null($lastFecha) || $fecha > $lastFecha)
	{
		$lastFecha = $fecha;
		$lastMonto = $dato->MONTO;
	}
	$chartData .= ",['{$dato->FECHA}',  {$dato->MONTO}]";
}
?>
	<title>Actualmente hay u$s <?=number_format ( $lastMonto, 0, ',', '.' )?> millones en las reservas del BCRA.</title>

    <meta content="Monitoreamos las reservas del banco d&aacute;a a d&aacute;a. Fuente: datosdemocraticos.com.ar" name="description" />
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['A�o', 'Monto']
          <?= $chartData ?>
        ]);

        var options = {
          title: 'Reservas Internacionales del B.C.R.A. - reservashoy.com.ar'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
	
		<style>
		body {
			margin:0;
			font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
			font-size: 14px;
			line-height: 1.428571429;
			color: rgb(51, 51, 51);
			background-color: rgb(255, 255, 255);
			padding-bottom:100px;
		}
		h1 {
			margin:0;
			color: #ffffff;
			text-align: center;
			text-shadow: 0 1px 2px rgba(0,0,0,0.6);
			margin-botton:10px;
		}
		div.total-hoy {
			text-align:center;
			font-size:20px;
		}
		div.get-image {
			text-align:right;
			width:900px;
			margin:auto;
		}
		.share{
			width: 600px; 
			margin: 15px auto;
		}
		#chart_div{
			width: 900px; 
			height: 500px;
			margin:auto;
		}
		.logo {
			border-radius: 50%;
		}
		.header {
			background-color: rgb(119, 119, 119);
			text-align:center;
			padding:10px;
		}
		.header a {
			color: #ffffff;
		}
		.header a:hover,.header a:visited {
			color: #ffffff;
		}
		a {
			color: #000000;
		}
		a:hover,a:visited {
			color: #000000;
		}
		
		.footer {
			position:fixed;
			left:0;
			bottom:0;
			width:100%;
			border-top: 2px solid rgb(119, 119, 119);
			text-align:center;
			background-color:#FFFFFF;
		}
		</style>
		
  </head>
  <body>
	<div class="header">
		<h1>Las reservas del BCRA d&iacute;a a d&iacute;a - <a href="http://datosdemocraticos.com.ar">Datos Democr&aacute;ticos</a></h1>
		<img width="160" height="160" src="datos-democraticos-160.png" alt="logo" class="logo">
	</div>
	
	<div class="share">
			
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=381443141985598";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		
		<div class="fb-like" data-href="http://reservashoy.com.ar/" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false"></div>
	
		<a href="https://twitter.com/share" class="twitter-share-button" data-via="datosdemoc" data-lang="es" data-hashtags="BCRA">Twittear</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	
	</div>
	
    <div id="chart_div" ></div>
	
	<div class="get-image">
		<a href="#" onclick="showImage()">Obtener imagen</a>
	</div>
	<div class="total-hoy">
		<span>Actualmente hay<br/><strong>u$s <?=number_format ( $lastMonto, 0, ',', '.' )?> millones</strong></span>
	</div>
	
	<div class="footer">
		<p>Datos democr&aacute;ticos 2013 - <a href="http://datosdemocraticos.com.ar">datosdemocraticos.com.ar</a> - Usando el Dataset <a href="http://datosdemocraticos.com.ar/api/v1/reservas_internacionales_bcra">Reservas Internacionales del B.C.R.A.</a></p>
	</div>
	
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-45371545-1', 'reservashoy.com.ar');
	  ga('send', 'pageview');

	  function showImage()
	  {	
		grChartImg.ShowImage('chart_div', true);
	  }
	  
	</script>
  </body>
</html>