/**
 * FanPress CM module installer Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.moduleinstaller = {

    startTime    : 0,
    stopTime     : 0,
    elCount      : [],
    elements     : [],
    currentEl    : {},
    currentIdx   : 0,

    init: function () {

        fpcm.moduleinstaller.elements = fpcm.dom.fromClass('fpcm-ui-update-icons');
        fpcm.moduleinstaller.elCount  = fpcm.moduleinstaller.elements.length;

        var start = fpcm.moduleinstaller.elements.first();
        fpcm.moduleinstaller.execRequest(start);
    },

    execRequest: function(el) {

        fpcm.moduleinstaller.currentIdx++;

        var params = {
            step: el.attr('data-step'),
            func: el.attr('data-func'),
            var: el.attr('data-var'),
        };

        var _listItem = el.parent().parent().parent();
        _listItem.addClass('list-group-item-primary').removeClass('disabled');
        
        if (params.var && fpcm.vars.jsvars.pkgdata.module[params.var]) {
            _listItem.html(_listItem.html().replace('{{var}}', fpcm.vars.jsvars.pkgdata.module[params.var]));
        }

        if (params.func && typeof fpcm.moduleinstaller[params.func] === 'function') {
            fpcm.moduleinstaller[params.func].call();
        }

        if (!params.step) {
            return false;
        }
        
        fpcm.moduleinstaller._spinner(_listItem, true);

        fpcm.moduleinstaller.currentEl = el;

        fpcm.ajax.post('packagemgr/moduleInstaller', {
            quiet: true,
            data: {
                step: params.step,
                key : fpcm.vars.jsvars.pkgdata.key,
                mode: fpcm.vars.jsvars.pkgdata.action
            },
            execDone: function (res) {

                fpcm.moduleinstaller._spinner(_listItem, false);

                if (!res.code) {
                    
                    _listItem.removeClass('list-group-item-primary').removeClass('disabled').addClass('list-group-item-danger');
                    fpcm.moduleinstaller.errorMsg();
                    
                    if (res.pkgdata.errorMsg) {
                        fpcm.ui.addMessage({
                            txt: res.pkgdata.errorMsg,
                            type: 'error'
                        });

                        fpcm.ui_notify.show({
                            body: res.pkgdata.errorMsg
                        });
                    }

                    fpcm.moduleinstaller.currentEl = {};
                    return false;
                }

                if (res.pkgdata instanceof Object) {
                    jQuery.extend(fpcm.vars.jsvars.pkgdata.module, res.pkgdata);
                }

                var afterFunc = fpcm.moduleinstaller.currentEl.attr('data-after');
                if (afterFunc && typeof fpcm.moduleinstaller[afterFunc] === 'function') {
                    fpcm.moduleinstaller[afterFunc].call();
                }

                _listItem.removeClass('list-group-item-primary').removeClass('disabled').addClass('list-group-item-success');
                if (!fpcm.moduleinstaller.elements[fpcm.moduleinstaller.currentIdx]) {
                    return false;
                }

                fpcm.moduleinstaller.execRequest(jQuery(fpcm.moduleinstaller.elements[fpcm.moduleinstaller.currentIdx]));
            },
            execFail: function () {

                fpcm.moduleinstaller._spinner(_listItem, false);

                _listItem.removeClass('list-group-item-primary').removeClass('disabled').addClass('list-group-item-danger');

                fpcm.ui_notify.show({
                    body: fpcm.moduleinstaller.errorMsg()
                });                   
                
                fpcm.moduleinstaller.currentEl = {};
                return false;
            }
        });

        return true;
    },
    
    startTimer: function() {
        fpcm.moduleinstaller.startTime = (new Date().getTime());
    },
    
    stopTimer: function() {
        fpcm.moduleinstaller.stopTime = (new Date().getTime());
        fpcm.dom.assignHtml('#fpcm-ui-update-timer', (fpcm.moduleinstaller.stopTime - fpcm.moduleinstaller.startTime) / 1000 + ' sec');

        let _res = fpcm.dom.fromId('fpcm-ui-update-result-1');
        _res.removeClass('disabled d-none').addClass('list-group-item-success');
        
        fpcm.ui_notify.show({
            body: _res.find('div.fpcm-ui-updater-descr').text().trim()
        }); 

        fpcm.dom.fromId('runUpdateNext').removeClass('fpcm ui-hidden');
    },
    
    errorMsg: function () {
        let _res = fpcm.dom.fromId('fpcm-ui-update-result-0');
        _res.removeClass('disabled d-none').addClass('list-group-item-danger');
        fpcm.dom.fromId('fpcm-ui-update-timer').addClass('d-none');
        fpcm.dom.fromId('fpcm-ui-update-version').addClass('d-none');
        return _res.find('div.fpcm-ui-updater-descr').text().trim();
    },
    
    _spinner: function (_listItem, _status) {
        _listItem.find('div.fpcm.ui-updater-spinner').html(_status ? '<div class="spinner-border spinner-border-sm text-secondary" role="status"></div>' : '');
    }

};