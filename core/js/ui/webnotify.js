/**
 * FanPress CM UI Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui_notify = {

    _supported: true,
    _currentPermission: null,

    init: function() {
        
        if (Notification === undefined) {
            console.error('WebNotifications is not supported or provided by the current browser.');
            fpcm.ui_notify._supported = false;
        }

    },
    
    show: function(_params) {

        if (!fpcm.ui_notify._supported) {
            alert(_params.body);
            return false;
        }
        
        if (!_params.noRequest) {
            fpcm.ui_notify._requestPermissions(_params);
        }

        if (fpcm.ui_notify._currentPermission !== true) {
            return false;
        }

        var opts = {
            body: fpcm.ui.translate(_params.body),
        };

        opts.icon = _params.icon ? _params.icon : fpcm.vars.ui.notifyicon;
        
        //

        if (_params.tag) {
            opts.tag = _params.tag;
        }

        if (_params.lang) {
            opts.lang = _params.lang;
        }
        else {
            opts.lang = fpcm.dom.fromTag('html').attr('lang');
        }
        
        if (!_params.title) {
            _params.title = 'HEADLINE';
        }

        var obj = new Notification(fpcm.ui.translate(_params.title), opts);

        if (_params.click) {
            obj.onclick = _params.click;
        }

        if (_params.error) {
            obj.onerror = _params.error;
        }        
        
        if (_params.timeout === -1) {
            return true;
        }

        setTimeout(obj.close.bind(obj), _params.timeout ? _params.timeout : 4000);
        return true;
    },
    
    _requestPermissions: function (_msgParams) {

        if (fpcm.ui_notify._hasPermissions() !== null) {
            return true;
        }
        
        Notification.requestPermission().then(function(result) {
            
            if (!fpcm.ui_notify._hasPermissions(result)) {
                return false;
            }
            
            if (_msgParams) {
                _msgParams.noRequest = true;
                fpcm.ui_notify.show(_msgParams);
            }
            
            return true;
        });

        return fpcm.ui_notify._currentPermission;
    },

    _hasPermissions: function(_var) {

        if (!_var) {
            _var = Notification.permission;
        }

        if (_var === 'granted') {
            fpcm.ui_notify._currentPermission = true;
            return true;
        }

        if (_var === 'denied') {
            fpcm.ui_notify._currentPermission = false;
            return false;
        }
;
        return null;
    }

};