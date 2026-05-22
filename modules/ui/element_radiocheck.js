/**
 * FanPress CM UI input Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
export class element_radiocheck {
    
    name = '';

    id = '';

    type = '';
    
    value = '';
    
    class = 'form-control';
    
    wrapperClass = 'form-check ';

    label = '';
    
    readonly = false;
    
    required = false;
    
    switch = false;
    
    selected = false;

    assignToDom(_destination) {
        
        if (!_destination) {
            console.warn('No element given to assign fpcm_ui_input to.');
            return false;            
        }

        let _wrapper = document.createElement('div');
        
        if (this.switch) {
            this.wrapperClass += ' form-switch';
        }
        
        _wrapper.className = this.wrapperClass;              
        
        let _input = document.createElement('input');
        
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
        
        if (this.type) {
            _input.type = this.type;
        }
        
        if (this.required) {
            _input.required = this.required;
        }
        
        if (this.selected) {
            _input.checked = true;
        }

        _wrapper.appendChild(_input);

        if (this.label) {

            let _label = document.createElement('label');
            _label.htmlFor = _input.id;
            _label.className = 'fpcm ui-label';

            this.label = fpcm.ui.translate(this.label); 

            let _labelSpan = document.createElement('span');
            _labelSpan.classList.add('fpcm-ui-label', 'ps-1');
            _labelSpan.innerHTML = this.label;
            
            _label.appendChild(_labelSpan);

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