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
        return fpcm.dom.fromTag('#' + _str.replace(/([^A-Za-z0-9\_\:\.\-])/gim, ''));
    },
    
    fromClass: function (_str)
    {
        return fpcm.dom.fromTag('.' + _str.replace(/([^\#\ \.\-\w\>\:\w\d])/gim, ''));
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
    
    getValuesFromIds: function (_elements)
    {
        if (typeof _elements === 'string' && _elements.substr(0,1) === '#') {
            return fpcm.dom.fromId(_elements).val();
        }

        if (typeof _elements === 'object' && _elements.length) {

            let _return = {};
            for (var i in _elements) {

                let _el = _elements[i];
                if (_el === undefined) {
                    continue;
                }

                _return[_el.replace(':', '_')] = fpcm.dom.fromId(_el).val();
            }

            return _return;
        }

        return null;
    },

    resetValuesByIdsString: function (_elements, _val)
    {
        if (typeof _elements !== 'object' || !_elements.length) {
            return false;
        }
        
        if (_val === undefined) {
            _val = '';
        }

        for (var i in _elements) {

            let _el = _elements[i];
            if (_el === undefined) {
                continue;
            }

            fpcm.dom.fromId(_el).val(_val);            
        }

        return true;
    },

    resetValuesByIdsChecked: function (_elements)
    {
        if (typeof _elements !== 'object' || !_elements.length) {
            return false;
        }

        for (var i in _elements) {

            let _el = _elements[i];
            if (_el === undefined) {
                continue;
            }
            
            fpcm.dom.fromId(_el).prop('checked', false).checkboxradio('refresh');
        }

        return true;
    },

    resetValuesByIdsSelect: function (_elements)
    {
        if (typeof _elements !== 'object' || !_elements.length) {
            return false;
        }

        for (var i in _elements) {

            let _el = _elements[i];
            if (_el === undefined) {
                continue;
            }

            fpcm.dom.fromId(_el).val('').prop('selectedIndex', 0).selectmenu('refresh');
        }

        return true;
    }

};