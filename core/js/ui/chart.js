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

        if (!_chartConf.data || !_chartConf.data || !_chartConf.data.datasets) {
            return false;
        }

        _chartConf.options.elements = {
            line: {
                borderWidth: 5
            },
            bar: {
                borderWidth: 0
            },
            arc: {
                borderWidth: 0
            }
        }


        return new Chart(fpcm.dom.fromId(_chartConf.id), _chartConf);
    }

};