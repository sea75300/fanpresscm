/**
 * FanPress CM UI icon Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
export class element_button {

    name = '';

    id = '';

    type = '';

    class = 'btn';

    label = '';

    readonly = false;

    icon = false;

    iconOnly = false;

    text = '';

    primary = false;

    assignToDom(_destination) {

        debugger;

        if (!_destination) {
            console.warn('No element given to assign fpcm_ui_button to.');
            return false;
        }

        if (!this.name && !this.id) {
            console.warn('An element of fpcm_ui_input requires at least a name or id');
            return false;
        }

        let _button = document.createElement('button');
        _button.name = this.name;
        _button.id = this.id;
        _button.className = this.class;

        if (this.readonly) {
            _button.readonly = true;
        }

        if (this.text) {
            this.text = fpcm.ui.translate(this.text);
        }

        if (this.icon) {
            let _ti = this.icon.split(' ');
            let _icon = new fpcm.ui.forms.icon(_ti[2].replace('fa-', ''), _ti[1].replace('fa-', ''));

            if (this.iconOnly) {
                this.text = _icon.getString();
            }
            else {

                this.text = _icon.getString() + this.text;
            }
        }

        _button.innerHTML = this.text;

        if (this.iconOnly) {
            _destination.appendChild(_button);
            return;
        }

        if (this.text) {
            let _labelSpan = document.createElement('span');
            _labelSpan.classList.add('fpcm-ui-label', 'ps-1');
            _labelSpan.innerHTML = this.text;
            _button.appendChild(_labelSpan);
        }

        _destination.appendChild(_button);

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