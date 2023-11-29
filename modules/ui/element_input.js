/**
 * FanPress CM UI input Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
export class element_input {
    
    name = '';

    id = '';

    type = '';
    
    value = '';
    
    class = 'form-control';
    
    wrapper = 'form-floating mb-3';
    
    placeholder = '';

    label = '';

    pattern = '';

    maxlenght = 255;

    max = '';

    min = '';
    
    readonly = false;
    
    autofocus = false;

    assignToDom(_destination) {
        
        if (!_destination) {
            console.warn('No element given to assign fpcm_ui_input to.');
            return false;            
        }

        let _wrapper = document.createElement('div');
        _wrapper.className = this.wrapper;              
        
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
        
        if (this.maxlenght) {
            _input.maxLength = this.maxlenght;
        }
        
        if (this.pattern) {
            _input.pattern = this.pattern;
        }
        
        if (this.readonly) {
            _input.readonly = true;
        }
        
        if (this.autofocus) {
            _input.autofocus = true;
        }
        
        if (this.placeholder) {
            _input.placeholder = this.placeholder;
        }
        
        if (this.max) {
            _input.max = this.max;
        }
        
        if (this.min) {
            _input.min = this.min;
        }
        
        _wrapper.appendChild(_input);
        

        let _label = document.createElement('label');
        _label.htmlFor = _input.id;
        _label.className = 'fpcm ui-label';
        _label.innerHTML = this.label;
        
        _wrapper.appendChild(_label);
        
        _destination.appendChild(_wrapper);

        delete(_label, _input, _wrapper);
        
    }

}