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
        obj.wrapper.append('<div class="row fpcm-ui-dataview-head fpcm-ui-dataview-rowcolpadding ui-widget-header ui-corner-all ui-helper-reset" id="' + obj.headId + '"></div>');
        obj.wrapper.append('<div class="fpcm-ui-dataview-rows" id="' + obj.rowsId + '"></div>');
        
        obj.headline    = fpcm.dom.fromId(obj.headId);
        obj.lines       = fpcm.dom.fromId(obj.rowsId);

        jQuery.each(obj.columns, function (index, column) {
            style = 'fpcm-ui-padding-none-lr fpcm-ui-dataview-col ' + column.class + ' fpcm-ui-dataview-align-' + column.align + ' col align-self-center ' + (column.size ? ' fpcm-ui-dataview-size-' + column.size + ' col-12 col-lg-' + column.size : '');
            obj.headline.append('<div class="' + style + '" id="' + obj.fullId + '-dataview-headcol-' + column.name + index + '">' + (column.descr ? fpcm.ui.translate(column.descr) : '&nbsp;') + '</div>');            
        });

        obj.headline.append('<div class="fpcm-ui-clear"></div>');

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

        obj.lines.append('<div class="row ' + row.class + '" id="' + rowId + '"></span>');

        jQuery.each(row.columns, function (index, rowCol) {

            var rowColumn   = obj.columns[index] ? obj.columns[index] : false;
            if (!rowColumn) {
                return;
            }
            
            if (!rowColumn.size) {
                rowColumn.size = 'auto';
            }

            var style       = 'fpcm-ui-padding-none-lr fpcm-ui-dataview-col ' + rowColumn.class + ' fpcm-ui-dataview-align-' + rowColumn.align
                            + (isNotFound ? ' col col-12' : ' fpcm-ui-dataview-size-' + rowColumn.size + ' col col-12 col-lg-' + rowColumn.size)
                            + ' fpcm-ui-dataview-type' + rowCol.type + ' align-self-center'
                            + (rowCol.class ? ' ' + rowCol.class : '');

            var valueStr    = ( rowCol.type == fpcm.vars.jsvars.dataviews.rolColTypes.coltypeValue
                            ? '<div class="fpcm-ui-dataview-col-value">' + (rowCol.value !== '' ? fpcm.ui.translate(rowCol.value) : '&nbsp;') + '</div>'
                            : (rowCol.value !== '' ? fpcm.ui.translate(rowCol.value) : '&nbsp;') );

            fpcm.dom.appendHtml('#' + rowId, '<div class="' + style + '" id="' + rowId + '-dataview-rowcol-' + rowCol.name + index + '">' + valueStr + '</div>');
            
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

    exists: function(id) {
        
        if (!fpcm.vars.jsvars.dataviews[id]) {
            return false;
        }

        return true;
    }

};