/**
 * FanPress CM Filemanager search Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.search = {

    _dlg: false,

    init: function() {

        if (!fpcm.ui.langvarExists('ARTICLES_SEARCH')) {
            return false;
        }

        fpcm.dom.bindClick('#btnOpenSearch', function () {

            if (!fpcm.search._dlg) {
                fpcm.search._dlg = new fpcm.ui.forms.searchDialog(fpcm.ui_dialogs.getConfig('search'));
            }

            fpcm.ui_dialogs.create({
                id: 'files-search',
                title: 'ARTICLES_SEARCH',
                closeButton: true,
                directAssignToDom: true,
                content: fpcm.search._dlg.getRendered(),
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_ADD'),
                        icon: "plus",
                        showLabel: false,
                        click: function(_ui, _bsObj) {
                            fpcm.search._dlg.addNewCondition();
                        }
                    },
                    {
                        text: fpcm.ui.translate('ARTICLE_SEARCH_START'),
                        icon: "check",
                        primary: true,
                        clickClose: true,
                        click: function(_ui, _bsObj) {

                            if (((new Date()).getTime() - fpcm.vars.jsvars.filesLastSearch) < 10000) {
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

                            fpcm.filemanager.reloadFiles(1, _filter);
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_RESET'),
                        icon: "filter-circle-xmark" ,
                        clickClose: true,
                        click: function() {
                            fpcm.search._dlg.reset();
                            fpcm.filemanager.reloadFiles(1);
                        }
                    }
                ],
                dlOnOpenAfter: function () {
                    fpcm.ui_dnd.initDnd({
                        destination: fpcm.search._dlg.getFullId(),
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
