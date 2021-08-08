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
    console.warn('fpcm.ui.assignControlgroups is deprecated as of version 5.0-dev. Use fpcm.ui_tabs.render instead.');
}
    
fpcm.ui.tabs = function(_elemClassId, params) {
    console.warn('fpcm.ui.tabs is deprecated as of version 5.0-dev. Use fpcm.ui_tabs.render instead.');
}

fpcm.ui.spinner = function(elemClassId, params) {
    console.warn('fpcm.ui.spinner is deprecated as of version 5.0-dev. Use numberInput view helper instead.');
}

fpcm.ui.datepicker = function(elemClassId, params) {
    console.warn('fpcm.ui.datepicker is deprecated as of version 5.0-dev. Use native dateInput view helper instead.');
}
    
fpcm.ui.controlgroup = function(elemClassId, params) {        
    console.warn('fpcm.ui.controlgroup is deprecated as of version 5.0-dev.');
}

fpcm.ui.button = function(elemClassId, params, onClick) {
    console.warn('fpcm.ui.button is deprecated as of version 5.0-dev.');
}