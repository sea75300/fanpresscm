/**
 * FanPress CM Smiley Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.6
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.smileys = {

    init: function () {

        var acObject = fpcm.ui.autocomplete('#smileyfilename', {
            source: fpcmSmileyFiles
        });

        acObject.autocomplete('instance').
        _renderItem = function( ul, item ) {
            return  jQuery('<li>').
                    addClass('fpcm-ui-smileylist-preview').
                    append('<div><img src="' + item.label + '" class="fpcm-ui-smileylist-preview"><span>' + item.value + '</span></div>').
                    appendTo(ul);
        };

    }
};