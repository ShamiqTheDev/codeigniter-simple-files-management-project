/*
 Highcharts JS v5.0.9 (2017-03-08)
 Custom library
 Author Altaf hussain
*/

function getBarGraph(container_id,data_response){
	chartVendor = Highcharts.chart(container_id, {
		
		chart: {
			type: 'column',
			events: {
				drilldown: function (e) {
					
					if (!e.seriesOptions) {
						if (e.point.y == 0 || e.point.name == "Unknown"){
							return false;
						}
						var chart = this;

						if (typeof e.point.url_name != "undefined") {
							url_name = e.point.url_name;
						}

						chart.showLoading('Loading...');
						$.ajax({
							url: base_url+"admin/ajax_dashboard/" + e.point.url_name,
							type: "post", 
							dataType: "json",
							data: {
									name: e.point.name, id: e.point.id , graph_type: e.point.graph_type, more_drilldown: e.point.more_drilldown
								},
							success: function(response) {
								console.log(response);
								chart.hideLoading();
								chart.addSingleSeriesAsDrilldown(e.point, response[0]);
								chart.addSingleSeriesAsDrilldown(e.point, response[1]);
								chart.applyDrilldown();
								url_name = (response[0].data[0].url_name);
								next_data = response[0];
							},
							error: function(response) {
								//Do Something to handle error
								console.log(response);
							}
						});
					}
				},
				drillup: function (e) {
					setTimeout(function(){
						//checkDrillUpBtn();
					}, 800);
				}
			}
		},
		title: {
			text: ''
		},
		yAxis: [{ // Primary yAxis
			labels: {
				format: '{value}',
				style: {
					
				}
			},
			title: {
				text: "Total Scanned",
				style: {
				}
			} 
		}],
		xAxis: {
			type: 'category',
			crosshair: true
		},		
		tooltip: {
			shared: true
		},
		legend: {
			enabled: false
		},
		series: data_response,
		credits: {
			enabled: false
		},
		drilldown: {
			series: []
		}
	});
}

function getPieGraph(container_id,data_response,drilldown){
	chartVendorPie = Highcharts.chart(container_id, {
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie',
			height: 350,
		},
		title: {
			text: ""
		},
		tooltip: {
		},
		plotOptions: {
			pie: {
				allowPointSelect: false,
				/*cursor: 'pointer',
				point: {
					events: {
						click: function () {
							location.href = base_url + 'admin/employees/index/superannuation/' +
									this.options.name + '/' + job_type;
						}
					}
				},*/
				dataLabels: {
					enabled: true,
					style: {
						color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
					}
				},
				 borderWidth:0,
				
			}
		},
		series: data_response,
			noData: {
				style: {
					fontWeight: 'bold',
					fontSize: '15px',
					color: '#303030'
				}
			},
			credits: {
			  enabled: false
			},
	});
	//chartVendor.showLoading('Loading...');
	if (!chartVendorPie.hasData()) {
		//chartVendorPie.hideNoData();
		chartVendorPie.showNoData("No data available");
	}
}