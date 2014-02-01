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
//     Range function from:
//     Underscore.js 1.5.2
//     http://underscorejs.org
//     (c) 2009-2014 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
//     Underscore may be freely distributed under the MIT license.
        range : function(start, stop, step) {
            if (arguments.length <= 1) {
                stop = start || 0;
                start = 0;
            }
            step = arguments[2] || 1;
            
            var length = Math.max(Math.ceil((stop - start) / step), 0);
            var idx = 0;
            var range = new Array(length);
          
            while(idx < length) {
                range[idx++] = start;
                start += step;
            }
          
            return range;
        },
	
		svg: null,

		x:null,
		y:null,

		parseDate: null,

		yAxis:null,

		data:null,
      
		innerWidth:null,
		innerHeight:null,

		_init: function () {

			this.parseDate = d3.time.format("%Y-%m-%d").parse;

			this.innerWidth  = this.options.width - this.options.margin.left - this.options.margin.right,
            this.innerHeight = this.options.height - this.options.margin.top - this.options.margin.bottom;
             
            this.x = d3.scale.ordinal().rangeBands([this.innerWidth * 0.05,this.innerWidth * 0.95]);
          
			this.y = d3.scale.linear().range(
					[this.innerHeight,0]
                );
          


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
							.call(this.yAxis);

			yAxisEl.selectAll("path")
                          .attr("fill", "none")
                          .attr("fill-opacity","1")
                          .attr("stroke","#000000")
                          .attr("stroke-width","1px");

			var scope = this;
			d3.json("data.json?r="+Math.random(), function(error, dataset) {
				scope.data = dataset;
				scope.normalize.call(scope, scope.data.ultimos7dias, scope.parseDate);
				scope.normalize.call(scope, scope.data.ultimos30dias, scope.parseDate);
				scope.normalize.call(scope, scope.data.ultimos12meses, scope.parseDate);

				scope.mostrarUltimos7Dias.call(scope);
			});
			
		},

		normalize: function(data, parseDate) {
			data.forEach(function(d) {
				d.fecha = parseDate(d.fecha);
				d.monto = +d.monto;
	        });
		},

		currentData: [],

		setCurrentData: function(data) {
			this.currentData = [];
			var newData = [];
			data.forEach(function(d){
				newData.push(d);
			});
			this.currentData = newData;
		},

		mostrarUltimos7Dias: function() {
			this.setCurrentData(this.data.ultimos7dias);
          	this.loadData();	
		},
		mostrarUltimos30Dias: function() {
			this.setCurrentData(this.data.ultimos30dias);
			this.loadData();
		},
		mostrarUltimos12Meses: function() {
			this.setCurrentData(this.data.ultimos12meses);
          	this.loadData();
		},

		loadData: function() {

			var data = this.currentData;

			var scope = this;
			var height 	= this.innerHeight;
			var barWidth = this.innerWidth * 0.8 / data.length;
			            
            this.x.domain(this.range(0,data.length,1));

	        this.y.domain(
              [
                d3.min(data,function(d) { return d.monto * 0.9; })
              , 
               	d3.max(data,function(d) { return d.monto * 1.05; })
              ]
            );
                    
          	this.svg.select("g.y.axis").call(this.yAxis);
          	this.svg.selectAll("g.y.axis text").attr("font-size","15px");

          	var rectsBg = this.svg.selectAll('rect.bg').data(data);
			var rects = this.svg.selectAll('rect.value').data(data);

			rectsBg.enter()
					.append('rect')
            		.classed('bg',true)
					.attr('fill', '#E8E8E8') //sacar fill
					.attr("width", barWidth) // hacer dinamico en funcion del ancho
					.attr("x", function(d, i) { return scope.x(i); })
				    .attr("y", 0)
				    .attr("height", height);    
          

			rects.enter()
					.append('rect')
            		.classed('value',true)
					.attr('fill', '#2F2D2E') //sacar fill
					.attr("width", barWidth) // hacer dinamico en funcion del ancho
					.attr("x", function(d, i) { return scope.x(i); })
				    .attr("y", function(d) { return scope.y(d.monto); })
				    .attr("height", function(d) { return height-scope.y(d.monto); })
					.on('mouseover', function(d){
						//console.log(d);
                	});      
          
			rectsBg.exit().remove();
			rects.exit().remove();
                	
		}
		
	}

	return new ReservasHoyChart(options);

})(options)