/**
 * FanPress CM UI input Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
export class element_textarea {
    
    name = '';

    id = '';
    
    value = '';
    
    class = 'form-control fpcm ui-textarea-medium';
    
    wrapper = 'form-floating mb-3';
    
    placeholder = '';

    label = '';
    
    readonly = false;
    
    autofocus = false;
    
    labelIcon = false;
    
    required = false;

    assignToDom(_destination) {
        
        if (!_destination) {
            console.warn('No element given to assign fpcm_ui_input to.');
            return false;            
        }

        let _wrapper = document.createElement('div');
        _wrapper.className = this.wrapper;              
        
        let _input = document.createElement('textarea');
        
        if (!this.name && !this.id) {
            console.warn('An element of fpcm_ui_input requires at least a name or id');
            return false;
        }
        
        _input.name = this.name;
        
        if (!this.id) {
            this.id = this.name;
        }
        
        _input.id = 'fpcm-id-' + this.id;
        _input.value = this.value;
        _input.className = this.class;
        
        if (this.readonly) {
            _input.readonly = true;
        }
        
        if (this.autofocus) {
            _input.autofocus = true;
        }
        
        if (this.placeholder) {
            _input.placeholder = fpcm.ui.translate(this.placeholder);
        }
        
        if (this.required) {
            _input.required = this.required;
        }
        
        _wrapper.appendChild(_input);

        if (this.label) {
            let _label = document.createElement('label');
            _label.htmlFor = _input.id;
            _label.className = 'fpcm ui-label';

            this.label = fpcm.ui.translate(this.label);

            if (this.labelIcon) {
                this.label = this.labelIcon.getString() + ' ' + this.label;
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