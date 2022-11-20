/**
 * FanPress CM Article list Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.article = {

    init: function() {
        
        fpcm.ui_tabs.render('#tabs-editor', {
            initDataViewOnRenderAfter: function () {
                fpcm.article._initCommentActions();
            }
        });


    },
    
    initAfter: function() {

        fpcm.article._initAll();
        fpcm.article._initEdit();

        window.categoryMs = fpcm.ui.multiselect('articlecategories');
        
        fpcm.dom.bindClick('#btnEditsources', function () {         
            
            fpcm.ajax.post('autocomplete', {
                data: {
                    term: '',
                    src: 'articlesources'
                },
                execDone: function (_result) {
                    fpcm.editor.manageSources(_result);
                }
            });
        });
    },
    
    _initCommentActions: function () {
        
        if (!fpcm.comments) {
            return true;
        }
        
        fpcm.comments.assignActionsList();
        
        fpcm.dom.bindClick('.fpcm-ui-commentlist-link', function (_e, _ui) {
            fpcm.article._initCommentDialog(_ui.href);
        });
        

    },
    
    _initAll: function () {

        fpcm.ui.autocomplete('#articlesources', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articlesources',
            minLength: 3
        });

        fpcm.dom.bindClick('#insertarticleimg', function () {
            fpcm.editor.showFileManager(3);
        });

        fpcm.editor.tweetTextInput = fpcm.dom.fromId('articletweettxt');
        fpcm.dom.bindClick('#twitterReplacements li > a.dropdown-item', function (_e, _ui) {

            if (!_ui.dataset.var) {
                return false;
            }
            
            let currentText = fpcm.editor.tweetTextInput.val();
            let currentpos = fpcm.dom.fromTag(fpcm.editor.tweetTextInput).prop('selectionStart');

            fpcm.editor.tweetTextInput.val(currentText.substring(0, currentpos) + _ui.dataset.var +  currentText.substring(currentpos));
        });
        
    },
    
    _initEdit: function () {
        
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
        
        fpcm.dom.bindEvent('.fpcm-ui-editor-metainfo-checkbox', 'change', function (_e, _ui) {
            fpcm.dom.fromTag('span.fpcm-ui-editor-metainfo-' + _ui.dataset.icon).toggleClass('fpcm-ui-status-1 fpcm-ui-status-0');
        });
        
    },
    
    _initCommentDialog: function(_url) {

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
    }
};