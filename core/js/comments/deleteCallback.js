/**
 * FanPress CM Comments delete callback Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 5.1.0-a1
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.commentCallbacks = {
    
    deleteCallback: function(_result) {

        if (_result.code == 1) {
            window.location.reload();
            return true;
        }

        fpcm.ui.addMessage({
            txt: 'DELETE_FAILED_COMMENTS',
            type: 'error',
        }, true);
        
        fpcm.dom.resetCheckboxesByClass('fpcm-ui-list-checkbox');
        return false;
    }

};