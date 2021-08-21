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

        obj.wrapper     = fpcm.dom.fromId(obj.fullId).addClass('fpcm-ui-dataview');
        obj.wrapper.append('<div class="row bg-primary bg-gradient text-light py-1" id="' + obj.headId + '"></div>');
        obj.wrapper.append('<div class="fpcm-ui-dataview-rows" id="' + obj.rowsId + '"></div>');
        
        obj.headline    = fpcm.dom.fromId(obj.headId);
        obj.lines       = fpcm.dom.fromId(obj.rowsId);

        jQuery.each(obj.columns, function (index, column) {
            let _style = (column.class ? column.class + ' ' : '') + fpcm.dataview.getAlignString(column.align, 'md', 'text-center') + 
                     ' align-self-center py-0 py-md-1 ' + 
                     fpcm.dataview.getSizeString(column) +
                     (!column.descr ? ' d-none d-lg-block' : '');

            obj.headline.append('<div class="' + _style + '" id="' + obj.fullId + '-dataview-headcol-' + column.name + index + '">' + (column.descr ? fpcm.ui.translate(column.descr) : '&nbsp;') + '</div>');            
        });

        jQuery.each(obj.rows, function (index, row) {
            fpcm.dataview.addRow(obj.fullId, index, row, obj);
        });
        
        if (typeof params.onRenderAfter === 'function') {
            params.onRenderAfter.call();
        }
        
        fpcm.vars.jsvars.dataviews[id].dataViewHeight = fpcm.dom.fromId(obj.fullId).height() + 'px';
        fpcm.dom.fromId(obj.fullId).find('div.row.placeholder-wave').remove();
        
    },

    addRow: function(id, index, row, obj) {

        if (!fpcm.vars.jsvars.dataviews) {
            return;
        }

        var _notFound       = row.isNotFound === true ? true : false;

        var rowId           = id + '-dataview-row-' + index;
        var baseclass       = row.isheadline ? 'fpcm-ui-dataview-subhead bg-secondary bg-gradient text-light' : 'fpcm-ui-background-transition';
        baseclass          += _notFound ? ' fpcm-ui-dataview-notfound' : '';

        row.class           = baseclass + (row.class ? ' ' + row.class : '');

        obj.lines.append('<div class="row py-2 border-bottom border-secondary ' + row.class + '" id="' + rowId + '"></span>');

        jQuery.each(row.columns, function (index, rowCol) {

            var rowColumn   = obj.columns[index] ? obj.columns[index] : false;
            if (!rowColumn) {
                return;
            }
            
            if (!rowColumn.size) {
                rowColumn.size = 'auto';
            }

            var colId = rowId + '-dataview-rowcol-' + rowCol.name + index;

            var style       = ( rowColumn.class ? rowColumn.class + ' ' : '') 
                            + ( _notFound === true ? ' text-start' : fpcm.dataview.getAlignString(rowColumn.align) )
                            + ( _notFound === true ? ' col' : fpcm.dataview.getSizeString(rowColumn) )
                            + ' fpcm-ui-dataview-type' 
                            + rowCol.type + ' align-self-center my-1'
                            + (rowCol.class ? ' ' + rowCol.class : '');

            var valueStr    = ( rowCol.type == fpcm.vars.jsvars.dataviews.rolColTypes.coltypeValue
                            ? '<div class="fpcm-ui-dataview-col-value">' + (rowCol.value !== '' ? fpcm.ui.translate(rowCol.value) : '&nbsp;') + '</div>'
                            : (rowCol.value !== '' ? fpcm.ui.translate(rowCol.value) : '&nbsp;') );

            fpcm.dom.appendHtml('#' + rowId, '<div class="' + style + '" id="' + colId + '">' + valueStr + '</div>');
            
        });
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
    }

};