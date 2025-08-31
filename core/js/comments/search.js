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

        fpcm.dom.bindClick('#btnOpensearch', function () {

            if (!fpcm.search._dlg) {
                fpcm.search._dlg = new fpcm.ui.forms.searchDialog(fpcm.ui_dialogs.getConfig('search'));
            }

            fpcm.ui_dialogs.create({
                id: 'comments-search',
                title: 'ARTICLES_SEARCH',
                closeButton: true,
                directAssignToDom: true,
                content: fpcm.search._dlg.getRendered(),
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_ADD'),
                        icon: "plus",
                        class: 'btn-success',
                        showLabel: false,
                        isLeft: true,
                        click: function(_ui, _bsObj) {
                            fpcm.search._dlg.addNewCondition();
                        }
                    },
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                        icon: "search",
                        clickClose: true,
                        class: 'btn-primary',
                        click: function(_ui, _bsObj) {

                            if (((new Date()).getTime() - fpcm.vars.jsvars.commentsLastSearch) < 10000) {
                                fpcm.ui.addMessage({
                                    type: 'error',
                                    txt : fpcm.ui.translate('SEARCH_WAITMSG')
                                });
                                return false;
                            }

                            let _sfields = document.getElementsByName('searchData');
                            if (!_sfields.length) {
                                return false;
                            }

                            let _filter = {};

                            for (let _svi of _sfields) {

                                if (_filter[_svi.dataset.ridx] === undefined) {
                                    _filter[_svi.dataset.ridx] = {
                                        combination: '',
                                        field: null,
                                        value: null
                                    };
                                }

                                _filter[_svi.dataset.ridx][_svi.dataset.type] = _svi.value;
                            }

                            let _sorts = document.getElementsByName('sorts');
                            if (_sorts.length) {
                                _filter.sort = {};
                                for (let _sort of _sorts) {
                                    _filter.sort[_sort.dataset.option] = _sort.value;
                                }
                            }

                            fpcm.comments.startCommentSearch({
                                mode: fpcm.vars.jsvars.articleSearchMode,
                                filter: _filter
                            });
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_RESET'),
                        icon: "filter-circle-xmark" ,
                        clickClose: true,
                        click: function() {
                            fpcm.ui.relocate('self');
                        }
                    }
                ],
                dlOnOpenAfter: function () {
                    fpcm.ui_dnd.initDnd({
                        destination: fpcm.search._dlg.getFullListId(),
                        group: 'shared',
                        dropCallback: function (_e) {

                            let _rows =  _e.to.children;
                            if (!_rows.length) {
                                return;
                            }

                            let _ridx = 0;
                            for (var _row of _rows) {

                                let _list = _row.querySelectorAll('[data-ridx]');
                                if (!_list.length) {
                                    return;
                                }

                                for (var _el of _list) {
                                    _el.dataset.ridx = _ridx;
                                }

                                _ridx++;
                            }
                        }
                    });
                }
            });

            return false;
        });
    }

};