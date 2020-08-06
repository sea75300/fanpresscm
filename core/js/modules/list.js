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
        fpcm.modulelist.tabs = fpcm.ui.tabs('#fpcm-tabs-modules',
        {
            addTabScroll: true,
            initDataViewJson: true,         
            dataViewWrapperClass: 'fpcm-ui-modulelist',
            initDataViewOnRenderAfter: fpcm.modulelist.initButtons,
            initDataViewJsonBefore:function(event, ui) {
                fpcm.dom.fromClass('fpcm-ui-modulelist').remove();
            },            
            beforeActivate: function( event, ui ) {
                fpcm.dom.fromTag(ui.oldTab).unbind('click');
            },
        });

        fpcm.modulelist.tabs.tabs('load', 0);
    },
    
    initButtons: function () {

        fpcm.ui.assignControlgroups();

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

            fpcm.ui.dialog({
                title: fpcm.ui.translate('GLOBAL_CONFIRM'),
                content: fpcm.ui.translate('CONFIRM_MESSAGE'),
                dlWidth: fpcm.ui.getDialogSizes(top, 0.35).width,
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_YES'),
                        icon: "ui-icon-check",                    
                        click: function () {
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
                                    
                                    fpcm.modulelist.tabs.tabs('load', 0);
                                }
                            });

                            fpcm.dom.fromTag(this).dialog( "close" );
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_NO'),
                        icon: "ui-icon-closethick",
                        click: function () {
                            fpcm.dom.fromTag(this).dialog( "close" );
                        }
                    }
                ]
            });
        });

        fpcm.dom.fromTag('a.fpcm-ui-modulelist-info').click(function(event) {

            var btnEl = fpcm.dom.fromTag(this);
            fpcm.ui.dialog({
                id: 'modulelist-infos',
                title: fpcm.ui.translate('MODULES_LIST_INFORMATIONS'),
                resizable: true,
                dlHeight: fpcm.ui.getDialogSizes(top, 0.75).height,
                content: fpcm.ui.createIFrame({
                    src: btnEl.attr('href')
                }),
                dlButtons: [
                    {
                        text: fpcm.ui.translate('GLOBAL_CLOSE'),
                        icon: "ui-icon-closethick",                
                        click: function() {
                            fpcm.dom.fromTag(this).dialog( "close" );
                        }
                    }
                ],
                dlOnClose: function() {
                    fpcm.dom.fromId('fpcm-dialog-modulelist-infos').remove();
                }
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