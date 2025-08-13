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

                            debugger;

                            var sParams = fpcm.dom.getValuesByClass('fpcm-files-search-input');
                            sParams.combinations = fpcm.dom.getValuesByClass('fpcm-ui-input-select-filessearch-combination');

                            fpcm.filemanager.startFilesSearch(sParams);
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
                        destination: fpcm.search._form.id,
                        group: 'shared'
                    });
                }
            });

            return false;
        });

    },

    startFilesSearch: function (sParams) {

        if (((new Date()).getTime() - fpcm.vars.jsvars.filesLastSearch) < 10000) {
            fpcm.ui.addMessage({
                type: 'error',
                txt : fpcm.ui.translate('SEARCH_WAITMSG')
            });
            return false;
        }

        fpcm.filemanager.reloadFiles(1, sParams);
    },

    _initSearchConditions: function () {

        let _cfg = fpcm.ui_dialogs.getConfig('search');
        if (!_cfg || !_cfg.fields) {
            return false;
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

        let _cIdx = 0;
        for (var _fieldCfg of _cfg.fields.buildFields) {

            _opts = {};
            _opts.index = fpcm.search._form.children.length;

            switch (_cIdx) {
                case 0:
                    _opts.colClass = ['col-auto'];
                    break;
                case 1:
                    _opts.colClass = ['col-3'];
                    break;
            }

            let _field = _fieldCfg;
            _field.bottomSpace = '';
            _field.data.ridx = _opts.index;

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
                _opts.index = _e.currentTarget.dataset.ridx;

                _valField.bottomSpace = '';

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
