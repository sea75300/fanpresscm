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

    _cfg: false,
    _lines: 0,
    _form: null,

    init: function() {

        if (!fpcm.ui.langvarExists('ARTICLES_SEARCH')) {
            return false;
        }

        fpcm.dom.bindClick('#btnOpenSearch', function () {

            fpcm.search._initSearchConditions();

            fpcm.ui_dialogs.create({
                id: 'files-search',
                title: 'ARTICLES_SEARCH',
                closeButton: true,
                directAssignToDom: true,
                content: fpcm.search._form,
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_ADD'),
                        icon: "plus",
                        showLabel: false,
                        click: function(_ui, _bsObj) {
                            fpcm.search._appendConditionRow();
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
                            fpcm.search._lines = 0;
                            fpcm.search._form = null;
                            fpcm.filemanager.reloadFiles(1);
                        }
                    }
                ],
                dlOnOpenAfter: function () {
                    fpcm.ui_dnd.initDnd({
                        destination: fpcm.search._form.id,
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

    },

    _initSearchConditions: function () {

        let _cfg = fpcm.ui_dialogs.getConfig('search');
        if (!_cfg || !_cfg.fields) {
            return false;
        }

        if (fpcm.search._form) {
            return;
        }

        fpcm.search._form = document.createElement('div');
        fpcm.search._form.id = fpcm.ui.prepareId('search-fields', true);

        fpcm.search._appendConditionRow();

    },

    _appendConditionRow: function () {

        let _cfg = fpcm.ui_dialogs.getConfig('search');
        if (!_cfg || !_cfg.fields) {
            return false;
        }

        let _row1 = document.createElement('div');
        _row1.classList.add('row', 'align-items-center', 'mb-2', 'g-0', 'gap-2');

        fpcm.search._lines++;

        let _cIdx = 0;
        for (var _fieldCfg of _cfg.fields.buildFields) {

            let _field = _fieldCfg;

            _opts = {};
            _opts.idIndex = fpcm.search._lines;
            
            switch (_cIdx) {
                case 0:
                    _opts.colClass = ['col-auto'];
                    break;
                case 1:
                    _opts.colClass = ['col-3'];
                    _opts.namePattern = `searchData`;
                    _field.data.type = 'combination';
                    
                    break;
                default:
                    _opts.namePattern = `searchData`;
                    _field.data.type = 'field';
                    break;
            }

            _field.bottomSpace = '';
            _field.data.ridx = _opts.idIndex;

            fpcm.search.assignEvents(_field);

            fpcm.ui_dialogs.appendField(_field, _row1, true, _opts);
            _cIdx++;
        }

        fpcm.search._form.appendChild(_row1);
    },

    assignEvents: function (_field) {


        let _cfg = fpcm.ui_dialogs.getConfig('search');
        if (!_cfg || !_cfg.fields) {
            return false;
        }

        if (_field.name === 'btnCremove') {
            _field.onClick = function (_e) {

                if (fpcm.search._form.children.length < 2) {
                    return false;
                }

                _e.currentTarget.parentElement.parentElement.remove();
            }

            return;
        }

        if (_field.name === 'combinations') {
            _field.onChange = function (_e) {
                let _d = /*_e.currentTarget.value === '(' || */_e.currentTarget.value === ')';
                _e.currentTarget.parentElement.parentElement.nextElementSibling.getElementsByTagName('select').item(0).disabled = _d;
            }

            return;
        }

        if (_field.name === 'fields') {
            _field.onChange = function (_e) {

                if (_e.currentTarget.parentElement.parentElement.parentElement.children.length > 3) {
                    _e.currentTarget.parentElement.parentElement.parentElement.children.item(3).remove();
                }

                if (!_e.currentTarget.value) {
                    return false;
                }

                let _valField = _cfg.fields.valueFields[_e.currentTarget.value];
                if (!_valField) {
                    console.error(`Undefined search field index ${_e.currentTarget.value}`);
                    return false;
                }

                _opts = {};
                _opts.idIndex = fpcm.search._lines;
                _opts.namePattern = `searchData`;

                _valField.bottomSpace = '';
                _valField.data.type = 'value';
                _valField.data.ridx = _e.currentTarget.dataset.ridx;

                fpcm.ui_dialogs.appendField(
                    _valField,
                    _e.currentTarget.parentElement.parentElement.parentElement,
                    true,
                    _opts
                );
            }

            return;
        }

    }
};
