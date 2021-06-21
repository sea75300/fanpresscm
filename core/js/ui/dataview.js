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

        var spinner     = fpcm.dom.fromId('fpcm-dataview-' + id + '-spinner');
        var obj         = fpcm.vars.jsvars.dataviews[id];
        var style       = '';

        obj.fullId      = fpcm.dataview.getFullId(id);
        obj.headId      = obj.fullId + '-head';
        obj.rowsId      = obj.fullId + '-rows';

        obj.wrapper     = fpcm.dom.fromId(obj.fullId).addClass('fpcm-ui-dataview');
        obj.wrapper.append('<div class="row fpcm-ui-dataview-head" id="' + obj.headId + '"></div>');
        obj.wrapper.append('<div class="fpcm-ui-dataview-rows" id="' + obj.rowsId + '"></div>');
        
        obj.headline    = fpcm.dom.fromId(obj.headId);
        obj.lines       = fpcm.dom.fromId(obj.rowsId);

        jQuery.each(obj.columns, function (index, column) {
            style = 'fpcm-ui-dataview-col ' + column.class + fpcm.dataview.getAlignString(column.align) + ' align-self-center py-0 py-md-2 ' + fpcm.dataview.getSizeString(column);
            obj.headline.append('<div class="' + style + '" id="' + obj.fullId + '-dataview-headcol-' + column.name + index + '">' + (column.descr ? fpcm.ui.translate(column.descr) : '&nbsp;') + '</div>');            
        });

        jQuery.each(obj.rows, function (index, row) {
            fpcm.dataview.addRow(obj.fullId, index, row, obj);
        });
        
        if (typeof params.onRenderAfter === 'function') {
            params.onRenderAfter.call();
        }
        
        fpcm.vars.jsvars.dataviews[id].dataViewHeight = fpcm.dom.fromId(obj.fullId).height() + 'px';

        if (spinner) {
            spinner.remove();
        }
    },

    addRow: function(id, index, row, obj) {

        if (!fpcm.vars.jsvars.dataviews) {
            return;
        }

        var rowId           = id + '-dataview-row-' + index;
        var baseclass       = row.isheadline ? 'fpcm-ui-dataview-subhead' : 'fpcm-ui-dataview-row fpcm-ui-background-transition';
        baseclass          += row.isNotFound ? ' fpcm-ui-dataview-notfound' : '';
        var isNotFound      = row.isNotFound;

        row.class           = baseclass + (row.class ? ' ' + row.class : '');

        obj.lines.append('<div class="row py-2 ' + row.class + '" id="' + rowId + '"></span>');

        jQuery.each(row.columns, function (index, rowCol) {

            var rowColumn   = obj.columns[index] ? obj.columns[index] : false;
            if (!rowColumn) {
                return;
            }
            
            if (!rowColumn.size) {
                rowColumn.size = 'auto';
            }

            var colId = rowId + '-dataview-rowcol-' + rowCol.name + index;
            console.log(colId);
            console.log(rowColumn);


            var style       = 'fpcm-ui-dataview-col ' 
                            + rowColumn.class 
                            + fpcm.dataview.getAlignString(rowColumn.align)
                            + (isNotFound ? ' col col-12' : fpcm.dataview.getSizeString(rowColumn) )
                            + ' fpcm-ui-dataview-type' 
                            + rowCol.type + ' align-self-center'
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
    
    getAlignString: function(_align) {
        
        switch (_align) {
            case 'left' :
                _align = 'start';
                break;
            case 'right' :
                _align = 'end';
                break;
        }

        return ' text-' + _align;
        
    },
    
    getSizeString: function(item) {

        if (!item.size) {
            return 'col';
        }
        
        return ' fpcm-ui-dataview-size-' + item.size + ' col-12 col-lg-' + item.size;  
    },

    exists: function(id) {
        
        if (!fpcm.vars.jsvars.dataviews[id]) {
            return false;
        }

        return true;
    }

};