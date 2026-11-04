/**
 * FanPress CM Templates Namespace
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.templates = {

    _tplId: '',
    _cssFilePath: '',

    init: function() {

//        if (fpcm.dataview.exists('draftfiles')) {
//            fpcm.dataview.render('draftfiles');
//        }

        fpcm.ui_tabs.render('#fpcm-tabs-templates', {

            onRenderHtmlBefore: (_ui, _result) => {

                let _cards = document.createElement('div');
                _cards.classList.add('mx-2', 'py-2');

                for (var _folder in _result) {
                    
                    let _card = document.createElement('div');
                    _card.classList.add('card');   
                    
                    let _headline = document.createElement('div');
                    _headline.classList.add('card-header');
                    _headline.innerHTML = fpcm.ui.translate('TEMPLATES_FOLDER_' + _folder.toUpperCase());
                    _card.appendChild(_headline);

                    let _list = document.createElement('div');
                    _list.classList.add('list-group', 'list-group-flush');
                    
                    let _files = _result[_folder];
                    for (var _file in _files) {
                        
                        let _row = document.createElement('a');
                        _row.classList.add('list-group-item', 'list-group-item-action');
                        _row.href = _files[_file];
                        _row.innerHTML = _file;
                        _list.appendChild(_row);                        
                        
                    }
                    
                    _card.appendChild(_list);
                    
                    _cards.appendChild(_card);
                    
                }
                
                _result.dom = _cards;

                console.log(_result);

                //let _html = `<div class="list-group">${rows}</div>`;
                
                
                //<a href="#" class="list-group-item list-group-item-action">A simple default list group item</a>

                _result.html = '';
//                if (!fpcm.templates.editorInstance ||
//                    !_ui.relatedTarget.dataset.tplid ) {
//
//                    if (_ui.relatedTarget && _ui.relatedTarget.dataset.tplid) {
//                        fpcm.templates._saveCss(_ui.relatedTarget.dataset.tplid);
//                    }
//
//
//                    return true;
//                }
//
//                if (fpcm.editor_ace._instancePreview) {
//                    fpcm.editor_ace._instance.destroy();
//                }
//
//                fpcm.dom.fromTag(_ui.relatedTarget.dataset.bsTarget).empty();
//                return true;
            },

            onRenderHtmlAfter: (_ui) => {
//                fpcm.templates._restoreCss();
            }
        });


        /*fpcm.dom.bindClick('.fpcm-articletemplates-edit', function(_ev, _ui) {
            fpcm.ui_loader.hide();
            fpcm.ui_dialogs.create({
                id: 'draft-editor',
                title: 'TEMPLATE_HL_DRAFTS_EDIT',
                url: _ui.attributes.href.value,
                icon: {
                    icon: 'file-code'
                },
                closeButton: true,
                dlButtons : [
                    {
                        text: 'GLOBAL_SAVE',
                        icon: "save",
                        primary: true,
                        click: function(_ui) {
                            fpcm.dom.appendAndClickButtonInDialogFrame(_ui, 'btnSaveTemplate');
                            fpcm.ui_loader.hide();
                        }
                    }
                ]
            });

            return false;

        });

        fpcm.dom.bindClick('#btnSaveTemplates', function () {
            fpcm.ui_dialogs.confirm({
                clickYes: function () {
                    fpcm.ajax.post('templates/save', {
                        data: {
                            content: fpcm.editor_ace.getValue(),
                            tplid  : fpcm.templates._tplId
                        },
                        execDone: function (_result) {

                            if (_result instanceof Object && _result.txt) {
                                fpcm.ui.addMessage(_result);
                                return true;
                            }

                        }
                    });
                }
            });
        });*/

    },

    _saveCss: function(_id) {

        if (_id === undefined) {
            _id = fpcm.templates._tplId;
        }

        let _fid = 'css-file-' + _id;

        let _cff = document.getElementById(_fid);
        if (!_cff) {
            return false;
        }

        fpcm.templates._cssFilePath = _cff.value;

    },

    _restoreCss: function (_id) {
        
        if (!fpcm.templates._cssFilePath) {
            return;
        }

        if (_id === undefined) {
            _id = fpcm.templates._tplId;
        }

        let _fid = 'css-file-' + _id;

        document.getElementById(_fid).value = fpcm.templates._cssFilePath;
    }

};

fpcm.filemanager = {

    runFileIndexUpdate: function (_params) {
        fpcm.ui.relocate('?module=templates/templates&rg=6');
    },

    getAcceptTypes: function ()
    {
        return /(\.|\/)(htm|html|txt)$/i;
    },

    getAcceptTypesArr: function ()
    {
        return ['html', 'htm', 'txt', 'application/xhtml+xml', 'text/html', 'text/plain'];
    }

};

fpcm.editor = { }