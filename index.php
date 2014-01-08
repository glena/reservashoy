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
	
		<title>Visualización del valor de las reservas del BCRA - reservashoy.com.ar</title>

		<script src="http://d3js.org/d3.v3.min.js"></script>
		<script src="/js/d3.tip.js"></script>
		
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

			.axis path,
			.axis line {
				fill: none;
				stroke: #000;
				shape-rendering: crispEdges;
			}

			.x.axis path {
				display: none;
			}

			.line {
				fill: none;
				stroke: steelblue;
				stroke-width: 1.5px;
			}
			circle:hover {
				fill:red;
			}

			.d3-tip {
				line-height: 1;
				padding: 12px;
				background: rgba(0, 0, 0, 0.8);
				color: #fff;
				border-radius: 2px;
			}

			.d3-tip .point-data {
				font-weight: bold;
			}
			.d3-tip .extra-info {
				padding-top: 5px;
			}
			.d3-tip .event{font-size:12px;color:red;padding-top:5px}
			.centered{text-align:center}

		</style>

	</head>
	<body>
		<div class="header">
			<h1>Las reservas del BCRA d&iacute;a a d&iacute;a - <a href="http://datosdemocraticos.com.ar?l=1">Datos Democr&aacute;ticos</a></h1>
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
			
			<div style="margin-left:20px;" class="fb-like" data-href="http://reservashoy.com.ar/" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false"></div>
		
			<a href="https://twitter.com/share" class="twitter-share-button" data-via="datosdemoc" data-lang="es" data-hashtags="BCRA">Twittear</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			
			<a href="https://twitter.com/datosdemoc" class="twitter-follow-button" data-show-count="false" data-lang="es">Seguir a @datosdemoc</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

			<a href="http://datosdemocraticos.com.ar/contacto?l=1" style="float:right">Contacto</a>
		
		</div>

		<div class="chart_div" id="chart_div"></div>
		<div class="actions centered"><small>Es posible hacer zoom utilizando la rueda del mouse sobre el gr&aacute;fico para una mejor visualizaci&oacute;n.</small></div>
		<div class="actions">
			<input type="checkbox" id="udpateYAxis" onclick="udpateYAxis(this.checked);"/>
			<label for="udpateYAxis">Mostrar grafico completo</label>
			<a href="#" class="obtenerGrafico" onclick="showImage();ga('send', 'event', 'grafico', 'obtener_imagen');">Descargar gr&aacute;fico</a>
		</div>

		<div class="total-hoy">
			<span>Al d&iacute;a <span class="actualmente_fecha"></span> hay<br/><strong>u$s <span class="actualmente_monto"></span> millones</strong></span>
		</div>

		<div class="footer">
			<p>Datos democr&aacute;ticos 2013 - <a href="http://datosdemocraticos.com.ar?l=2">datosdemocraticos.com.ar</a> - Usando el Dataset <a href="http://datosdemocraticos.com.ar/api/v1/reservas_internacionales_bcra">Reservas Internacionales del B.C.R.A.</a> - <a href="http://datosdemocraticos.com.ar/contacto?l=1">Contacto</a></p>
		</div>
		
		<a href="https://github.com/glena/reservashoy" onclick="ga('sendsend', 'event', 'github', 'click');"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png" alt="Fork me on GitHub"></a>

		<script> 
		
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-45371545-1', 'reservashoy.com.ar');
			ga('send', 'pageview');
			
			d3.selection.prototype.moveToFront = function() {
				return this.each(function(){
					this.parentNode.appendChild(this);
				});
			};

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
				"29/11/2013":"Ventas por 30 millones de d&oacute;lares en el mercado de cambios, y los pagos de deuda (Global 2017 y Bonar 18) que se realizan con los billetes del organismo."
			};

			var totalYAxis = false;
			
			var margin = {top: 20, right: 20, bottom: 100, left: 70},
				width = 960 - margin.left - margin.right,
				height = 500 - margin.top - margin.bottom;


			var format = d3.time.format("%d/%m/%Y");
			var parseDate = format.parse;

			var x = d3.time.scale().range([0, width]);
			var y = d3.scale.linear().range([height, 0]);

			var xAxis = d3.svg.axis()
					.scale(x)
					.orient("bottom")
					.ticks(30)
					.tickFormat(d3.time.format("%d/%m/%Y"));

			var yAxis = d3.svg.axis()
					.scale(y)
					.orient("left");

			var line = d3.svg.line()
					.x(function(d) { return x(d.fecha); })
					.y(function(d) { return y(d.monto); });

			
			var tip = d3.tip()
					.attr('class', 'd3-tip')
					.html(function(d) { 
						html = '';

						html += '<div class="point-data">'+ format(d.fecha) +': u$s '+ d.monto.toLocaleString() +'</div>';

						if (hitos[format(d.fecha)] != undefined)
						{
							html+='<div class="event">Evento:</div>';
							html += '<div class="extra-info">'+ hitos[format(d.fecha)] +'</div>';
						}

						return html; 
					})
					.offset([0, 3]);

			var svg = d3.select(".chart_div").append("svg")
					.attr("width", width + margin.left + margin.right)
					.attr("height", height + margin.top + margin.bottom)
					.append("g")
					.attr("transform", "translate(" + margin.left + "," + margin.top + ")")
					.call(tip);

			var rect = svg.append("svg:rect")
					.attr("class", "pane")
					.attr("width", width)
					.attr("height", height)
					.attr("fill","white");
					
			svg.append("text")
					  .attr("x", width/2)
					  .attr("y", height/2)
					  .attr("font-size","20px")
					  .attr("fill","#BBBBBB")
					  .style("text-anchor", "middle")
					  .text("reservashoy.com.ar - datosdemocraticos.com.ar");
			
			var xAxilsEl = svg.append("g")
			  .attr("class", "x axis")
			  .attr("transform", "translate(0," + height + ")")
			  .call(xAxis);
			  
			xAxilsEl.selectAll("path")
			  .attr("fill", "none")
			  .attr("fill-opacity","1")
			  .attr("stroke","#000000")
			  .attr("stroke-width","1px");
			  
			var yAxisEl = svg.append("g")
				.attr("class", "y axis")
				.call(yAxis);

			yAxisEl.selectAll("path")
				.attr("fill", "none")
				.attr("fill-opacity","1")
				.attr("stroke","#000000")
				.attr("stroke-width","1px");
				
			yAxisEl.append("text")
					.attr("transform", "rotate(-90)")
					.attr("y", 6)
					.attr("dy", ".71em")
					.style("text-anchor", "end")
					.text("Valor (millones u$S)");
			var data;		
			d3.tsv("data.tsv?r="+Math.random(), function(error, dataset) {
				data = dataset;
				data.forEach(function(d) {
					d.fecha = parseDate(d.fecha);
					d.monto = +d.monto;
				});
				
				x.domain(d3.extent(data, function(d) { return d.fecha; }));
				updateDomain();

				var path = svg.append("path")
						.datum(data)
						.attr("fill", "none")
						.attr("fill-opacity","1")
						.attr("stroke","#4682b4")
						.attr("stroke-width","1.5px")
						.attr("class", "line")
						.attr("d", line);
						
				var circles = svg.selectAll("circle").data(data);
  				circles.enter()
						.append("circle")
						.attr("class", function (d) { 
							var classname = '';
							if (hitos[format(d.fecha)] != undefined)
							{
								classname = 'with-data';
							}
							return classname; 
						})
						.attr("fill", function (d) { 
							var classname = '#0000FF';
							if (hitos[format(d.fecha)] != undefined)
							{
								classname = 'orange';
							}
							return classname; 
						})
						.attr("r", function (d) { 
							var size = 3;
							if (hitos[format(d.fecha)] != undefined)
							{
								size = 5;
							}
							return size; 
						})
        				.on('mouseover', function(d){
							ga('send', 'event', 'circle', 'show', format(d.fecha));
							tip.show(d);
						})
      					.on('mouseout', tip.hide);

				d3.selectAll("circle.with-data").moveToFront();
						 
				rect.call(d3.behavior.zoom().x(x).y(y).scaleExtent([1,15]).on("zoom", draw));	
				draw();
				
				var lastItem = data[data.length-1];
				var ultima_fecha = format(lastItem.fecha);
				var ultimo_monto = lastItem.monto.toLocaleString();

				d3.select(".actualmente_fecha").html(ultima_fecha);
				d3.select(".actualmente_monto").html(ultimo_monto);

				document.title = 'Hay u$s '+ ultimo_monto +' millones al '+ ultima_fecha +' en las reservas del BCRA.'
			});
			
			function updateDomain()
			{
				if (!totalYAxis)
				{
					y.domain(d3.extent(data, function(d) { return d.monto; }));	
				}
				else
				{
					y.domain([0,d3.max(data, function(d) { return d.monto; })]);
				}
			}
			
			function udpateYAxis(value){
				totalYAxis = value;
				updateDomain();
				rect.call(d3.behavior.zoom().x(x).y(y).on("zoom", draw));
				draw();
			}
			
			function draw() {
				svg.select("g.x.axis").call(xAxis);
				svg.select("g.y.axis").call(yAxis);
				svg.select("path.line").attr("d", line);
				svg.selectAll("circle").attr("cx", function(d) { return x(d.fecha); }).attr("cy", function(d) { return y(d.monto); });

				xAxilsEl.selectAll("text")
					.style("text-anchor", "end")
					.attr("transform", "rotate(-60)");
			}
			
			function showImage(){
				grChartImg.ShowImage('chart_div', true);
			}

		</script>
	</body>
</html>