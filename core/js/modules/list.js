/**
 * FanPress CM Module Liste Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.modulelist = {

    init: function() {

        fpcm.system.checkForUpdates();
        fpcm.ui_tabs.render('#modulemgr',
        {
            initDataViewOnRenderAfter: fpcm.modulelist.initButtons,
        });

    },
    
    initButtons: function () {

        fpcm.dom.fromTag('a.fpcm-ui-modulelist-action-local-update').unbind('click');
        fpcm.dom.fromTag('a.fpcm-ui-modulelist-action-local-update').click(function() {
            var destUrl = fpcm.dom.fromTag(this).attr('href');
            fpcm.ui.confirmDialog({
                clickNoDefault: true,
                clickYes: function () {
                    fpcm.ui.relocate(destUrl);
                    return false;
                }
            });
            
            return false;
        });

        fpcm.dom.fromTag('button.fpcm-ui-modulelist-action-local').unbind('click');
        fpcm.dom.fromTag('button.fpcm-ui-modulelist-action-local').click(function() {
            
            var btnEl = fpcm.dom.fromTag(this);

            var params = {
                action: btnEl.data('action'),
                key: btnEl.data('key'),
            };

            var fromDir = btnEl.data('dir');
            if (fromDir) {
                params.fromDir = fromDir;
            }

            fpcm.ui.confirmDialog({                
                clickYes: function () {
                    fpcm.ajax.post('modules/exec', {
                        data: params,
                        execDone: function (result) {
                            if (result.code !== undefined && result.code < 1) {
                                var msg = '';
                                switch (result.code) {
                                    case fpcm.vars.jsvars.codes.installFailed :
                                        msg = 'MODULES_FAILED_INSTALL';
                                        break;
                                    case fpcm.vars.jsvars.codes.uninstallFailed :
                                        msg = 'MODULES_FAILED_UNINSTALL';
                                        break;
                                    case fpcm.vars.jsvars.codes.enabledFailed :
                                        msg = 'MODULES_FAILED_ENABLE';
                                        break;
                                    case fpcm.vars.jsvars.codes.disabledFailed :
                                        msg = 'MODULES_FAILED_DISABLE';
                                        break;
                                }

                                fpcm.ui.addMessage({
                                    txt: msg,
                                    type: 'error'
                                }, true);
                            }

                            fpcm.ui_tabs.getNodes('#modulemgr', 0);
                            fpcm.ui_tabs.show('#modulemgr', 0);
                        }
                    });
                }
            });
        });

        fpcm.dom.fromTag('a.fpcm-ui-modulelist-info').unbind('click');
        fpcm.dom.fromTag('a.fpcm-ui-modulelist-info').click(function(event) {

            var btnEl = fpcm.dom.fromTag(this);
            fpcm.ui.dialog({
                id: 'modulelist-infos',
                title: 'MODULES_LIST_INFORMATIONS',
                url: btnEl.attr('href'),
                closeButton: true
            });
            
            return false;
        });
        
    }
 
};

fpcm.filemanager = {

    runFileIndexUpdate: function (_params) {

        if (!_params.files || !_params.files[0] || !_params.files[0].name) {
            return false;
        }

        if (_params.result.files[0].error) {
            return false;
        }

        fpcm.ajax.post('packagemgr/unzcp', {
            data    : {
                file: _params.files[0].name
            },
            loaderMsg: fpcm.ui.translate('MODULES_LIST_INSTALL'),
            execDone: function (result) {
                fpcm.ui.addMessage(result);
                if (result.type !== 'notice') {
                    return false;
                }

                fpcm.modulelist.tabs.tabs('load', 0);
            }
        });

    },
    
    getAcceptTypes: function ()
    {
        return /(\.|\/)(zip)$/i;
    }

};