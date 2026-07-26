/**
 * FanPress CM Editor listener for filemanager messages
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020-2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editorMessages = {

    tabsObj: {},

    init: function() {
        
        window.addEventListener('message', function (event) {

            if (!event.data || !event.data.cmd || event.source.location.href !== event.data.validSource) {
                return false;
            }

            if (event.data.mceAction === 'clickFmgrBtn') {
                fpcm.dom.fromId(event.data.cmd).click()
            }

        });
    }

};
