/**
 * FanPress CM texts namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.testing = {

    init: function() {
        
        fpcm.ui.progressbar('.fpcm-ui-progressbar', {
            max: 1,
            value: 0
        });       

        fpcm.worker.postMessage({
            namespace: 'testing',
            function: 'exec',
            id: 'testing.exec'
        });


    },
    
    exec: function (_params) {

        if (!_params) {
            _params = {};
        }
        
        if (!_params.current) {
            _params.current = 1;
        }
        
        if (!_params.next) {
            _params.next = 1;
        }
        
        fpcm.dom.fromClass('fpcm-ui-progressbar-label').text('Loading data...');
        
        fpcm.ajax.post('testing', {
            data: {
                current: _params.current,
                next: _params.next
            },
            cache: false,
            dataType: 'json',
            quiet: true,
            execDone: function (result) {

                fpcm.dom.fromClass('fpcm-ui-progressbar-label').empty();

                fpcm.ui.progressbar('.fpcm-ui-progressbar', {
                    max: result.data.fs,
                    value: result.current ? result.current : result.data.fs
                });

                if (result.data.lines && result.data.lines.length) {
                    fpcm.dom.fromId('list').append('<li>' + result.data.lines.join('</li><li>') + '</li>');
                }
                
                if (!result.next) {
                    return false;
                }
                
                fpcm.testing.exec({
                    current: result.current,
                    next: result.next
                });
            }
        });
        
    }

};