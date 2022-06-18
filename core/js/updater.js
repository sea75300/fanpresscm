/**
 * FanPress CM Updater Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.updater = {

    startTime    : 0,
    stopTime     : 0,
    elCount      : [],
    elements     : [],
    currentEl    : {},
    currentIdx   : 0,

    init: function () {

        fpcm.updater.elements = fpcm.dom.fromClass('fpcm-ui-update-icons');
        fpcm.updater.elCount  = fpcm.updater.elements.length;

        var start = fpcm.updater.elements.first();
        fpcm.updater.execRequest(start);
    },

    execRequest: function(el) {

        fpcm.updater.currentIdx++;

        var params = {
            step: el.data('step'),
            func: el.data('func'),
            var: el.data('var'),
        };

        var _listItem = el.parent().parent().parent();
        _listItem.addClass('list-group-item-primary').removeClass('disabled');

        if (params.var && fpcm.vars.jsvars.pkgdata.update[params.var]) {
            _listItem.html(_listItem.html().replace('{{var}}', fpcm.vars.jsvars.pkgdata.update[params.var]));
        }

        if (params.func && typeof fpcm.updater[params.func] === 'function') {
            fpcm.updater[params.func].call();
        }

        if (!params.step) {
            return false;
        }

        fpcm.updater._spinner(_listItem, true);

        fpcm.updater.currentEl = el;

        fpcm.ajax.post('packagemgr/processUpdate', {
            quiet: true,
            data: {
                step : params.step
            },
            execDone: function (res) {

                fpcm.updater._spinner(_listItem, false);

                if (!res.code) {

                    _listItem.removeClass('list-group-item-primary').removeClass('disabled').addClass('list-group-item-danger');
                    fpcm.updater.errorMsg();
                    
                    if (res.message) {
                        fpcm.ui.addMessage(res.message);
                        
                        fpcm.ui_notify.show({
                            body: res.message.txt
                        });
                    }

                    fpcm.updater.currentEl = {};
                    return false;
                }

                if (res.pkgdata instanceof Object) {
                    jQuery.extend(fpcm.vars.jsvars.pkgdata.update, res.pkgdata);
                }

                var afterFunc = fpcm.updater.currentEl.data('after');
                if (afterFunc && typeof fpcm.updater[afterFunc] === 'function') {
                    fpcm.updater[afterFunc].call();
                }

                _listItem.removeClass('list-group-item-primary').removeClass('disabled').addClass('list-group-item-success');
                
                if (!fpcm.updater.elements[fpcm.updater.currentIdx]) {
                    return false;
                }

                fpcm.updater.execRequest(fpcm.dom.fromTag(fpcm.updater.elements[fpcm.updater.currentIdx]));
            },
            execFail: function () {

                fpcm.updater._spinner(_listItem, false);

                _listItem.removeClass('list-group-item-primary').removeClass('disabled').addClass('list-group-item-danger');

                fpcm.ui_notify.show({
                    body: fpcm.updater.errorMsg()
                });                   
                
                fpcm.updater.currentEl = {};
                return false;
            }
        });

        return true;
    },
    
    startTimer: function() {
        fpcm.updater.startTime = (new Date().getTime());
    },
    
    stopTimer: function() {
        fpcm.updater.stopTime = (new Date().getTime());
        
        fpcm.dom.assignHtml('#fpcm-ui-update-timer', (fpcm.updater.stopTime - fpcm.updater.startTime) / 1000 + ' sec');
        fpcm.dom.assignHtml('#fpcm-ui-update-newver-descr', fpcm.vars.jsvars.pkgdata.update.version);

        let _res = fpcm.dom.fromId('fpcm-ui-update-result-1');
        _res.removeClass('disabled d-none').addClass('list-group-item-success');
        
        fpcm.ui_notify.show({
            body: _res.find('div.fpcm-ui-updater-descr').text().trim()
        });        

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