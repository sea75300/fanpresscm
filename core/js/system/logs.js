/**
 * FanPress CM Logs Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.logs = {

    init: function () {

        fpcm.ui_tabs.render('#tabs-logs', {

            reload: true,

            onShow: function (_el) {
                let _logParams = fpcm.system.parseUrlQuery(_el.target.attributes.href.value);
                let _newLog = _logParams.log ? _logParams.log : null;

                if (_newLog !== fpcm.vars.jsvars.currentLog.name) {
                    fpcm.vars.jsvars.currentLog.searchterm = '';
                }

                fpcm.vars.jsvars.currentLog.name = _newLog;
                fpcm.vars.jsvars.currentLog.key = _logParams.key ? _logParams.key : null;
                fpcm.vars.jsvars.currentLog.system = _logParams.system ? _logParams.system : null;

               let _sp = new URLSearchParams(_el.target.search);

                if (fpcm.vars.jsvars.currentLog.searchterm && fpcm.vars.jsvars.currentLog.searchterm.length > 3) {
                    _sp.set('term', fpcm.vars.jsvars.currentLog.searchterm);
                }
                else if (_sp.has('term')) {
                    _sp.delete('term');
                }

                _el.target.search = _sp.toString();
            },

            onRenderJsonAfter: function (_el, _result) {

                if (!_result.logsize) {
                    return false;
                }

                let _str = '<div class="row fpcm-ui-font-small">';
                _str += '<div class="col-12 align-self-center p-2">';
                _str += fpcm.ui.getIcon('weight', { size: 'lg' }) + fpcm.ui.translate('FILE_LIST_FILESIZE') + ': ' + _result.logsize;
                _str += '</div>';
                _str += '</div>';
                fpcm.dom.appendHtml(_el.target.dataset.bsTarget, _str);
            }

        });

        fpcm.dom.bindClick('#btnCleanLogs', function (_event, _ui) {

            fpcm.ui_dialogs.confirm({
                focusNo: true,
                clickYes: function () {
                    fpcm.logs.clearLogs(fpcm.vars.jsvars.currentLog);
                }
            });

        });

        fpcm.dom.bindClick('#btnSearchLogs', function () {

            var _input = new fpcm.ui.forms.input();
            _input.name = 'log-search-dialog';
            _input.label = fpcm.ui.translate('ARTICLE_SEARCH_TEXT');
            _input.placeholder = _input.label;
            _input.required = true;

            if (fpcm.vars.jsvars.currentLog.searchterm) {
                _input.value = fpcm.vars.jsvars.currentLog.searchterm;
            }
            else {
                _input.value = '';
            }

            fpcm.ui_dialogs.create({
                id: 'search-logs',
                title: 'ARTICLES_SEARCH',
                closeButton: true,
                content: _input,
                dlButtons: [
                    {
                        text: 'ARTICLE_SEARCH_START',
                        icon: 'search',
                        primary: true,
                        autofocus: false,
                        clickClose: true,
                        click: function() {
                            let _inputEl = document.getElementById('fpcm-id-log-search-dialog');
                            fpcm.vars.jsvars.currentLog.searchterm = _inputEl.value.replace(/([^\w\d\s\.\-\_]+)/i, '');
                            fpcm.logs.reloadLogs();
                        }
                    },
                    {
                        text: fpcm.ui.translate('GLOBAL_RESET'),
                        icon: "filter-circle-xmark" ,
                        clickClose: true,
                        click: function() {
                            fpcm.vars.jsvars.currentLog.searchterm = '';
                            fpcm.logs.reloadLogs();
                        }
                    }
                ],
                dlOnOpenAfter: function( event, ui ) {
                    fpcm.dom.setFocus('fpcm-id-log-search-dialog');
                }
            });

        });

    },

    clearLogs: function(_params) {

        fpcm.ajax.post('logs/clear', {
            data: {
                log: _params.name,
                key: _params.key,
                system: _params.system
            },
            execDone: function(result) {
                fpcm.logs.reloadLogs();
                fpcm.vars.jsvars.currentLog.searchterm = '';
                fpcm.ui.addMessage(result, true);
            }
        });

        return false;
    },

    reloadLogs: function() {

        let _id = fpcm.ui.prepareId('tabs-logs');

        let _dom = document.querySelector(_id + ' a.nav-link.active');
        fpcm.dom.fromTag(_id + ' a.nav-link.active').removeClass('active');
        (new bootstrap.Tab(_dom)).show();
    }

};