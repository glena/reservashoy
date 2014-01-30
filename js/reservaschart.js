var options = {
	selector: '.chart_div',
	width:960,
	height:500
};

var chart = (function (options) {

	'use strict';

	function extend (a, b) {
		for (var key in b) {
			if (b.hasOwnProperty(key)) {
				a[key] = b[key];
			}
		}
		return a;
	}


	function ReservasHoyChart(options) {
		this.options = extend(this.defaults, options);
		this._init();
	}

	ReservasHoyChart.prototype = {
		defaults: {
			selector: 'body',
			width:960,
			height:500,
			margin: {
				top: 20, 
				right: 20, 
				bottom: 100, 
				left: 70
			}
		},
	
		svg: null,

		x:null,
		y:null,

		xAxis:null,
		yAxis:null,

		data:null,

		_init: function () {

			var width 	= this.options.width - this.options.margin.left - this.options.margin.right,
                height 	= this.options.height - this.options.margin.top - this.options.margin.bottom;

            this.x = d3.time.scale().range([0,width]);
            this.y = d3.scale.linear().range([0,height]);

            this.xAxis = d3.svg.axis()
            				.scale(this.x)
                            .orient("bottom");

            this.yAxis = d3.svg.axis()
                            .scale(this.y)
                            .orient("left");

            this.svg = d3.select(this.options.selector).append("svg")
                            .attr("width", this.options.width)
                            .attr("height", this.options.height)
                            .append("g")
                            .attr("transform", "translate(" + [ this.options.margin.left , this.options.margin.top ] + ")");
              
            var yAxisEl = this.svg.append("g")
							.attr("class", "y axis")
							.call(this.yAxis)
							.selectAll("path")
	                          .attr("fill", "none")
	                          .attr("fill-opacity","0")
	                          .attr("stroke","#000000")
	                          .attr("stroke-width","1px")

			var scope = this;
			d3.json("data.json?r="+Math.random(), function(error, dataset) {
				scope.data = dataset;
				scope.normalize(scope.data.ultimos7dias);
				scope.normalize(scope.data.ultimos30dias);
				scope.normalize(scope.data.ultimos12meses);

				scope.mostrarUltimos7Dias();
			});
		},

		normalize: function(data) {
			var parseDate = d3.time.format("%Y-%m-%d").parse;
			data.forEach(function(d) {
	                d.fecha = parseDate(d.fecha);
	                d.monto = +d.monto;
	        });
		},

		mostrarUltimos7Dias: function() {
			this.loadData(this.data.ultimos7dias);	
		},
		mostrarUltimos30Dias: function() {
			this.loadData(this.data.ultimos30dias);
		},
		mostrarUltimos12Meses: function() {
			this.loadData(this.data.ultimos12meses);
		},

		loadData: function(data) {
			var scope = this;
			var barWidth = (this.options.width - this.options.margin.left - this.options.margin.right) * .9 / (data.length);
			var height 	= this.options.height - this.options.margin.top - this.options.margin.bottom;

			this.x.domain(d3.extent(data, function(d) { return d.fecha; }));
	        this.y.domain(d3.extent(data, function(d) { return d.monto; }));    

	        var rects = this.svg.selectAll('rect.value')
        			.data(data);

        	rects.enter()
        			.append('rect')
        			.attr('fill', 'red') //sacar fill
        			.attr("width", barWidth) // hacer dinamico en funcion del ancho
                    
					.attr("x", function(d) { return scope.x(d.fecha); })
				    .attr("y", function(d) { return height-scope.y(d.monto); })
				    .attr("height", function(d) { return scope.y(d.monto); })

                	.on('mouseover', function(d){
                		console.log(d);
                	});

                	
		}
		
	}

	return new ReservasHoyChart(options);

})(options)