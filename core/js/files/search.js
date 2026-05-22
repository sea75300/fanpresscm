/**
 * FanPress CM Filemanager search Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.search = {

    _dlg: false,

    init: function() {

        if (!fpcm.ui.langvarExists('ARTICLES_SEARCH')) {
            return false;
        }

        fpcm.dom.bindClick('#btnOpenSearch', function ()
        {
            fpcm.ui_dialogs.search(
                'files',
                function(_ui, _bsObj) {

                    if (((new Date()).getTime() - fpcm.vars.jsvars.filesLastSearch) < 10000) {
                        fpcm.ui.addMessage({
                            type: 'error',
                            txt : fpcm.ui.translate('SEARCH_WAITMSG')
                        });
                        return false;
                    }

                    let _filter = fpcm.search._dlg.getValues();
                    fpcm.filemanager.reloadFiles(1, _filter);
                },
                function() {
                    fpcm.search._dlg.reset();
                    fpcm.search._dlg = false;
                    fpcm.filemanager.reloadFiles(1);
                }
            );
        });

    }

};
