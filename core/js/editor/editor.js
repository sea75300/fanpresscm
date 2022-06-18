
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

        fpcm.ui_tabs.render('#tabs-editor', {
            initDataViewOnRenderAfter: function () {
                fpcm.editor.initCommentListActions();
            }
        });

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
        fpcm.dom.fromTag(document).keydown(function(thekey) {

            if (thekey.ctrlKey && thekey.which == 83) {
                
                var _saveBtnEl = fpcm.dom.fromId('btnArticleSave');
                if(_saveBtnEl) {
                    _saveBtnEl.click();
                    return false;
                }
            }

        });

    },
    
    initAfter: function() {

        fpcm.ui.multiselect('articlecategories', {
            placeholder: 'EDITOR_CATEGORIES_SEARCH'
        });

        fpcm.dom.fromClass('fpcm-editor-articleimage').fancybox();

        fpcm.dom.fromId('insertarticleimg').click(function () {
            fpcm.editor.showFileManager(3);
            return false;
        });

        fpcm.ui.autocomplete('#articleimagepath', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=editorfiles',
            minLength: 3
        });

        fpcm.ui.autocomplete('#articlesources', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articlesources',
            minLength: 3
        });

        fpcm.editor.tweetTextInput = fpcm.dom.fromId('articletweettxt');
        fpcm.ui.selectmenu('#twitterReplacements', {
            change: function( event, ui ) {

                if (ui.value) {
                    var currentText = fpcm.editor.tweetTextInput.val();
                    var currentpos = fpcm.dom.fromTag(fpcm.editor.tweetTextInput).prop('selectionStart');
                    fpcm.editor.tweetTextInput.val(currentText.substring(0, currentpos) + ui.value +  currentText.substring(currentpos));
                }

            },
            resetAfter: true
        });
        

        if (!fpcm.vars.jsvars.articleId) {
            return true;
        }

        fpcm.dom.fromId('btnShortlink').click(function (event, handler) {

            fpcm.ajax.get('editor/editorlist', {
                dataType: 'json',
                data: {
                    id: fpcm.dom.fromTag(this).data().article,
                    view: 'shortlink'
                },
                execDone: function (result) {


                    let _par = {
                        id: 'editor-shortlink',
                        title: fpcm.ui.translate('EDITOR_ARTICLE_SHORTLINK'),
                        closeButton: true,
                        dlButtons: [{
                            text: 'EDITOR_ARTICLE_SHORTLINK_COPY',
                            icon: 'copy',
                            click: function () {

                                let _el = fpcm.dom.fromId('fpcm-editor-shotlink');
                                 if (!_el.length) {
                                     return true;
                                 }

                                 _el.select();
                                document.execCommand('copy');
                            }
                        }]
                    };

                    _par.content = '<div class="form-floating mb-3">' +
                                    '<input type="url" class="form-control" id="fpcm-editor-shotlink" name="fpcm-editor-shotlink" placeholder="' + fpcm.ui.translate('EDITOR_ARTICLE_SHORTLINK') + '" value="' + result.shortend + '">' +
                                    '<label for="fpcm-editor-shotlink">' + fpcm.ui.translate('EDITOR_ARTICLE_SHORTLINK') + '</label>' +
                                  '</div>';

                    fpcm.ui_dialogs.create(_par);
                }
            });

             return false;
        });

        fpcm.dom.fromTag('.fpcm-ui-editor-metainfo-checkbox').on('change', function () {
            fpcm.dom.fromTag('span.fpcm-ui-editor-metainfo-' + fpcm.dom.fromTag(this).data('icon')).toggleClass('fpcm-ui-status-1 fpcm-ui-status-0');
            return true;
        });
    },
    
    showCommentLayer: function(_url) {

        fpcm.ui_dialogs.create({
            id: 'editor-comments',
            title: 'COMMENTS_EDIT',
            url: _url,
            closeButton: true,
            dlButtons  : [
                {
                    text: 'COMMMENT_LOCKIP',
                    icon: "lock",
                    disabled: fpcm.vars.jsvars.lkIp ? false : true,
                    click: function(_ui) {
                        fpcm.dom.findElementInDialogFrame(_ui, '#btnLockIp').click();
                    }
                },
                {
                    text: 'Whois',
                    icon: "globe",
                    click: function(_ui) {
                        let _el = fpcm.dom.findElementInDialogFrame(_ui, '#whoisIp');
                        window.open(_el[0].href, '_blank', 'width=700,height=500,scrollbars=yes,resizable=yes,');
                    }
                },
                {
                    text: 'GLOBAL_SAVE',
                    icon: "save",
                    primary: true,
                    click: function(_ui) {
                        fpcm.dom.findElementInDialogFrame(_ui, '#btnCommentSave').click();
                        fpcm.ui_tabs.show('#tabs-editor', 2);
                    }
                }
            ]
        });
        
        fpcm.ui_loader.hide();
        return false;
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
                if (!_data) {
                    return true;
                }

                let _list = _data[1].split('|');
                if (!_list.length) {
                    return true;
                }
                
                let _repl = [];
                _repl.push('<figure role="group" class="' + _galleryPlaceholderClass + '" data-mce-placeholder="1" data-placeholder="' + _data[0] + '">');
                
                for (var i = 0; i < _list.length; i++) {

                    if (!_list[i]) {
                        continue;
                    }

                    let _item = _list[i].split(/(thumb:)?([a-z0-9_.\-\/]*)(:link)?/i);
                    if (!_item || !_item.length) {
                        continue;
                    }
                    
                    let _imgurl = fpcm.vars.jsvars.uploadFileRoot + (_item[1] === fpcm.vars.jsvars.galleryThumbStr ? _item[2].replace('/', '/thumbs/') : _item[2]);
                    _repl.push('<img src="' + _imgurl + '" class="fpcm-content-gallery-item" style="padding: 0 0.25rem;" data-mce-resize="false" data-mce-placeholder="1" />');
                }

                _repl.push('</figure>');
                e.content = e.content.replace(_data[0], _repl.join(''));
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

    showInEditDialog: function(result){

        if (fpcm.vars.jsvars.checkLastState == 1 && result.articleCode == 0 && !result.articleUser) {

            fpcm.ui.addMessage({
                type : 'notice',
                id   : 'fpcm-editor-notinedit',
                icon : 'check',
                txt  : fpcm.ui.translate('EDITOR_STATUS_NOTINEDIT')
            }, true);
        }

        if (fpcm.vars.jsvars.checkLastState == 0 && result.articleCode == 1 && result.articleUser) {
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
    
    initCommentListActions: function () {
        
        if (!fpcm.comments) {
            return true;
        }
        
        fpcm.comments.assignActionsList();
        
        fpcm.dom.fromClass('fpcm-ui-commentlist-link').click(function () {
            fpcm.ui_loader.hide();
            fpcm.editor.showCommentLayer(fpcm.dom.fromTag(this).attr('href'));
            return false;
        });

    },
    
    getGalleryReplacement: function (_values) {
        return fpcm.vars.jsvars.editorGalleryTagStart.replace(
            '{{IMAGES}}',
            fpcm.vars.jsvars.editorGalleryTagThumb + _values.join(fpcm.vars.jsvars.editorGalleryTagLink + '|' + fpcm.vars.jsvars.editorGalleryTagThumb) + fpcm.vars.jsvars.editorGalleryTagLink
        );
    }

};
