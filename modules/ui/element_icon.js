/**
 * FanPress CM UI icon Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
export class element_icon {  
    
    icon = '';
    
    spinner = '';

    size = '';

    stack = '';
    
    prefix = 'fa';

    collection = 'fa';

    base = 'fa';

    width = 'fw';

    stackTop = false;

    iconClass = '';
    
    stackClass = '';

    getString() {
        
        let _bottomStack = '';
        let _topStack = '';
        let _stackIconSize = '';
        let _size = '';
        let _spinner = '';

        if (this.size) {
            _size = this.prefix + '-' + this.size;
        }
        
        if (this.spinner) {
            _spinner = this.prefix + '-' + this.spinner;
        }

        
        if (this.stack) {
            let _stackStr = `<span class="${this.base} ${this.prefix}-${this.stack} ${this.prefix}-stack-2x ${this.stackClass}"></span>`;
            _stackIconSize = this.prefix + '-stack-1x';
            
            if (this.stackTop) {
                _topStack = `<span class="${this.base} ${this.prefix}-stack ${_size}">${_stackStr}`;
                _bottomStack = '</span>';
            }
            else {
                _topStack = `<span class="${this.prefix}-stack">`;
                _bottomStack = _stackStr + '</span>';
            }            
            
            _size = '';
        }
        
        return `${_topStack}<span class="fpcm-ui-icon ${this.collection} ${this.prefix}-${this.icon} ${this.prefix}-${this.width} ${this.iconClass} ${_size} ${_spinner} ${_stackIconSize}"></span>${_bottomStack}`;
        
    }


    assignToDom(_destination) {

    }

}