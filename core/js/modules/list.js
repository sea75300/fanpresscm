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

    _placeholder: '',

    init: function() {

        fpcm.system.checkForUpdates();
        fpcm.ui_tabs.render('#modulemgr', {
            initDataViewOnRenderAfter: fpcm.modulelist.initButtons,
        });

    },
    
    initButtons: function () {

        fpcm.dom.bindClick('a.fpcm-ui-modulelist-action-local-update', function(_ui) {
            var _url = _ui.delegateTarget.href;
            fpcm.ui_dialogs.confirm({
                clickNoDefault: true,
                clickYes: function () {
                    fpcm.ui.relocate(_url);
                    return false;
                }
            });
            
            return false;
        });

        fpcm.dom.bindClick('button.fpcm-ui-modulelist-action-local', function(_ui) {
            
            var btnEl = _ui.delegateTarget;

            var params = {
                action: btnEl.dataset.action,
                key: btnEl.dataset.key,
            };

            var fromDir = btnEl.dataset.dir;
            if (fromDir) {
                params.fromDir = fromDir;
            }

            fpcm.ui_dialogs.confirm({                
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
        
        var _ocEL = document.getElementById('offcanvasInfo')
        _ocEL.addEventListener('shown.bs.offcanvas', function (_e) {
            
            if (!fpcm.modulelist._placeholder) {
                fpcm.modulelist._placeholder = _e.target.getElementsByClassName('offcanvas-body')[0].innerHTML;
            }
            else {
                 _e.target.getElementsByClassName('offcanvas-body')[0].innerHTML = fpcm.modulelist._placeholder;
            }

            fpcm.ajax.get('modules/info', {
                quiet: true,
                data: {
                    key: _e.relatedTarget.dataset.key,
                    repo: _e.relatedTarget.dataset.repo ? _e.relatedTarget.dataset.repo : 0,
                },
                execDone: function (_result) {
                    _e.target.getElementsByClassName('offcanvas-body')[0].innerHTML = _result;
                    fpcm.dom.bindClick('#btnInstall', function (_e, _ui) {
                        let el = fpcm.dom.fromId('install' + _ui.dataset.hash);
                        if (!el.length) {
                            return false;
                        }

                        parent.fpcm.ui.relocate(el.attr('href'));
                    });
                }
            });
        });

        _ocEL.addEventListener('hide.bs.offcanvas', function (_e) {
            _e.target.getElementsByClassName('offcanvas-body')[0].innerHTML = '';
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
                
                fpcm.ui_tabs.show('#modulemgr', 0);
            }
        });

    },
    
    getAcceptTypes: function ()
    {
        return /(\.|\/)(zip)$/i;
    }

};