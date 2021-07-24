
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

        fpcm.ui_tabs.render('#tabs-editor', {
            initDataViewOnRenderAfter: function () {
                fpcm.ui.assignCheckboxes();
                fpcm.ui.assignControlgroups(),
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

        fpcm.dom.fromId('articlecategories').selectize({
            placeholder: fpcm.ui.translate('EDITOR_CATEGORIES_SEARCH'),
            searchField: ['text', 'value'],
            plugins: ['remove_button']
        });

        fpcm.dom.fromClass('fpcm-editor-articleimage').fancybox();

//        fpcm.dom.fromId('insertarticleimg').click(function () {
//            fpcm.editor.showFileManager(3);
//            return false;
//        });
//
//        fpcm.dom.fromId('insertposterimg').click(function () {
//            fpcm.editor.showFileManager(4);
//            return false;
//        });
//
        fpcm.ui.autocomplete('#articleimagepath', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=editorfiles',
            minLength: 3
        });

        fpcm.ui.autocomplete('#articlesources', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articlesources',
            minLength: 3
        });
//
//        fpcm.editor.tweetTextInput = fpcm.dom.fromId('articletweettxt');
//        fpcm.ui.selectmenu('#twitterReplacements', {
//            change: function( event, ui ) {
//
//                if (ui.item.value) {
//                    var currentText = fpcm.editor.tweetTextInput.val();
//                    var currentpos = fpcm.dom.fromTag(fpcm.editor.tweetTextInput).prop('selectionStart');
//                    fpcm.editor.tweetTextInput.val(currentText.substring(0, currentpos) + ui.item.value +  currentText.substring(currentpos));
//                }
//
//                this.selectedIndex = 0;
//                fpcm.dom.fromTag(this).selectmenu('refresh');
//                return false;
//            }
//        });
//        
//
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
                                
                                if (!result.permalink) {
                                    let _domEl = document.createElement('input');
                                    _domEl.type = 'hidden';
                                    _domEl.id = 'fpcm-editor-shotlink';
                                    _domEl.value = result.shortend;
                                    document.appendChild(_domEl);
                                }

                                let _el = fpcm.dom.fromId('fpcm-editor-shotlink');
                                 if (!_el.length) {
                                     return true;
                                 }

                                 _el.select();
                                document.execCommand('copy');
                                if (!result.permalink) {
                                    fpcm.dom.fromId('fpcm-editor-shotlink').remove();
                                }                                

                            }
                        }]
                    };
                             
                    if (result.permalink) {
                        _par.content = fpcm.ui.getTextInput({
                                            name: 'fpcm-editor-shotlink',
                                            value: result.shortend,
                                            text: fpcm.ui.translate('EDITOR_ARTICLE_SHORTLINK'),
                                        });
                    }
                    else {
                        _par.url = result.shortend;
                    }

                    fpcm.ui.dialog(_par);
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

        fpcm.ui.dialog({
            id: 'editor-comments',
            title: 'COMMENTS_EDIT',
            url: _url,
            closeButton: true,
            dlButtons  : [
                {
                    text: 'GLOBAL_SAVE',
                    icon: "save",
                    primary: true,
                    click: function(_ui) {
                        fpcm.dom.findElementInDialogFrame(_ui, '#btnCommentSave').click();
                        fpcm.ui_tabs.show('#tabs-editor', 2);
                    }
                },
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
                }                           
            ]
        });
        
        fpcm.ui_loader.hide();
        return false;
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
            fpcm.ui.button('#editor-html-buttonrestore', {
                disabled: false
            });
            
        }, 30000);

    },
    
    initCodeMirror: function() {

        fpcm.editor.cmInstance = fpcm.editor_codemirror.create({
           editorId  : 'htmleditor',
           elementId : 'articlecontent',
           extraKeys : fpcm.editor_codemirror.defaultShortKeys
        });
        
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
        
        var sizeSmall = fpcm.ui.getDialogSizes(top, 0.35);
        var sizeLarge = fpcm.ui.getDialogSizes();

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

    insertIFrame: function(url, params, returnOnly) {
        
        if (url === undefined) {
            url = 'http://';
        }
        
        if (params === undefined) {
            params = [];
        }

        var code = '<iframe src="' + url + '" class="fpcm-articletext-iframe" ' + params.join(' ') + '></iframe>';
        if (!returnOnly) {
            fpcm.editor.insert(code, '');
            return true;
        }

        return code;
    },
    
    getGalleryReplacement: function (_values) {
        return fpcm.vars.jsvars.editorGalleryTagStart.replace(
            '{{IMAGES}}',
            fpcm.vars.jsvars.editorGalleryTagThumb + _values.join(fpcm.vars.jsvars.editorGalleryTagLink + '|' + fpcm.vars.jsvars.editorGalleryTagThumb) + fpcm.vars.jsvars.editorGalleryTagLink
        );
    }

};
