if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.extStats = {

    init: function () {
        fpcm.extStats.drawChart(fpcm.vars.jsvars.extStats.chartType);

        fpcm.ui.datepicker('#dateFrom', {
            changeMonth: true,
            changeYear: true,
            minDate: fpcm.vars.jsvars.extStats.minDate
        });
        fpcm.ui.datepicker('#dateTo', {
            changeMonth: true,
            changeYear: true
        });

        fpcm.ui.selectmenu('#chartType', {
            change: function () {
                jQuery('#fpcm-nkorg-extendedstats-chart').empty();
                fpcm.extStats.drawChart(jQuery(this).val());
            }

        });

        fpcm.ui.selectmenu('#chartMode', {
            classes: {
                'ui-selectmenu-button' : fpcm.vars.jsvars.extStats.showMode ? '' : 'fpcm-ui-hidden'
            }
        });

        fpcm.ui.selectmenu('#source', {
            change: function (event, ui) {
                if (ui.item.value === 'shares') {
                    jQuery('#chartMode-button').hide();
                }
                else {
                    jQuery('#chartMode-button').show();
                }
                
                fpcm.ui.controlgroup(fpcm.ui.mainToolbar, 'refresh');
            }
        });

    },

    drawChart: function (type) {

        if (window.chart) {
            window.chart.destroy();
        }

        if (!fpcm.vars.jsvars.extStats.chartValues.datasets) {
            return true;
        }

        fpcm.vars.jsvars.extStats.chartValues.datasets[0].borderWidth = (type === 'line' ? 5 : 0);

        var isBarOrLine = (type === 'line' || type === 'bar');

        var chartOptions = {
            legend: {
                display: (isBarOrLine ? false : true),
                position: 'bottom',
                labels: {
                    boxWidth: 25,
                    fontSize: 10
                }
            },
            responsive: true
        }

        if (isBarOrLine) {

            chartOptions.scales = {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                xAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
            };

        }

        window.chart = new Chart(jQuery('#fpcm-nkorg-extendedstats-chart'), {
            type: type,
            data: fpcm.vars.jsvars.extStats.chartValues,
            options: chartOptions
        });
    }

};