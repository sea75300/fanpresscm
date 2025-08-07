
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
        
        if (!fpcm.editor.initToolbar) {
            fpcm.ui.addMessage({
                txt: 'Failed to init editor toolbar, fpcm.editor.initToolbar not defined',
                type: 'error'
            });
            
            return false;
        }
        
        if (!fpcm.editor.initEditor) {
            fpcm.ui.addMessage({
                txt: 'Failed to init editor toolbar, fpcm.editor.initEditor not defined',
                type: 'error'
            });
            
            return false;
        }
        
        fpcm.editor.initToolbar();
        fpcm.editor.initEditor({
           elementId: fpcm.ui.prepareId('content-ace', true),
           textareaId: 'articlecontent',
           type: 'articletext'
        });
    },

    initAfter: function() {

        fpcm.ui.autocomplete('#articleimagepath', {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=editorfiles',
            minLength: 3
        });

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
    },
    
    onRefresh: function (_result) {
        
        if (!fpcm.vars.jsvars.articleId > 0 || !fpcm.editor.showInEditDialog) {
            return false;
        }
        
        fpcm.editor.showInEditDialog(_result);
    }

};
