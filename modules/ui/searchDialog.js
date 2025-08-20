/**
 * FanPress CM UI search dialog
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
export class searchDialog {

    _cfg = false;

    _lines = 0;

    _form = null;

    constructor(_config) {
        this._cfg = _config;
        this._initSearchConditions();
    }

    getRendered() {
        return this._form;
    }

    getFullId() {
        return this._form.id;
    }
    
    addNewCondition() {
        this._appendConditionRow();
    }
    
    reset() {
        this._lines = 0;
        this._form = null;
    }

    _initSearchConditions() {

        if (!this._cfg || !this._cfg.fields) {
            return false;
        }

        if (this._form) {
            return;
        }

        this._form = document.createElement('div');
        this._form.id = fpcm.ui.prepareId('search-fields', true);

        this._appendConditionRow();

    }

    _appendConditionRow() {

        if (!this._cfg || !this._cfg.fields) {
            return false;
        }

        let _row1 = document.createElement('div');
        _row1.classList.add('row', 'align-items-center', 'mb-2', 'g-0', 'gap-2');

        this._lines++;

        let _cIdx = 0;
        let _opts = {};

        for (var _fieldCfg of this._cfg.fields.buildFields) {

            let _field = _fieldCfg;

            _opts = {};

            _opts.idIndex = this._lines;

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

            this.assignEvents(_field);

            fpcm.ui_dialogs.appendField(_field, _row1, true, _opts);
            _cIdx++;
        }

        this._form.appendChild(_row1);
    }

    assignEvents(_field) {

        if (!this._cfg || !this._cfg.fields) {
            return false;
        }
        
        let _self = this;

        if (_field.name === 'btnCremove') {
            _field.onClick = function (_e) {

                if (_self._form.children.length < 2) {
                    return false;
                }

                _e.currentTarget.parentElement.parentElement.remove();
            }

            return;
        }

        if (_field.name === 'combinations') {

            _field.onChange = function (_e) {
                let _d = _e.currentTarget.value === ')';
                _e.currentTarget.parentElement.parentElement.nextElementSibling.getElementsByTagName('select').item(0).disabled = _d;
            };

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

                let _valField = _self._cfg.fields.valueFields[_e.currentTarget.value];
                if (!_valField) {
                    console.error(`Undefined search field index ${_e.currentTarget.value}`);
                    return false;
                }

                let _opts = {};
                _opts.idIndex = _self._lines;
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
            };

            return;
        }

    }
}