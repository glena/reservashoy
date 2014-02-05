<!DOCTYPE html>
<html>
	<head>
		<script src="/js/d3.v3.min.js"></script>
		<link href="/css/main.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div class="header">
			<div class="content">
				<div class="left-title">
					<h1>
						Reservas<br/><b>En Vivo</b>
					</h1>
				</div>
				<div class="right-title">
					<span class="simbolo">u$s</span>
					<span id="monto"></span>
					<span class="texto">millones</span>
				</div>
			</div>

			
		</div>
		<div class="menu-wrapper">
			<ul class="menu">
				<li class="title">&Uacute;ltimos</li>
				<li class="item selected" onclick="seleccionFiltro(this,chart.mostrarUltimos7Dias);">7 d&iacute;as</li>
				<li class="item" onclick="seleccionFiltro(this,chart.mostrarUltimos30Dias);">30 d&iacute;as</li>
				<li class="item" onclick="seleccionFiltro(this,chart.mostrarUltimos12Meses);">12 meses</li>
			</ul>
		</div>
		<div class="chart_div"></div>
		<div class="autor">
			<span><a class="datosdemoc" href="http://datosdemocraticos.com.ar">Datos Democr&aacute;ticos</a> - <a class="glena" href="http://germanlena.com.ar">Germ&aacute;n Lena</a></span>
			<span class="right"><a class="datos" href="http://datosdemocraticos.com.ar/api/v1/reservas_internacionales_bcra">Reservas Internacionales del B.C.R.A.</a></span>
		</div>

		<script type="text/javascript" src="/js/reservaschart.js"></script>  
	</body>
</html>
