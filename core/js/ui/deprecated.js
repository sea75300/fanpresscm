/**
 * FanPress CM Deprecation Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {}
}

fpcm.ui.assignControlgroups = function() {
    console.warn('fpcm.ui.assignControlgroups is deprecated as of version 5.0-dev; use fpcm.ui_tabs.render instead.');
}
    
fpcm.ui.tabs = function(_elemClassId, params) {
    console.warn('fpcm.ui.tabs is deprecated as of version 5.0-dev; use fpcm.ui_tabs.render instead.');
}

fpcm.ui.spinner = function(elemClassId, params) {
    console.warn('fpcm.ui.spinner is deprecated as of version 5.0-dev; use numberInput view helper instead.');
}

fpcm.ui.datepicker = function(elemClassId, params) {
    console.warn('fpcm.ui.datepicker is deprecated as of version 5.0-dev; use native dateInput view helper instead.');
}
    
fpcm.ui.controlgroup = function(elemClassId, params) {
    console.warn('fpcm.ui.controlgroup is deprecated as of version 5.0-dev.');
}

fpcm.ui.button = function(elemClassId, params, onClick) {
    console.warn('fpcm.ui.button is deprecated as of version 5.0-dev.');
}

fpcm.ui.updateMainToolbar = function(elemClassId, params, onClick) {
    console.warn('fpcm.ui.updateMainToolbar is deprecated as of version 5.0-dev.');
}

fpcm.ui.resetSelectMenuSelection = function (elId) {
    console.warn('fpcm.ui.resetSelectMenuSelection is deprecated as of version 5.0-dev; use fpcm.dom.resetValuesByIdsSelect instead.');
    fpcm.dom.resetValuesByIdsSelect([elId]);
    return true;
}
    
fpcm.ui.getCheckboxCheckedValues = function(id) {
    console.warn('fpcm.ui.getCheckboxCheckedValues is deprecated as of version 5.0-dev; use fpcm.dom.getCheckboxCheckedValues instead.');
    return fpcm.dom.getCheckboxCheckedValues(id);
}

fpcm.ui.getValuesByClass = function(_class, _indexed) {        
    console.warn('fpcm.ui.getValuesByClass is deprecated as of version 5.0-dev; use fpcm.dom.getCheckboxCheckedValues instead.');
    return fpcm.dom.getValuesByClass(_class, _indexed);
}

fpcm.ui.dialog = function(params) {
    console.warn('fpcm.ui.dialog is deprecated as of version 5.0-dev; use fpcm.ui_dialogs.create instead.');
    return fpcm.ui_dialogs.create(params);
}
   
fpcm.ui.confirmDialog = function(params) {
    console.warn('fpcm.ui.confirmDialog is deprecated as of version 5.0-dev; use fpcm.ui_dialogs.confirmDlg instead.');
    fpcm.ui_dialogs.confirmDlg(params);
}

fpcm.ui.insertDialog = function(params) {
    console.warn('fpcm.ui.insertDialog is deprecated as of version 5.0-dev; use fpcm.ui_dialogs.insertDlg instead.');
    fpcm.ui_dialogs.insertDlg(params);
}

fpcm.ui.getDialogSizes =function(el, scale_factor) {
    console.warn('fpcm.ui.getDialogSizes is deprecated as of version 5.0-dev.');
}