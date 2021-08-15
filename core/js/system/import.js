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
            change: function (event, ui) {
                
                fpcm.dom.fromId('fpcm-ui-csv-fields-select').empty();
                fpcm.dom.fromId('fpcm-ui-csv-fields-list').empty();

                if (!fpcm.vars.jsvars.fields[ui.value.replace('\\', '_')]) {
                    return false;
                }
                
                let fields = fpcm.vars.jsvars.fields[ui.value.replace('\\', '_')];

                let _i = 0;
                for (var item in fields) {
                    _i++;
                    fpcm.dom.appendHtml(
                        '#fpcm-ui-csv-fields-select',
                        '<li class="list-group-item" id="csv_field_' + fields[item] + '" draggable="true" data-index="' + _i + '" data-fname="' + fields[item] + '">' + fpcm.ui.translate(item) + '</li>'
                    )
                }

                fpcm.import._initDnd(
                    '#fpcm-ui-csv-fields-list',
                    '#fpcm-ui-csv-fields-select li.list-group-item'
                );

                fpcm.import._initDnd(
                    '#fpcm-ui-csv-fields-select',
                    '#fpcm-ui-csv-fields-list li.list-group-item'
                );

                return false;
            }
            
        });     

        fpcm.dom.bindClick('#btnImportReset', function (event, ui)
        {
            fpcm.import._exec({
                csv: {
                    file: fpcm.dom.fromId('import_filename').val(),
                },
                reset: true,
                unique: fpcm.vars.jsvars.unique,
            });

            fpcm.worker.postMessage({
                cmd: 'remove',
                id: 'import.exec'
            });

        });
        
        fpcm.dom.bindClick('#btnImportPreview', function (event, ui)
        {            
            if (!fpcm.import._checkPreconditions()) {
                return false;
            }            

            fpcm.worker.postMessage({
                namespace: 'import',
                function: '_exec',
                id: 'import.exec',
                param: {
                    csv: {
                        item: fpcm.dom.fromId('import_destination').val().replace('\\', '__'),
                        file: fpcm.dom.fromId('import_filename').val(),
                        delim: fpcm.dom.fromId('import_delimiter').val(),
                        enclo: fpcm.dom.fromId('import_enclosure').val(),
                        skipfirst: fpcm.dom.fromId('import_first').prop('checked'),
                        fields: fpcm.import._getFields()
                    },
                    start: true,
                    preview: true,
                    unique: fpcm.vars.jsvars.unique,
                    current: 1,
                    next: 1
                }
            }); 

        });
        
        fpcm.dom.bindClick('#btnImportStart', function (event, ui)
        {
            if (!fpcm.import._checkPreconditions()) {
                return false;
            }

            fpcm.dom.fromId('fpcm-progress-csvimport').parent().removeClass('ui-hidden');
            fpcm.dom.fromId('fpcm-ui-csv-upload').addClass('fpcm ui-hidden');
            fpcm.dom.fromId('fpcm-ui-csv-settings').addClass('fpcm ui-hidden');
            
            fpcm.ui_dialogs.confirmDlg({
                clickYes: function () {
                    fpcm.worker.postMessage({
                        namespace: 'import',
                        function: '_exec',
                        id: 'import.exec',
                        param: {
                            csv: {
                                item: fpcm.dom.fromId('import_destination').val().replace('\\', '__'),
                                file: fpcm.dom.fromId('import_filename').val(),
                                delim: fpcm.dom.fromId('import_delimiter').val(),
                                enclo: fpcm.dom.fromId('import_enclosure').val(),
                                skipfirst: fpcm.dom.fromId('import_first').prop('checked'),
                                fields: fpcm.import._getFields()
                            },
                            start: true,
                            unique: fpcm.vars.jsvars.unique,
                            current: 1,
                            next: 1
                        }
                    });                     
                }
            });
            
        });

    },
    
    _exec: function (_params) {

        if (_params.start && !_params.csv.file) {
            fpcm.ui.addMessage({
               type: 'error',
               txt: 'IMPORT_MSG_NOFILE'
            });

            return false;
        }
        
        fpcm.ajax.post('import', {
            data: _params,
            cache: false,
            quiet: true,
            dataType: 'json',
            execDone: function (result) {
                
                if (result.reset !== undefined) {

                    fpcm.worker.postMessage({
                        cmd: 'remove',
                        id: 'import.exec'
                    });

                    fpcm.ui.relocate('self');
                    return false;
                }

                if (!result.data) {

                    fpcm.ui.progressbar('csvimport', {
                        max: 1,
                        value: 1
                    });

                    fpcm.ui.addMessage(result, true);

                    fpcm.worker.postMessage({
                        cmd: 'remove',
                        id: 'import.exec'
                    });

                    return false;
                }
                
                if (result.data.previews) {

                    fpcm.worker.postMessage({
                        cmd: 'remove',
                        id: 'import.exec'
                    });

                    fpcm.import._showPreviewDialog(result.data);

                    return false;
                }

                fpcm.ui.progressbar('csvimport', {
                    max: result.data.fs,
                    value: result.current ? result.current : result.data.fs,
                    animated: true
                });
                
                if (!result.next) {
                    fpcm.worker.postMessage({
                        cmd: 'remove',
                        id: 'import.exec'
                    });
                    
                    fpcm.ui.addMessage({
                        txt: 'IMPORT_MSG_FINISHED',
                        type: 'notice',
                    });

                    return false;
                }
                
                fpcm.import._exec({
                    current: result.current,
                    next: result.next,
                    unique: result.unique
                });
            },
            execFailed: function () {

                fpcm.worker.postMessage({
                    cmd: 'remove',
                    id: 'import.exec'
                });
                
            }
        });
        
    },
    
    _checkPreconditions: function() {
        
        
        if (!fpcm.dom.fromId('import_filename').val()) {
            fpcm.ui.addMessage({
               type: 'error',
               txt: 'IMPORT_MSG_NOFILE'
            });
            return false;
        }

        if (!fpcm.import._getFields().length) {
            fpcm.ui.addMessage({
               type: 'error',
               txt: 'IMPORT_MSG_NOFIELDS'
            });
            return false;
        }
        
        return true;
    },
    
    _showPreviewDialog: function (_data) {

        let html = [];
        let _rowFields = [];

        html.push('<div class="row mx-0 px-0">');
        for (var item in _data.previews[0]) {            
            html.push('<div class="col"><strong>' + fpcm.ui.translate(item) + '</strong></div>');
            _rowFields.push(_data.previews[0][item]);
        }

        html.push('</div>');

        for (var r = 1; r < _data.previews.length; r++) {

            if (_data.previews[r] === undefined) {
                continue;
            }

            html.push('<div class="row mx-0 my-1 px-0">');
            for (var f = 0; f < _rowFields.length; f++) {
                html.push('<div class="col">' + _data.previews[r][ _rowFields[f] ] + '</div>');
            }

            html.push('</div>');
        }

        html = html.join('\n');

        fpcm.ui_dialogs.create({
            title: 'GLOBAL_PREVIEW',
            dialogId: 'csv-import-preview',
            content: html,
            closeButton: true,
            size: 'xl'
        });        

        return false;
    },
    
    _getFields: function () {

        let _fselect = fpcm.dom.fromId('fpcm-ui-csv-fields-list').find('li');
        let _fields = [];

        if (!_fselect.length) {
            fpcm.ui.addMessage({
               type: 'error',
               txt: 'IMPORT_MSG_INVALIDIMPORTTYPE_NONE'
            });

            return _fields;
        }

        for (var i = 0; i < _fselect.length; i++) {
            _fields.push(_fselect[i].dataset.fname);
        }

        return _fields;
    },
    
    _initDnd: function (_dragElement, _dragStartElement) {
        fpcm.ui_dnd.initDnd({
            dragElement: _dragElement,
            dragStartElement: _dragStartElement,
            dropZone: _dragElement,

            dropCallback: function (_event, _ui) {
                fpcm.dom.fromTag(_source).appendTo(_event.currentTarget);        
                _event.delegateTarget.classList.remove('border');
                _event.delegateTarget.classList.remove('border-success');
            },
            dragstartCallback: function (_event, _ui) {
                _source = _event.target;
            },
            dragenterCallback: function (_event, _ui) {
                _event.preventDefault();        
                _event.delegateTarget.classList.add('border');
                _event.delegateTarget.classList.add('border-success');
            },
            dragleaveCallback: function (_event, _ui) {
                _event.preventDefault();              
                _event.delegateTarget.classList.remove('border');
                _event.delegateTarget.classList.remove('border-success');
            }
        });
    }

};

fpcm.filemanager = {

    runFileIndexUpdate: function (_params) {
        
        if (!_params.files || !_params.files[0] || !_params.files[0].name) {
            return false;
        }

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