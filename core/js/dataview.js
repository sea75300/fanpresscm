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
            return false;
        }

        if (!fpcm.vars.jsvars.dataviews[id].columns.length) {
            return false;
        }

        var obj         = fpcm.vars.jsvars.dataviews[id];
        var rowEl       = {};
        var rowColumn   = {};
        var style       = '';
        var rowId       = '';
        var rowColId    = '';

        obj.fullId      = 'fpcm-dataview-' + id;
        obj.headId      = 'fpcm-dataview-' + id + '-head';
        obj.rowsId      = 'fpcm-dataview-' + id + '-rows';

        obj.wrapper     = jQuery('#' + obj.fullId).addClass('fpcm-ui-dataview');
        obj.wrapper.append('<div class="fpcm-ui-dataview-head" style="background-color:#d3d3d3;padding:0.3em;" id="' + obj.headId + '"></div>');
        obj.wrapper.append('<div class="fpcm-ui-dataview-rows" id="' + obj.rowsId + '"></div>');
        
        obj.headline    = jQuery('#' + obj.headId);
        obj.lines       = jQuery('#' + obj.rowsId);

        jQuery.each(obj.columns, function (index, column) {
            style = 'fpcm-ui-dataview-col fpcm-ui-dataview-align-' + column.align + ' fpcm-ui-dataview-size-' + column.size;
            obj.headline.append('<div class="' + style + '" id="fpcm-dataview-headcol-' + column.name + '">' + (column.descr ? column.descr : '&nbsp;') + '</div>');            
        });
        
        obj.headline.append('<div class="fpcm-ui-clear"></div>');

        jQuery.each(obj.rows, function (index, row) {

            rowId = 'fpcm-dataview-row-' + index;

            obj.lines.append('<div class="fpcm-ui-dataview-row" id="' + rowId + '"></span>');
            rowEl = jQuery('#'+rowId);

            jQuery.each(row, function (index, rowCol) {
                rowColumn = obj.columns[index] ? obj.columns[index] : {};
                rowColId  = 'fpcm-dataview-rowcol-' + rowCol.name;

                style     = 'fpcm-ui-dataview-col fpcm-ui-dataview-align-' + rowColumn.align + ' fpcm-ui-dataview-size-' + rowColumn.size;
                rowEl.append('<div class="' + style + '" id="' + rowColId + '"><div class="fpcm-ui-dataview-col-value">' + (rowCol.value ? rowCol.value : '&nbsp;') + '</div></div>');
            });

            rowEl.append('<div class="fpcm-ui-clear"></div>');
            
        });
        
        if (typeof params.onRenderAfter === 'function') {
            params.onRenderAfter.call();
        }
        
    }

};