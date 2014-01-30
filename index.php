<?

	if ($_SERVER["HTTP_HOST"] != 'reservashoy.com.ar')
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: http://reservashoy.com.ar/");
		exit();
	}
	
	header('Content-type: text/html; charset=utf-8'); 

?>


<!DOCTYPE html>
<html>
	<head>
	
		<title>Visualizacion del valor de las reservas del BCRA - reservashoy.com.ar</title>

		<script src="http://d3js.org/d3.v3.min.js"></script>
		
		<!--[if lt IE 9]><script type="text/javascript" src="/js/flashcanvas.js"></script><![endif]-->
		<script type="text/javascript" src="/js/canvg.js"></script> 
		<script type="text/javascript" src="/js/rgbcolor.js"></script>
		<script type="text/javascript" src="/js/grChartImg.js"></script>  

		<meta content="Monitoreamos las reservas del banco d&aacute;a a d&aacute;a. Fuente: datosdemocraticos.com.ar" name="description" />
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>

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
				margin-bottom:10px;
			}
			div.total-hoy {
				text-align:center;
				font-size:20px;
			}
			div.actions {
				width:80%;
				margin:auto;
			}
			div.actions .obtenerGrafico{
				float:right;
			}
			.share{
				width: 600px; 
				margin: 15px auto;
			}
			.chart_div{
				text-align: center;
				margin: 10px 0;
			}
			.logo {
				border-radius: 50%;
			}
			.header {
				background-color: rgb(119, 119, 119);
				text-align:center;
				padding:10px;
			}
			.header a,
			.header a:hover,.header a:visited {
				color: #ffffff;
			}
			a,
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
			<h1>Las reservas del BCRA d&iacute;a a d&iacute;a - <a href="http://datosdemocraticos.com.ar?l=1">Datos Democr&aacute;ticos</a></h1>
			<img width="160" height="160" src="datos-democraticos-160.png" alt="logo" class="logo">
		</div>


		<div class="chart_div" id="chart_div"></div>
		<div class="actions centered"><small>Es posible hacer zoom utilizando la rueda del mouse sobre el gr&aacute;fico para una mejor visualizaci&oacute;n.</small></div>
		<div class="actions">
			<a href="#" class="obtenerGrafico" onclick="showImage();ga('send', 'event', 'grafico', 'obtener_imagen');">Descargar gr&aacute;fico</a>
		</div>

		<div class="total-hoy">
			<span>Al d&iacute;a <span class="actualmente_fecha"></span> hay<br/><strong>u$s <span class="actualmente_monto"></span> millones</strong></span>
		</div>

		<div class="footer">
			<p>Datos democr&aacute;ticos 2013 - Copyright 2014 Germán Lena - <a href="http://datosdemocraticos.com.ar?l=2">datosdemocraticos.com.ar</a> - Usando el Dataset <a href="http://datosdemocraticos.com.ar/api/v1/reservas_internacionales_bcra">Reservas Internacionales del B.C.R.A.</a> - <a href="http://datosdemocraticos.com.ar/contacto?l=1">Contacto</a></p>
		</div>
		
		<a href="https://github.com/glena/reservashoy" onclick="ga('sendsend', 'event', 'github', 'click');"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png" alt="Fork me on GitHub"></a>

		<script> 
			
			var hitos = {
				"05/11/2012":"Comienzo de retenciones al 15%",
				"18/03/2013":"Aumento de retenciones al 20%",
				"27/06/2013":"Devaluacion del oro",
				"02/07/2013":"Inicio Cedin y BAADE",
				"13/08/2013":"Elecciones Primarias",
				"11/09/2013":"Vencimiento Bonar VII",
				"30/09/2013":"Fin del blanqueamiento de capitales",
				"28/10/2013":"Elecciones Legislativas",
				"03/12/2013":"Aumento de las retenciones por compras en el exterior al 35%",
				"12/11/2013":"Venta de dolares para el mercado interno, improtaci&oacute;n de combustibles y pago de deuda.",
				"29/11/2013":"Ventas por 30 millones de d&oacute;lares en el mercado de cambios, y los pagos de deuda (Global 2017 y Bonar 18) que se realizan con los billetes del organismo.",
				"30/12/2013":"BCRA informó que tuvo una \"participación compradora\" y debió cancelar una serie de compromisos externos en moneda extranjera por un total de u$s901 millones.",
				"08/01/2014":"Saldo vendedor de la autoridad monetaria por u$s60 millones en el mercado mayorista, se produjeron pagos de deuda y una compensación cuatrimestral a la ALADI.",
				"23/01/2014":"Venta de 100 millones de dolares para regular la cotización del dolar (pico máximo durante el día 8,40, cierre 7,75)",
				"24/01/2014":"Liberación del cepo al dolar para ahorro y descenso de las retenciones por compras en el exterior al 20%). Descenso de u$s 200 millones para regular la cotizaci&oacute;n del dolar oficial."
			};

			
			
			function showImage(){
				grChartImg.ShowImage('chart_div', true);
			}

		</script>
		<script type="text/javascript" src="/js/reservaschart.js"></script>  
	</body>
</html>
