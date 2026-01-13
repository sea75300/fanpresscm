/**
 * FanPress CM Comments Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.search = {

    _dlg: false,

    init: function () {

        if (!fpcm.ui.langvarExists('ARTICLES_SEARCH')) {
            return;
        }

        if (!fpcm.search._dlg) {
            fpcm.search._dlg = new fpcm.ui.forms.searchDialog(fpcm.ui_dialogs.getConfig('search'));
        }

        fpcm.dom.bindClick('#btnOpensearch', function ()
        {
            fpcm.ui_dialogs.search(
                'coments',
                function(_ui, _bsObj) {

                    if (((new Date()).getTime() - fpcm.vars.jsvars.commentsLastSearch) < 10000) {
                        fpcm.ui.addMessage({
                            type: 'error',
                            txt : fpcm.ui.translate('SEARCH_WAITMSG')
                        });
                        return false;
                    }

                    let _filter = fpcm.search._dlg.getValues();
                    if (_filter === null) {
                        return;
                    }

                    fpcm.comments.loadItems({
                        filter: _filter
                    });
                },
                function() {
                    fpcm.ui.relocate('self');
                },
                function () {
                    fpcm.ui.autocomplete('#articleId', {
                        source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
                        minLength: 3
                    });
                }
            );
        });
    }
};

fpcm.search.callbacks = {
    
    articleid: function (_el) {

        let _id = '#' + fpcm.ui.prepareId(_el.value, true) + _el.dataset.ridx;

        fpcm.ui.autocomplete(_id, {
            source: fpcm.vars.ajaxActionPath + 'autocomplete&src=articles',
            minLength: 3
        });

    }
    
};