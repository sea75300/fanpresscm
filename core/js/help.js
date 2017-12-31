/**
 * FanPress CM Comments Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.help = {

    init: function () {
        jQuery('#tabs-help-general div.fpcm-tabs-accordion').accordion({
            header: "h2",
            heightStyle: "content",
            active: fpcmDefaultCapter
        });
    }

};