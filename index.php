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

		<link href="/css/main.css" media="all" rel="stylesheet" type="text/css" />

	</head>
	<body>

		<div class="menu-wrapper">
			<ul class="menu">
				<li class="title">&Uacute;ltimos</li>
				<li class="item selected" onclick="seleccionFiltro(this,chart.mostrarUltimos7Dias);">7 d&iacute;as</li>
				<li class="item" onclick="seleccionFiltro(this,chart.mostrarUltimos30Dias);">30 d&iacute;as</li>
				<li class="item" onclick="seleccionFiltro(this,chart.mostrarUltimos12Meses);">12 meses</li>
			</ul>
		</div>
		<div class="chart_div"></div>

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

		</script>
		<script type="text/javascript" src="/js/reservaschart.js"></script>  
	</body>
</html>
