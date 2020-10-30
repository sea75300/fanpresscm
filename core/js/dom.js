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
   
    fromId: function (_str)
    {
        return fpcm.dom.fromTag('#' + fpcm.dom.__encodeIdClass(_str));
    },
    
    fromClass: function (_str)
    {
        return fpcm.dom.fromTag('.' + fpcm.dom.__encodeIdClass(_str));
    },

    fromTag: function (_str)
    {
        
        if (!_str) {
            return false;
        }

        return jQuery(_str);
    },

    fromWindow: function ()
    {
        return fpcm.dom.fromTag(window);
    },
    
    setFocus: function(_id)
    {
        
        if (!fpcm.dom.fromId(_id).length) {
            console.warn('Set focus to undefined element: ' + _id);
            return false;
        }

        fpcm.dom.fromId(_id).focus();
    },
    
    assignHtml: function(_id, data)
    {
        
        if (!fpcm.dom.fromTag(_id).length) {
            console.warn('Assign html to undefined element: ' + _id);
            return false;
        }

        fpcm.dom.fromTag(_id).html(data);
    },
    
    assignText: function(_id, data)
    {
        
        if (!fpcm.dom.fromTag(_id).length) {
            console.warn('Assign text to undefined element: ' + _id);
            return false;
        }

        fpcm.dom.fromTag(_id).text(data);
    },
    
    appendHtml: function(_id, data)
    {
        
        if (!fpcm.dom.fromTag(_id).length) {
            console.warn('Appending html to undefined element: ' + _id);
            return false;
        }

        fpcm.dom.fromTag(_id).append(data);
    },
    
    prependHtml: function(_id, data)
    {
        
        if (!fpcm.dom.fromTag(_id).length) {
            console.warn('Prepend html to undefined element: ' + _id);
            return false;
        }

        fpcm.dom.fromTag(_id).prepend(data);
    },
    
    isReadonly: function(_id, state)
    {
        
        if (!fpcm.dom.fromTag(_id).length) {
            console.warn('Set property to undefined element: ' + _id);
            return false;
        }

        fpcm.dom.fromTag(_id).prop('readonly', state);
    },
    
    __encodeIdClass: function (_str)
    {
        return _str.replace(/([^\#\ \.\-\w\>\w\d])/gim);
    }

};