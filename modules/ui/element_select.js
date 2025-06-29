/**
 * FanPress CM UI input Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
export class element_select {

    name = '';

    id = '';

    type = '';

    value = '';

    class = 'form-select';

    wrapper = 'form-floating mb-3';

    label = '';

    disabled = false;

    labelIcon = false;

    options = [];

    preSelected = false;

    data = [];

    assignToDom(_destination) {

        if (!_destination) {
            console.warn('No element given to assign fpcm_ui_input to.');
            return false;
        }

        let _wrapper = document.createElement('div');
        _wrapper.className = this.wrapper;

        let _input = document.createElement('select');

        if (!this.name && !this.id) {
            console.error('An element of fpcm_ui_input requires at least a name or id');
            return false;
        }

        _input.name = this.name;

        if (!this.id) {
            this.id = this.name;
        }

        _input.id = 'fpcm-id-' + this.id;
        _input.value = this.value;
        _input.className = this.class;

        if (this.data) {
            for (var _attr in this.data) {
                _input.setAttribute('data-' + _attr, this.data[_attr]);
            }
        }

        if (this.disabled) {
            _input.disabled = true;
        }

        if (this.options) {

            if (!fpcm.vars.jsvars.search instanceof Object) {
                console.error('The options of fpcm_ui_select requires to be an Object or Array instance');
                return false;
            }


            for (var _opt in this.options) {
                let _val = this.options[_opt];
                _input.options.add( new Option(fpcm.ui.translate(_opt), _val, false, this.preSelected == _val) );
            }

        }

        _wrapper.appendChild(_input);

        if (this.label) {
            let _label = document.createElement('label');
            _label.htmlFor = _input.id;
            _label.className = 'fpcm ui-label';

            this.label = fpcm.ui.translate(this.label);

            if (this.labelIcon) {
                this.label = this.labelIcon.getString() + ' ' + fpcm.ui.translate(this.label);
            }

            _label.innerHTML = this.label;

            _wrapper.appendChild(_label);
        }

        _destination.appendChild(_wrapper);
    }

    assignFormObject(_field) {

        for (var _idx in this) {
            if (!_field[_idx]) {
                continue;
            }

            this[_idx] = _field[_idx];
        }
    }

}