"use strict";

// Shared Colors Definition
const cl_green = '#25d950';
const cl_blue = '#1900f1';
const cl_red = '#ef000c';
const cl_yellow = '#f2d53a';


var KTApexCharts = function () {

	// COUNT
	var _chart_count = function () {
		const apexChart = "#chart_count";
		var options = {
			series: [{
						name: "Общие заказы",
						data: chart_count_total
					},{
						name: "Завершенные",
						data: chart_count_done
					},{
						name: "Отклоненные",
						data: chart_count_cancel
					},{
						name: "В ожидании",
						data: chart_count_wait
					}],
			chart: {
				height: 350,
				type: 'line',
				zoom: {
					enabled: false
				}
			},
			dataLabels: { 	
				enabled: false
			},
			stroke: {
				curve: 'smooth'
			},
			grid: {
				row: {
					colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
					opacity: 0.5
				},
			},
			xaxis: {
				categories: chart_months,
			},
			colors: [
					cl_blue,
					cl_green,
					cl_red,
					cl_yellow
					]
		};

		var chart = new ApexCharts(document.querySelector(apexChart), options);
		chart.render();
	}

	// SUM
	var _chart_sum = function () {
		const apexChart = "#chart_sum";
		var options = {
			series: [{
						name: "Общие заказы",
						data: chart_sum_total
					},{
						name: "Завершенные",
						data: chart_sum_done
					},{
						name: "Отклоненные",
						data: chart_sum_cancel
					},{
						name: "В ожидании",
						data: chart_sum_wait
					}],
			chart: {
				height: 350,
				type: 'line',
				zoom: {
					enabled: false
				}
			},
			dataLabels: { 	
				enabled: false
			},
			stroke: {
				curve: 'smooth'
			},
			grid: {
				row: {
					colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
					opacity: 0.5
				},
			},
			xaxis: {
				categories: chart_months,
			},
			yaxis: {
			  labels: {
			    formatter: function (value) {
			      return value.toFixed(2) + "$";
			    }
			  },
			},
			colors: [
					cl_blue,
					cl_green,
					cl_red,
					cl_yellow
					]
		};

		var chart = new ApexCharts(document.querySelector(apexChart), options);
		chart.render();
	}


	return {
		// public functions
		init: function () {
			_chart_count();
			_chart_sum();
		}
	};
}();

jQuery(document).ready(function () {
	KTApexCharts.init();
});