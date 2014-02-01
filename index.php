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

		<script type="text/javascript" src="/js/reservaschart.js"></script>  
	</body>
</html>
