/**
 * FanPress CM Smiley Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.6
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.smileys = {

    init: function () {

        if (fpcm.dataview && fpcm.dataview.exists('smileys')) {
            fpcm.dataview.render('smileys');
        }

        if (fpcm.vars.jsvars.files) {            
            fpcm.ui.autocomplete('#smileyfilename', {
                source: fpcm.vars.jsvars.files,
                minLength: 2,
                onRenderItems: function( _item ) {
                    _item.altLabel = '<img src="' + fpcm.vars.jsvars.smileypath + _item.value + '" class="fpcm-ui-smileylist-preview d-inline-block"><span>' +  _item.label + '</span>';
                    return _item;
                }
            });
        }
    }
};