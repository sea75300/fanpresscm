
/**
 * FanPress CM Editor Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor = {

    init: function() {
        
        fpcm.editor.initToolbar();

        if (!fpcm.vars.jsvars.isRevision) {
            fpcm.editor[fpcm.vars.jsvars.editorInitFunction].call();
        }
        else {
            fpcm.dom.fromClass('fpcm-ui-editor-categories-revisiondiff .fpcm-ui-input-checkbox').click(function() {
                return false;
            });
        }

        /**
         * Keycodes
         * http://www.brain4.de/programmierecke/js/tastatur.php
         */        
        document.addEventListener('keydown', function(_e) {

            if (_e.ctrlKey && _e.which == 83) {
            
                _e.preventDefault();
                
                var _saveBtnEl = fpcm.dom.fromId('btnArticleSave');
                if(_saveBtnEl) {
                    _saveBtnEl.click();
                    return false;
                }
            }

        });

    },
    
    initAfter: function() {

        fpcm.ui.autocomplete('#articleimagepath', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=editorfiles',
            minLength: 3
        });

    },
    
    initTinyMce: function() {

        fpcm.vars.jsvars.editorConfig.file_picker = function(callback, value, meta) {

            fpcm.editor.filePickerCallback = callback;
            fpcm.editor.filePickerActions = {
                fmUpload: 'btnFileUpload',
                fmSearch: 'btnOpenSearch',
                fmNewThumbs: 'btnCreateThumbs',
                fmDelete: 'btnDeleteFiles',
                fmGallery: 'btnInsertGallery',
            };
            
            _btns = [{
                type:  'custom',
                name: 'fmSearch',
                text: fpcm.ui.translate('ARTICLES_SEARCH'),
                disabled: false,
                primary: false,
                align: 'start'
            }];
            
            if (fpcm.vars.jsvars.filemanagerPermissions.add) {
                _btns.push({
                    type:  'custom',
                    name: 'fmUpload',
                    text: fpcm.ui.translate('FILE_LIST_UPLOADFORM'),
                    disabled: false,
                    primary: true,
                    align: 'start'
                });
            }
            
            if (!fpcm.editor.insertGalleryDisabled()) {
                _btns.push({
                    type:  'custom',
                    name: 'fmGallery',
                    text: fpcm.ui.translate('FILE_LIST_INSERTGALLERY'),
                    disabled: false,
                    primary: false,
                });
            }

            if (fpcm.vars.jsvars.filemanagerPermissions.thumbs) {
                _btns.push({
                    type:  'custom',
                    name: 'fmNewThumbs',
                    text: fpcm.ui.translate('FILE_LIST_NEWTHUMBS'),
                    disabled: false,
                    primary: false,
                });                
            }
            
            
            if (fpcm.vars.jsvars.filemanagerPermissions.delete) {
                _btns.push({
                    type:  'custom',
                    name: 'fmDelete',
                    text: fpcm.ui.translate('GLOBAL_DELETE'),
                    disabled: false,
                    primary: false,
                });                
            }
            
            _btns.push({
                type:  'cancel',
                name: 'fmClose',
                text: fpcm.ui.translate('GLOBAL_CLOSE'),
                disabled: false,
                primary: true
            });

            tinymce.activeEditor.windowManager.openUrl({
                title: fpcm.ui.translate('HL_FILES_MNG'),
                size: 'large',
                url: fpcm.vars.jsvars.filemanagerUrl + fpcm.vars.jsvars.filemanagerMode,
                id: 'fpcm-dialog-editor-tinymce-filemanager',
                buttons: _btns,
                onAction: function(api, action) {

                    if (!fpcm.editor.filePickerActions[action.name]) {
                        return false;
                    }

                    api.sendMessage({
                        mceAction: 'clickFmgrBtn',
                        cmd: fpcm.editor.filePickerActions[action.name],
                        validSource: window.location.href
                    });
                }
            });

            return true;
        }

        fpcm.vars.jsvars.editorConfig.onPaste = function(plugin, args) {
            var content = fpcm.editor_videolinks.replace(args.content);
            if (content === args.content) {
                return true;
            }

            fpcm.ui_loader.show();
            args.content = fpcm.editor_videolinks.createFrame(content, true);
            fpcm.ui_loader.hide();
        };

        fpcm.vars.jsvars.editorConfig.onInitAfterStd = function(editor) {

            editor.ui.registry.addButton('fpcm_emoticons', {
                icon: 'emoji',
                tooltip: fpcm.ui.translate('EDITOR_INSERTSMILEY'),
                disabled: false,
                onAction: function () {

                    tinymce.activeEditor.windowManager.open({
                        title: fpcm.ui.translate('EDITOR_INSERTSMILEY'),
                        size: 'normal',
                        body: {
                            type: 'panel',
                            items: [{
                                type: 'collection',
                                name: 'smileyList'
                            }]
                        },
                        buttons: [
                            {
                                type:  'cancel',
                                text: fpcm.ui.translate('GLOBAL_CLOSE'),
                                disabled: false,
                                primary: true
                            },                          
                        ],
                        initialData: {
                            smileyList: fpcm.vars.jsvars.editorConfig.fpcmEmoticons
                        },
                        onAction: function (api, data) {

                            if (data.value) {
                                editor.insertContent(data.value);
                            }

                            api.close();
                        }
                    });

                },
                onSetup: function (buttonApi) {
                    fpcm.ajax.get('editor/smileys', {
                        quiet: true,
                        dataType: 'json',
                        data: {
                            json: true
                        },
                        execDone: function (items) {

                            fpcm.vars.jsvars.editorConfig.fpcmEmoticons = [];

                            for(var x = 0;x < items.length; x++) {
                                fpcm.vars.jsvars.editorConfig.fpcmEmoticons.push({
                                    text: items[x].code,
                                    value: ' ' + items[x].code + ' ',
                                    icon: items[x].img
                                });
                            }

                        }
                    });
                }
            });
            
            var _galleryPlaceholderClass = 'fpcm-content-gallery-placeholder';
            
            editor.on('ResolveName', function (e) {
                if (e.target.nodeName === 'FIGURE' && editor.dom.hasClass(e.target, _galleryPlaceholderClass)) {
                    e.name = 'gallery';
                }
            });
            
            editor.on('BeforeSetContent', function (e) {

                let _data = e.content.match(/\[gallery\](.*)\[\/gallery\]/i);
                let _hasGallery = e.content.search(_galleryPlaceholderClass) > 0;
                if (!_data || _hasGallery) {
                    return true;
                }

                let _list = _data[1].split('|');
                if (!_list.length) {
                    return true;
                }
                
                let _repl = [];
                
                for (var i = 0; i < _list.length; i++) {

                    if (!_list[i]) {
                        continue;
                    }

                    let _item = _list[i].split(/(thumb:)?([a-z0-9_.\-\/]*)(:link)?/i);
                    if (!_item || !_item.length) {
                        continue;
                    }
                    
                    let _imgurl = fpcm.vars.jsvars.uploadFileRoot + (_item[1] === fpcm.vars.jsvars.galleryThumbStr ? _item[2].replace('/', '/thumbs/') : _item[2]);
                    _repl.push('<img src="' + _imgurl + '" class="fpcm-content-gallery-item" data-mce-resize="false" data-mce-placeholder="1"  data-item="' + _list[i] + '" />');
                }
                
                if (!_repl.length) {
                    return true;
                }

                _repl.unshift('<figure role="group" class="' + _galleryPlaceholderClass + '" data-mce-placeholder="1" data-placeholder="' + _data[0] + '">');
                _repl.push('</figure>');

                e.content = e.content.replace(_data[0], _repl.join(''));

                return true;
            });

            editor.on('PreInit', function () {

                editor.serializer.addNodeFilter('figure', function (_nodes) {

                    if (!_nodes.length) {
                        return true;
                    }

                    for (var i = 0; i < _nodes.length; i++) {

                        if (!_nodes[i]) {
                            continue;
                        }

                        let _node = _nodes[i];
                        if (_node.attr('class') !== _galleryPlaceholderClass) {
                            continue;
                        }

                        _node.value = _node.attr('data-placeholder');
                        _node.raw = true;
                        _node.type = 3;
                    }
                });
            });

            editor.on('NodeChange', function (_params) {

                if (!_params.selectionChange || _params.element.nodeName != 'FIGURE' || !_params.element.classList.contains(_galleryPlaceholderClass)) {
                    return true;
                }

                if (!_params.element.children.length) {
                    return true;
                }
                
                let _elList = [];
                for (var i = 0; i < _params.element.children.length; i++) {
                    _elList.push(_params.element.children[i].dataset.item);
                }
                
                if (!_elList.length) {
                    return true;
                }

                _params.element.dataset.placeholder = '[gallery]' + _elList.join('|') + '[/gallery]';
                return true;
                
            });
        
        };

        fpcm.editor_tinymce.create(fpcm.vars.jsvars.editorConfig);

    },
    
    initCodeMirrorAutosave: function() {

        fpcm.vars.jsvars.autoSaveStorage = localStorage.getItem(fpcm.vars.jsvars.editorConfig.autosavePref);

        setInterval(function() {

            var editorValue = fpcm.editor.cmInstance.getValue();
            if (!editorValue) {
                return false;
            }
            
            if (editorValue === localStorage.getItem(fpcm.vars.jsvars.editorConfig.autosavePref)) {
                return true;
            }

            localStorage.setItem(fpcm.vars.jsvars.editorConfig.autosavePref, editorValue);
            fpcm.dom.fromId('editor-html-buttonrestore').prop('disabled', false);
            
        }, 30000);

    },
    
    initCodeMirror: function() {

        fpcm.editor.cmInstance = fpcm.editor_codemirror.create({
           editorId  : 'htmleditor',
           elementId : 'articlecontent',
           extraKeys : fpcm.editor_codemirror.defaultShortKeys
        });
        
        fpcm.editor.cmInstance.setSize('100%', '50vh');
        
        fpcm.editor.cmInstance.on('paste', function(instance, event) {
                
            if (event.clipboardData === undefined) {
                return true;
            }

            var orgText = event.clipboardData.getData('Text');            
            var chgText = fpcm.editor_videolinks.replace(orgText);

            if (orgText === chgText) {
                return false;
            }

            fpcm.ui_loader.show();
            event.preventDefault();
            fpcm.editor_videolinks.createFrame(chgText, false);
            fpcm.ui_loader.hide();
            return true;

        });

        fpcm.editor.initCodeMirrorAutosave();

    },
    
    initAce: function() {

        fpcm.editor_ace.create({
           elementId: fpcm.ui.prepareId('content-ace', true),
           textareaId: 'articlecontent',
           type: 'articletext'
        });
        
    },

    showInEditDialog: function(result){

        if (fpcm.vars.jsvars.checkLastState == 1 && result.articleCode == 0) {

            fpcm.ui.addMessage({
                type : 'notice',
                id   : 'fpcm-editor-notinedit',
                icon : 'check',
                txt  : fpcm.ui.translate('EDITOR_STATUS_NOTINEDIT')
            }, true);
        }

        if (fpcm.vars.jsvars.checkLastState == 0 && result.articleCode == 1) {
            var msg = fpcm.ui.translate('EDITOR_STATUS_INEDIT');
            fpcm.ui.addMessage({
                type : 'neutral',
                id   : 'fpcm-editor-inedit',
                icon : 'pencil-square',
                txt  : msg.replace('{{username}}', result.username)
            }, true);
        }

        fpcm.vars.jsvars.checkLastState = result.articleCode;
    },
    
    getGalleryReplacement: function (_values) {
        return fpcm.vars.jsvars.editorGalleryTagStart.replace(
            '{{IMAGES}}',
            fpcm.vars.jsvars.editorGalleryTagThumb + _values.join(fpcm.vars.jsvars.editorGalleryTagLink + '|' + fpcm.vars.jsvars.editorGalleryTagThumb) + fpcm.vars.jsvars.editorGalleryTagLink
        );
    },
    
    manageSources: function (_result, _receiver) {

        if (!_result.length) {

            let _notFound = fpcm.ui.getIcon('list-ul', {
                stack: 'ban fpcm-ui-important-text',
                stackTop: true,
            }) + ' ' + fpcm.ui.translate('GLOBAL_NOTFOUND2');                        

            _result = [{
                value: false,
                label: _notFound
            }];
        }

        let _delDescr = fpcm.ui.translate('GLOBAL_DELETE');

        let _content = '<div class="list-group">';
        for (var _i in _result) {

            let _item = _result[_i];
            let _btn = '';
            let _link = _item.label;

            if (_item.value) {
                _btn = ` <button type="button" class="btn-close" aria-label="${_delDescr}" title="${_delDescr}" data-src-del-item="${_item.value}"></button>`;
                _link = ` <a href="${_item.value}" target="_blank" rel='external'>${_item.label}</a>`;
            }

            _content += `<div class="list-group-item d-flex justify-content-between align-items-start"><div class="align-self-center">${_link}</div>${_btn}</div>`;
        }

        _content += '</div>';
        
        if (_receiver) {
            _receiver.innerHTML = _content;
            return false;
        }

        fpcm.ui_dialogs.create({
            id: 'sources-mgr',
            title: fpcm.ui.translate('SYSTEM_OPTIONS_NEWS_SOURCESLIST'),
            content: _content,
            closeButton: true,
            dlOnOpenAfter: function (_ui, _bsObj) {

                fpcm.dom.bindClick('button[data-src-del-item]', function (_ev, _bui) {
                    fpcm.ajax.post('autocompleteCleanup', {
                        data: {
                            term: _bui.dataset.srcDelItem,
                            src: 'articlesources'
                        },
                        execDone: function (_result) {

                            let _parent = _bui.parentElement.parentElement;
                            _bui.parentElement.remove();

                            if (_parent.childNodes.length) {
                                return true;
                            }

                            fpcm.editor.manageSources([], _ui.children[0].children[0].children[2]);
                        }
                    });
                });
            }
        });
    }

};
