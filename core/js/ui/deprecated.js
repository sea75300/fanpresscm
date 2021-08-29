/**
 * FanPress CM Deprecation Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {}
}

fpcm.deprecation = {

    addWarning: function (_sel, _text, _parent) {
        
        if (!_parent) {
            return fpcm.dom.fromTag(_sel).prepend('<div class="alert alert-danger" role="alert">' + _sel + ' :: ' + _text + '</div>');
        }

        return fpcm.dom.fromTag(_sel).parent().prepend('<div class="alert alert-danger" role="alert">' + _sel + ' :: ' + _text + '</div>');
    }

}

fpcm.ui.tabs = function(_sel, params) {
    
    if (!fpcm.dom.fromTag(_sel).length) {
        return false;
    }
    
    console.warn('fpcm.ui.tabs is deprecated as of version 5.0-dev; use fpcm.ui_tabs.render instead.');
    fpcm.dom.fromTag(_sel).addClass('fpcm ui-tabs-wrapper');
    fpcm.dom.fromTag(_sel + ' > ul').addClass('nav nav-tabs border-bottom border-warning border-5');
    fpcm.dom.fromTag(_sel + ' > ul > li').addClass('nav-item');
    fpcm.dom.fromTag(_sel + ' > ul > li > a').addClass('nav-link fpcm ui-background-white-50p active');
    fpcm.dom.fromTag(_sel + ' > div').addClass('fpcm ui-background-white-50p');
    fpcm.deprecation.addWarning(_sel, 'This view uses old tab create methodes. Use the \fpcm\view\view::addTabs function and fpcm.ui_tabs.render instead for proper function.');
}

fpcm.ui.assignControlgroups = function() {
    console.warn('fpcm.ui.assignControlgroups is deprecated as of version 5.0-dev; use fpcm.ui_tabs.render instead.');
}

fpcm.ui.spinner = function(_sel, params) {
    console.warn('fpcm.ui.spinner is deprecated as of version 5.0-dev; use numberInput view helper instead.');
}

fpcm.ui.datepicker = function(_sel, params) {
    fpcm.deprecation.addWarning(_sel, 'fpcm.ui.datepicker is deprecated as of version 5.0-dev; use native dateInput view helper instead.', true);
    console.warn('fpcm.ui.datepicker is deprecated as of version 5.0-dev; use native dateInput view helper instead.');
}
    
fpcm.ui.controlgroup = function(_sel, params) {
    fpcm.deprecation.addWarning(_sel, 'fpcm.ui.controlgroup is deprecated as of version 5.0-dev.', true);
    console.warn('fpcm.ui.controlgroup is deprecated as of version 5.0-dev.');
}

fpcm.ui.updateMainToolbar = function(_sel, params, onClick) {    
    fpcm.deprecation.addWarning(_sel, 'fpcm.ui.updateMainToolbar is deprecated as of version 5.0-dev.');
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
    console.warn('fpcm.ui.getValuesByClass is deprecated as of version 5.0-dev; use fpcm.dom.getValuesByClass instead.');
    return fpcm.dom.getValuesByClass(_class, _indexed);
}

fpcm.ui.dialog = function(params) {
    console.warn('fpcm.ui.dialog is deprecated as of version 5.0-dev; use fpcm.ui_dialogs.create instead.');
    return fpcm.ui_dialogs.create(params);
}
   
fpcm.ui.confirmDialog = function(params) {
    console.warn('fpcm.ui.confirmDialog is deprecated as of version 5.0-dev; use fpcm.ui_dialogs.confirm instead.');
    fpcm.ui_dialogs.confirm(params);
}

fpcm.ui.insertDialog = function(params) {
    console.warn('fpcm.ui.insertDialog is deprecated as of version 5.0-dev; use fpcm.ui_dialogs.insert instead.');
    fpcm.ui_dialogs.insert(params);
}

fpcm.ui.getDialogSizes =function(el, scale_factor) {
    console.warn('fpcm.ui.getDialogSizes is deprecated as of version 5.0-dev.');
}