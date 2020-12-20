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

        fpcm.ui.selectmenu('#import_destination', {
            removeCornerLeft: true,
            change: function (event, ui) {
                
                if (!fpcm.vars.jsvars.fields[ui.item.value.replace('\\', '_')]) {
                    fpcm.dom.fromId('fpcm-ui-csv-fields-select').empty();
                    fpcm.dom.fromId('fpcm-ui-csv-fields-list').empty();
                    return false;
                }
                
                let fields = fpcm.vars.jsvars.fields[ui.item.value.replace('\\', '_')];
                
                for (var item in fields) {
                    fpcm.dom.appendHtml(
                        '#fpcm-ui-csv-fields-select',
                        '<li class="mb-1 mx-0 p-2 fpcm-ui-background-white-100 fpcm-ui-border-grey-medium" id="csv_field_' + fields[item] + '">' + fpcm.ui.translate(item) + '</li>'
                    )
                }
                
                fpcm.dom.fromClass('fpcm-ui-csv-fields').sortable({
                    connectWith: 'ul.fpcm-ui-csv-fields'
                });

                return false;
            }
            
        });   
                
        fpcm.dom.fromId('btnImportStart').click(function (event, ui)
        {
            let _fields = fpcm.dom.fromId('fpcm-ui-csv-fields-list').sortable('toArray');
            if (!_fields.length) {
                fpcm.ui.addMessage({
                   type: 'error',
                   txt: 'Bitte führe eine Feldauswahl durch.'
                });
                return false;
            }
            
            
            fpcm.worker.postMessage({
                namespace: 'import',
                function: '_exec',
                id: 'import.exec',
                param: {
                    item: fpcm.dom.fromId('import_destination').val().replace('\\', '__'),
                    csv: {
                        file: fpcm.dom.fromId('import_filename').val(),
                        delim: fpcm.dom.fromId('import_delimiter').val(),
                        enclo: fpcm.dom.fromId('import_enclosure').val(),
                        skipfirst: fpcm.dom.fromId('import_first').prop('selected'),
                        fields: fpcm.dom.fromId('fpcm-ui-csv-fields-list').sortable('toArray')
                    },
                    current: 1,
                    next: 1
                }
            });              
            
        });
        

    },
    
    _exec: function (_params) {

        if (!_params.csv.file) {
            fpcm.ui.addMessage({
               type: 'error',
               txt: 'Bitte lade eine gültige CSV-Datei hoch!'
            });

            return false;
        }
        
        fpcm.dom.fromClass('fpcm-ui-progressbar').parent().removeClass('ui-hidden');
        fpcm.dom.fromId('fpcm-ui-csv-upload').addClass('fpcm ui-hidden');        
        fpcm.dom.fromId('fpcm-ui-csv-settings').addClass('fpcm ui-hidden');        
        
        fpcm.ajax.post('import', {
            data: _params,
            cache: false,
            quiet: true,
            dataType: 'json',
            execDone: function (result) {

                if (!result.data) {

                    fpcm.ui.progressbar('.fpcm-ui-progressbar', {
                        max: 1,
                        value: 1
                    });

                    fpcm.ui.addMessage(result);
                    fpcm.ui.showMessages();

                    fpcm.worker.postMessage({
                        cmd: 'remove',
                        id: 'import.exec'
                    });

                    return false;
                }

                fpcm.ui.progressbar('.fpcm-ui-progressbar', {
                    max: result.data.fs,
                    value: result.current ? result.current : result.data.fs
                });

                if (result.data.lines && result.data.lines.length) {
                    fpcm.dom.fromId('list').append('<li>' + result.data.lines.join('</li><li>') + '</li>');
                }
                
                if (!result.next) {
                    fpcm.worker.postMessage({
                        cmd: 'remove',
                        id: 'import.exec'
                    });                

                    return false;
                }
                
                fpcm.testing.exec({
                    current: result.current,
                    next: result.next
                });
            },
            execFailed: function () {

                fpcm.worker.postMessage({
                    cmd: 'remove',
                    id: 'import.exec'
                });
                
            }
        });
        
    }

};

fpcm.filemanager = {

    runFileIndexUpdate: function (_params) {
        
        if (!_params.files || !_params.files[0] || !_params.files[0].name) {
            return false;
        }

        fpcm.ui.progressbar('.fpcm-ui-progressbar', {
            max: parseInt(_params.files[0].size),
            value: 0
        });
        
        fpcm.dom.fromClass('fpcm-ui-progressbar-label').text( fpcm.ui.translate('IMPORT_PROGRESS').replace('{{filename}}', _params.files[0].name) );

        fpcm.dom.fromId('fileupload').addClass('fpcm ui-hidden');
        fpcm.dom.appendHtml('#fpcm-ui-csv-upload', '<div class="row my-2">' + fpcm.ui.getTextInput({
                name: 'import_filename',
                id: 'import_filename',
                value: _params.files[0].name,
                text: fpcm.ui.translate('IMPORT_FILE')
            }) + '</div>'
        );

        fpcm.dom.isReadonly('#import_filename', true);
        return false;
    },
    
    getAcceptTypes: function ()
    {
        return /(\.|\/)(csv)$/i;
    }

};