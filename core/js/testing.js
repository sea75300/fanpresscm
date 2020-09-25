/**
 * FanPress CM texts namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.testing = {

    init: function() {
        
        fpcm.ui.progressbar('#progress', {
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
        
        fpcm.ajax.post('testing', {
            data: {
                current: _params.current,
                next: _params.next
            },
            cache: false,
            dataType: 'json',
            quiet: true,
            execDone: function (result) {

                fpcm.ui.progressbar('#progress', {
                    max: result.data.fs,
                    value: result.current
                });
                
                
                if (result.data.lines) {
                    fpcm.dom.fromId('list').append('<li>' + result.data.lines.join('</li><li>') + '</li>');
                }
                
                if (result.next < 1) {
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