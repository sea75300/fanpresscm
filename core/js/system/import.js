/**
 * FanPress CM CSV import namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.import = {

    init: function () {      

        fpcm.dom.fromId('btnImportStart').click(function (event, ui)
        {
            fpcm.worker.postMessage({
                namespace: 'import',
                function: '_exec',
                id: 'import.exec',
                param: {
                    file: fpcm.dom.fromId('import_filename').val(),
                    item: fpcm.dom.fromId('import_destination').val(),
                    delim: fpcm.dom.fromId('import_delimiter').val(),
                    enclo: fpcm.dom.fromId('import_enclosure').val(),
                    current: 1,
                    next: 1
                }
            });              
            
        });
        

    },
    
    _exec: function (_params) {

        if (!_params.file) {
            fpcm.ui.addMessage({
               type: 'error',
               txt: 'BItte lade eine gültige CSV-Datei hoch!'
            });
            return false;
        }
        
        console.log(_params);
        
        return false;
        
        fpcm.ajax.post('system/import', {
            data: _params,
            cache: false,
            dataType: 'json',
            execDone: function (result) {

//                fpcm.dom.fromClass('fpcm-ui-progressbar-label').empty();
//
//                fpcm.ui.progressbar('.fpcm-ui-progressbar', {
//                    max: result.data.fs,
//                    value: result.current ? result.current : result.data.fs
//                });
//
//                if (result.data.lines && result.data.lines.length) {
//                    fpcm.dom.fromId('list').append('<li>' + result.data.lines.join('</li><li>') + '</li>');
//                }
//                
//                if (!result.next) {
//                    return false;
//                }
//                
//                fpcm.testing.exec({
//                    current: result.current,
//                    next: result.next
//                });
            }
        });
        
    }

};

fpcm.filemanager = {

    runFileIndexUpdate: function (_params) {
        
        if (!_params.files || !_params.files[0] || !_params.files[0].name) {
            return false;
        }

        fpcm.dom.fromId('import_filename').val(_params.files[0].name);

        fpcm.ui.progressbar('.fpcm-ui-progressbar', {
            max: parseInt(_params.files[0].size),
            value: 0
        });

        fpcm.dom.fromClass('fpcm-ui-progressbar').parent().removeClass('ui-hidden');
        fpcm.dom.fromId('fpcm-ui-csv-upload').addClass('fpcm ui-hidden');
        return false;
    },
    
    getAcceptTypes: function ()
    {
        return /(\.|\/)(csv)$/i;
    }

};