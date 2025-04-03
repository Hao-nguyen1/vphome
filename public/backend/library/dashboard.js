(function($) {
	"use strict";
	var HT = {}; 


    HT.createChart = (label, data) => {

        let canvas = document.getElementById('barChart')
        let ctx = canvas.getContext('2d')

        if(window.myBarChart){
            window.myBarChart.destroy();
        }

        let chartData = {
            labels: label,
            datasets: [
                {
                    label: "Doanh thu",
                    backgroundColor: 'rgba(26,179,148,0.5)',
                    borderColor: "rgba(26,179,148,0.7)",
                    pointBackgroundColor: "rgba(26,179,148,1)",
                    pointBorderColor: "#fff",
                    data: data
                }
            ]
        }

        let chartOption = {
            tooltips: {
              callbacks: {
                    label: function(tooltipItem, data) {
                        var value = tooltipItem.yLabel;
                        value = value.toString();
                        value = value.split(/(?=(?:...)*$)/);
                        value = value.join('.');
                        return value;
                    }
              } 
            }, 
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        userCallback: function(value, index, values) {
                            // Convert the number to a string and splite the string every 3 charaters from the end
                            value = value.toString();
                            value = value.split(/(?=(?:...)*$)/);
                            value = value.join('.');
                            return value;
                        }
                    }
                }],
                xAxes: [{
                    ticks: {
                    }
                }]
            }
        }

        window.myBarChart = new Chart(ctx, {type: 'bar', data: chartData, options:chartOption});

    }

    HT.changeChart = () => {
        $(document).on('click', '.chartButton', function(e){
            e.preventDefault()
            let button = $(this)
            let chartType = button.attr('data-chart')
            $('.chartButton').removeClass('active')
            button.addClass('active')
            HT.callChart(chartType)
        })
    }

    HT.callChart = (chartType) => {
        $.ajax({
            type        : 'GET',
            url         :  'ajax/order/chart',
            data		: {
                chartType : chartType
            },
            dataType    : 'json',
            success: function(response){

                HT.createChart(response.label, response.data)
            }
        });
    }
   
   
	$(document).ready(function(){
        
        HT.createChart(label, data)

        HT.changeChart();


	});

    

})(jQuery);
