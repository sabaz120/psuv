"use strict";

// Shared Colors Definition
const primary = '#6993FF';
const success = '#1BC5BD';
const info = '#8950FC';
const warning = '#FFA800';
const danger = '#F64E60';
var chart = null

// Class definition
function generateBubbleData(baseval, count, yrange) {
    var i = 0;
    var series = [];
    while (i < count) {
      var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;;
      var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
      var z = Math.floor(Math.random() * (75 - 15 + 1)) + 15;
  
      series.push([x, y, z]);
      baseval += 86400000;
      i++;
    }
    return series;
  }

function generateData(count, yrange) {
    var i = 0;
    var series = [];
    while (i < count) {
        var x = 'w' + (i + 1).toString();
        var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

        series.push({
            x: x,
            y: y
        });
        i++;
    }
    return series;
}

var KTApexChartsDemo = function () {
	// Private functions

	var _demo12 = function (meta, carga, newChart, idSelector) {
		const apexChart = idSelector;
		console.log(meta, carga, newChart, idSelector)
		var options = {
			series: [meta, carga],
			chart: {
				width: 380,
				type: 'pie',
			},
			labels: ['Participación', 'Movilización'],
			responsive: [{
				breakpoint: 480,
				options: {
					chart: {
						width: 200
					},
					legend: {
						position: 'bottom'
					}
				}
			}],
			colors: [primary,danger]
		};
		
		if(newChart == true){
			chart = new ApexCharts(document.querySelector(apexChart), options);
			chart.render();
		}else{

			chart.updateOptions({
				series: [meta, carga],
				chart: {
					width: 380,
					type: 'pie',
				},
				labels: ['Participación', 'Movilización'],
				responsive: [{
					breakpoint: 480,
					options: {
						chart: {
							width: 200
						},
						legend: {
							position: 'bottom'
						}
					}
				}],
				colors: [primary,danger]
			})

		}
		
	}

	return {
		// public functions
		init: function (meta, carga, newChart = true, idSelector) {
			_demo12(meta, carga, newChart, idSelector);
		}
	};
}();

var apexChartCandidatos = function () {
	// Private functions

	var _demo12 = function (series, labels, newChart, idSelector) {
		const apexChart = idSelector;
		var options = {
			series: series,
			chart: {
				width: 380,
				type: 'pie',
			},
			labels: labels,
			responsive: [{
				breakpoint: 480,
				options: {
					chart: {
						width: 200
					},
					legend: {
						position: 'bottom'
					}
				}
			}],
			// colors: [primary,danger]
		};
		
		if(newChart == true){
			chart = new ApexCharts(document.querySelector(apexChart), options);
			chart.render();
		}else{

			chart.updateOptions({
				series: series,
				chart: {
					width: 380,
					type: 'pie',
				},
				labels: labels,
				responsive: [{
					breakpoint: 480,
					options: {
						chart: {
							width: 200
						},
						legend: {
							position: 'bottom'
						}
					}
				}],
				// colors: [primary,danger]
			})

		}
		
	}

	return {
		// public functions
		init: function (meta, carga, newChart = true, idSelector) {
			_demo12(meta, carga, newChart, idSelector);
		}
	};
}();
