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
    
    getCheckboxCheckedValues: function(id) {

        var data = [];
        fpcm.dom.fromTag(id + ':checked').map(function (idx, item) {
            data.push(item.value);
        });

        return data;

    },

    getValuesByClass: function(_class, _indexed) {
        
        var _fields = fpcm.dom.fromClass(_class);
        if (!_fields.length) {
            return {};
        }

        _data = _indexed ? [] : {};
        _fields.map(function (idx, item) {

            var el = fpcm.dom.fromTag(item);
            if (_indexed) {
                _data.push(el.val());
                return true;
            }

            _data[el.attr('name')] = el.val();
            return true;
        });     

        return _data;
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

    resetValuesByIdsChecked: function (_elements, _value)
    {
        if (_value === undefined) {
            _value = false;
        }

        if (typeof _elements !== 'object' || !_elements.length) {
            return false;
        }

        for (var i in _elements) {

            let _el = _elements[i];
            if (_el === undefined) {
                continue;
            }
            
            fpcm.dom.fromId(_el).prop('checked', _value);
        }

        return true;
    },

    resetValuesByIdsSelect: function (_elements, _value)
    {
        if (_value === undefined) {
            _value = 0;
        }

        if (typeof _elements !== 'object' || !_elements.length) {
            return false;
        }

        for (var i in _elements) {

            let _el = _elements[i];
            if (_el === undefined) {
                continue;
            }

            fpcm.dom.fromId(_el).val('').prop('selectedIndex', _value);
        }

        return true;
    },
    
    resetCheckboxesByClass: function(_class, _value) {
        
        if (_value === undefined) {
            _value = false;
        }
        
        fpcm.dom.fromClass(_class).prop('checked', _value);
        return true;
    },
    
    findElementInDialogFrame: function (_root, _element, _frame)
    {
        
        if (!_frame) {
            _frame = 0;
        }
        
        return fpcm.dom.fromTag(_root._dialog.getElementsByTagName('iframe')[_frame]).contents().find(_element);
    },
    
    bindEvent: function (_element, _ob, _callback, _unbind)
    {
        if (_unbind === true || _unbind === undefined) {
            fpcm.dom.fromTag(_element).unbind(_ob);
        }

        fpcm.dom.fromTag(_element).bind(_ob, function (_event, _selecto, _data, _handler) {

            if (_handler === undefined) {
                _handler = false;
            }
            
            return _callback(_event, this, _selecto, _data, _handler);
        });
    },
    
    bindClick: function (_element, _callback)
    {
        fpcm.dom.bindEvent(_element, 'click', _callback);
    }

};