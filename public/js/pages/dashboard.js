//[Dashboard Javascript]

//Project:	Unique Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
	
//ticker
 	if ($('#webticker-1').length) {   
		$("#webticker-1").webTicker({
			height:'auto', 
			duplicate:true, 
			startEmpty:false, 
			rssfrequency:5
		});
	}
//data table
    $('#example1').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : false,
      'autoWidth'   : false
    });
	
//---1
function crypto_line_chart_btc_pot() {
    var config2a = {
        type: 'line',
        data: {
            labels: ["2016", "2016", "2016", "2017", "2017", "2018"],
            datasets: [{
                label: "Sell",
                fill: false,
                borderColor: '#e0bc00',
                backgroundColor: 'e0bc00',
                data: [0, 1.5, 2.5, 2, 3, 5]
			}, {
                label: "Buy",
                fill: false,
                borderColor: '#00c292',
                backgroundColor: '#00c292',
                data: [0, 0.75, 1, 1.75, 2.5, 3]
            }]
        }
    };
    var ctx2a = document.getElementById("chart-potential").getContext("2d");
    window.chart2a = new Chart(ctx2a, config2a);
}
if ($('#chart-potential').length) {
    crypto_line_chart_btc_pot();
} 
//--- 2
function crypto_line_chart_btc_eth() {
    var config2b = {
        type: 'line',
        data: {
            labels: ["2016", "2016", "2016", "2017", "2017", "2018"],
            datasets: [{
                label: "Sell ",
                fill: false,
                borderColor: '#e0bc00',
                backgroundColor: 'e0bc00',
                data: [0, 1.5, 2.5, 2, 3, 5]
            }, {
                label: "Buy",
                fill: false,
                borderColor: '#00c292',
                backgroundColor: '#00c292',
                data: [0, 0.75, 1, 1.75, 2.5, 3]
            }]
        }
    };
    var ctx2b = document.getElementById("chart-ethereum").getContext("2d");
    window.chart2b = new Chart(ctx2b, config2b);
}
if ($('#chart-ethereum').length) {
    crypto_line_chart_btc_eth();
}
//---32
function crypto_line_chart_btc_eth_neo() {
    var config2c = {
        type: 'line',
        data: {
            labels: ["2016", "2016", "2016", "2017", "2017", "2018"],
            datasets: [{
                label: "Sell ",
                fill: false,
                borderColor: '#e0bc00',
                backgroundColor: 'e0bc00',
                data: [0, 1.5, 2.5, 2, 3, 5]
            }, {
                label: "Buy ",
                fill: false,
                borderColor: '#00c292',
                backgroundColor: '#00c292',
                data: [0, 2, 0.75, 1.5, 2, 4]
            }]
        }
    };
    var ctx2c = document.getElementById("chart-neo").getContext("2d");
    window.chart2c = new Chart(ctx2c, config2c);
}
if ($('#chart-neo').length) {
    crypto_line_chart_btc_eth_neo();
}	
	
	
	
}); // End of use strict
