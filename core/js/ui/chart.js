/**
 * FanPress CM UI Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui_chart = {
    
    draw: function(_chartConf) {
        
        if (Chart === undefined) {
            console.warn('Chart.js is not loaded!');
            return false;
        }

        for (var i = 0; i < _chartConf.data.datasets.length; i++) {
            _chartConf.data.datasets[i].borderWidth = (_chartConf.type === 'line' ? 2 : 0);            
        }

        let isBarOrLine = (_chartConf.type === 'line' || _chartConf.type === 'bar');

        if (_chartConf.options.legend === undefined) {

            _chartConf.options.legend = {
                display: (isBarOrLine ? false : true),
                position: 'right',
                labels: {
                    boxWidth: 25,
                    fontSize: 12
                }
            };

        }

        if (isBarOrLine && !_chartConf.options.scales) {

            _chartConf.options.scales = {
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

        return new Chart(fpcm.dom.fromId(_chartConf.id), _chartConf);  
    }

};