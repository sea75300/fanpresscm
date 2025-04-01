/**
 * FanPress CM UI message namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
export class message {  
    
    type = '';
            
    icon = 'info-circle';

    id = '';

    txt = '';

    constructor(_type, _txt) {
        this.type = _type;
        this.txt = _txt;
    }

}