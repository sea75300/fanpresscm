/**
 * FanPress CM Data View Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.dataview = {

    render: function (id, params) {

        if (!fpcm.vars.jsvars.dataviews[id]) {
            console.log('Dataview ' + id + ' does not exists in fpcm.vars.jsvars.dataviews!');
            return false;
        }

        if (!fpcm.vars.jsvars.dataviews[id].columns.length) {
            console.log('Dataview ' + id + ' does not contain any columns!');
            return false;
        }

        var obj         = fpcm.vars.jsvars.dataviews[id];
        var style       = '';

        obj.fullId      = 'fpcm-dataview-' + id;
        obj.headId      = 'fpcm-dataview-' + id + '-head';
        obj.rowsId      = 'fpcm-dataview-' + id + '-rows';

        obj.wrapper     = jQuery('#' + obj.fullId).addClass('fpcm-ui-dataview');
        obj.wrapper.append('<div class="fpcm-ui-dataview-head fpcm-ui-dataview-rowcolpadding ui-widget-header ui-corner-all ui-helper-reset" id="' + obj.headId + '"></div>');
        obj.wrapper.append('<div class="fpcm-ui-dataview-rows" id="' + obj.rowsId + '"></div>');
        
        obj.headline    = jQuery('#' + obj.headId);
        obj.lines       = jQuery('#' + obj.rowsId);

        jQuery.each(obj.columns, function (index, column) {
            style = 'fpcm-ui-dataview-col fpcm-ui-dataview-align-' + column.align + ' fpcm-ui-dataview-size-' + column.size;
            obj.headline.append('<div class="' + style + '" id="fpcm-dataview-headcol-' + column.name + index + '">' + (column.descr ? fpcm.ui.translate(column.descr) : '&nbsp;') + '</div>');            
        });
        
        obj.headline.append('<div class="fpcm-ui-clear"></div>');

        jQuery.each(obj.rows, function (index, row) {
            fpcm.dataview.addRow(index, row, obj);
        });
        
        if (typeof params.onRenderAfter === 'function') {
            params.onRenderAfter.call();
        }
        
    },
    
    addRow: function(index, row, obj) {

        var rowId           = 'fpcm-dataview-row-' + index;

        obj.lines.append('<div class="fpcm-ui-dataview-row' + (row.class ? ' ' + row.class : '') + '" id="' + rowId + '"></span>');
        var rowEl           = jQuery('#'+rowId);

        jQuery.each(row.columns, function (index, rowCol) {

            var rowColumn   = obj.columns[index] ? obj.columns[index] : {};

            var style       = 'fpcm-ui-dataview-col fpcm-ui-dataview-align-' + rowColumn.align
                            + ' fpcm-ui-dataview-size-' + rowColumn.size
                            + ' fpcm-ui-dataview-type' + rowCol.type
                            + (rowCol.class ? ' ' + rowCol.class : '');

            var valueStr    = ( rowCol.type == fpcm.vars.jsvars.dataviews.rolColTypes.coltypeValue
                            ? '<div class="fpcm-ui-dataview-col-value">' + (rowCol.value ? fpcm.ui.translate(rowCol.value) : '&nbsp;') + '</div>'
                            : (rowCol.value ? fpcm.ui.translate(rowCol.value) : '&nbsp;') );

            rowEl.append('<div class="' + style + '" id="fpcm-dataview-rowcol-' + rowCol.name + index + '">' + valueStr + '</div>');
        });

        rowEl.append('<div class="fpcm-ui-clear"></div>');
    },
    
    updateAndRender: function (id, params) {
        jQuery('#fpcm-dataview-' + id).empty();
        fpcm.dataview.render(id, params);
    }

};