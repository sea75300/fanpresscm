/**
 * FanPress CM DOM Wrapper functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.dom = {
   
    fromId: function (_str) {
        return fpcm.dom.fromTag('#' + _str);
    },
    
    fromClass: function (_str) {
        return fpcm.dom.fromTag('.' + _str);
    },

    fromTag: function (_str) {
        
        if (!_str) {
            return false;
        }

        return jQuery(_str);
    },

    fromWindow: function () {
        return fpcm.dom.fromTag(window);
    },
    
    setFocus: function(_id) {
        fpcm.dom.fromTag(_id).focus();
    },
    
    assignHtml: function(_id, data) {
        fpcm.dom.fromTag(_id).html(data);
    },
    
    assignText: function(_id, data) {
        fpcm.dom.fromTag(_id).text(data);
    },
    
    appendHtml: function(_id, data) {
        fpcm.dom.fromTag(_id).append(data);
    },
    
    prependHtml: function(_id, data) {
        fpcm.dom.fromTag(_id).prepend(data);
    },
    
    isReadonly: function(_id, state) {
        fpcm.dom.fromTag(_id).prop('readonly', state);
    }

};