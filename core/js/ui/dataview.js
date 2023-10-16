/**
 * FanPress CM Data View Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.dataview = {

    _baseItem: {},

    render: function (id, params) {

        if (!params) {
            params = {};
        }

        if (!fpcm.vars.jsvars.dataviews) {
            return;
        }

        if (!fpcm.dataview.exists(id)) {
            console.log('Dataview ' + id + ' does not exists in fpcm.vars.jsvars.dataviews!');
            return false;
        }

        if (!fpcm.vars.jsvars.dataviews[id].init) {
            return false;
        }

        if (!fpcm.vars.jsvars.dataviews[id].columns.length) {
            console.log('Dataview ' + id + ' does not contain any columns!');
            return false;
        }

        var obj         = fpcm.vars.jsvars.dataviews[id];
        var style       = '';

        obj.fullId      = fpcm.dataview.getFullId(id);
        obj.headId      = obj.fullId + '-head';
        obj.rowsId      = obj.fullId + '-rows';

        fpcm.dataview._baseItem = document.getElementById(obj.fullId);   
        if (!fpcm.dataview._baseItem) {
            return false;
        }

        fpcm.dataview._createHead(obj, obj.columns);
        fpcm.dataview._createRows(obj);

        if (typeof params.onRenderAfter === 'function') {
            params.onRenderAfter.call();
        }
        
        fpcm.ui.assignCheckboxes();
        
        let _placeholder = fpcm.dataview._baseItem.querySelectorAll('div.row.placeholder-wave');
        for (var _i = 0; _i < _placeholder.length; _i++) {
            _placeholder[_i].remove();
        }

    },
    
    updateAndRender: function (id, params) {

        if (!fpcm.vars.jsvars.dataviews || !fpcm.dataview.exists(id)) {
            return;
        }

        fpcm.dom.fromId(fpcm.dataview.getFullId(id)).empty();
        fpcm.dataview.render(id, params);
    },
    
    getDataViewWrapper: function (id, _class) {

        if (!_class) {
            _class = 'fpcm-ui-dataview-wrapper';
        }

        return '<div id="'+ fpcm.dataview.getFullId(id) +'" class="' + _class + '"></div>';
    },

    getFullId: function (id) {
        return 'fpcm-dataview-'+ id;
    },
    
    getAlignString: function(_align, _prefix, _preprend) {
        
        if (_prefix === undefined) {
            _prefix = '';
        }
        else {
            _prefix = _prefix + '-';
        }
        
        if (_preprend === undefined) {
            _preprend = '';
        }
        
        switch (_align) {
            case 'left' :
                _align = 'start';
                break;
            case 'right' :
                _align = 'end';
                break;
        }

        return _preprend + ' text-' + _prefix + _align;
        
    },
    
    getSizeString: function(item) {

        if (!item.size) {
            return 'col';
        }
        
        return ' col-12 col-lg-' + item.size;  
    },

    exists: function(id) {
        
        if (!fpcm.vars.jsvars.dataviews[id]) {
            return false;
        }

        return true;
    },
    
    _createHead: function (_cfg) {
        
        let _el = document.createElement('div');
        _el.classList.add('row');
        
        if (fpcm.ui.darkModeEnabled()) {
            _el.classList.add('bg-primary-subtle');
        }
        else  {
            _el.classList.add('text-bg-primary');
        }
        
        _el.classList.add('py-1');
        _el.classList.add('fpcm');
        _el.classList.add('ui-dataview-head');
        _el.id = _cfg.headId;

        for (var _i in _cfg.columns) {
            
            let _col = _cfg.columns[_i];
            
            let _style = (_col.class ? _col.class + ' ' : '') + fpcm.dataview.getAlignString(_col.align, 'md', 'text-center') + 
                     ' align-self-center py-0 py-md-1 ' + 
                     fpcm.dataview.getSizeString(_col) +
                     (!_col.descr ? ' d-none d-lg-block' : '');

            let _colEl = document.createElement('div');
            
            _colEl.id = _cfg.fullId + '-dataview-headcol-' + _col.name + _i;
            _colEl.innerHTML = (_col.descr ? fpcm.ui.translate(_col.descr) : '&nbsp;');
            fpcm.dataview._assignStyles(_style, _colEl);
            
            _el.appendChild(_colEl);
            _colEl = null;
        }        
        
        fpcm.dataview._baseItem.appendChild(_el);
    },
    
    _createRows: function (_cfg) {
        
        let _el = document.createElement('div');
        _el.classList.add('fpcm');
        _el.classList.add('ui-dataview-rows');
        _el.id = _cfg.rowsId;
        
        for (var _i in _cfg.rows) {
            fpcm.dataview._addRow(_i, _cfg.rows[_i], _cfg, _el);
        }

        
        fpcm.dataview._baseItem.appendChild(_el);
    },
    
    _addRow: function(index, row, obj, _domEl) {

        var _notFound       = row.isNotFound === true ? true : false;

        var rowId           = obj.fullId + '-dataview-row-' + index;
        var baseclass       = row.isheadline ? 'fpcm-ui-dataview-subhead bg-dark-subtle' : 'fpcm ui-background-transition';
        baseclass          += _notFound ? ' fpcm-ui-dataview-notfound' : '';

        row.class           = baseclass + (row.class ? ' ' + row.class : '');

        let _rowEl = document.createElement('div');
        _rowEl.id = rowId;

        _rowEl.classList.add('row');
        _rowEl.classList.add('py-2');
        _rowEl.classList.add('border-bottom');
        _rowEl.classList.add('border-2');
        _rowEl.classList.add('border-secondary');
        _rowEl.classList.add('border-opacity-50');        
        fpcm.dataview._assignStyles(row.class, _rowEl);

        for (var _i in row.columns) {
            
            var _colMeta   = obj.columns[_i] ? obj.columns[_i] : false;
            if (!_colMeta) {
                return;
            }
            
            let _colData = row.columns[_i];
            
            if (!_colMeta.size) {
                _colMeta.size = 'auto';
            }

            var colId = rowId + '-dataview-rowcol-' + _colData.name + index;

            var style       = ( _colMeta.class ? _colMeta.class + ' ' : '') 
                            + ( _notFound === true ? ' text-start' : fpcm.dataview.getAlignString(_colMeta.align) )
                            + ( _notFound === true ? ' col' : fpcm.dataview.getSizeString(_colMeta) )
                            + ' fpcm-ui-dataview-type' 
                            + _colData.type + ' align-self-center my-1'
                            + (_colData.class ? ' ' + _colData.class : '');

            var valueStr    = ( _colData.type == fpcm.vars.jsvars.dataviews.rolColTypes.coltypeValue
                            ? '<div class="fpcm-ui-dataview-col-value">' + (_colData.value !== '' ? fpcm.ui.translate(_colData.value) : '&nbsp;') + '</div>'
                            : (_colData.value !== '' ? fpcm.ui.translate(_colData.value) : '&nbsp;') );   

            let _colEl = document.createElement('div');
            fpcm.dataview._assignStyles(style, _colEl);

            _colEl.id = colId;
            _colEl.innerHTML = valueStr;
            _rowEl.appendChild(_colEl);

            _colEl = null;
        }

        _domEl.appendChild(_rowEl);
    },    
    
    _assignStyles: function (_style, _element) {

        if (!_style) {
            return false;
        }

        _style = _style.split(' ');
        for (var _x in _style) {

            if (!_style[_x]) {
                continue;
            }

            _element.classList.add(_style[_x]);                
        }

        return true;
    }

};