/**
 * FanPress CM public javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

if (fpcm === undefined) {
    var fpcm = {
        vars: {
            jsvars: {},
            ui: {
                messages: []
            }
        },
        modules: {},
        shares: {},
        system: {
            mergeToVars: function (_newvalue) {

                if (!_newvalue) {
                    _newvalue = [];
                }

                if (typeof jQuery === 'undefined') {
                    return false;
                }

                return jQuery.extend(true, fpcm, _newvalue);
            },
            bindClick: function (_el, _callback) {

                if (_el instanceof HTMLCollection) {

                    for (var _subEl of _el) {
                        _subEl.addEventListener('click', _callback);
                    }

                    return;
                }

                _el.addEventListener('click', _callback);
            }
        },
        pub: {
            init: function () {

                fpcm.pub.doRefresh();
                fpcm.pub.showMessages();

                fpcm.system.bindClick(
                    document.getElementsByClassName('fpcm-pub-commentsmiley'),
                    function (_ev) {
                        _ev.preventDefault();
                        fpcm.pub.insert(' ' + _ev.currentTarget.dataset.code + ' ');
                    }
                );

                fpcm.system.bindClick(
                    document.getElementsByClassName('fpcm-pub-mentionlink'),
                    (_ev) => {
                        _ev.preventDefault();
                        fpcm.pub.insert('@#' + _ev.currentTarget.id + ': ');
                    }
                );

                fpcm.system.bindClick(
                    document.getElementsByClassName('a.fpcm-pub-sharebutton-count'),
                    (_ev) => {
                        _ev.preventDefault();
                        let _item = _ev.currentTarget.dataset.onclick;
                        if (fpcm.pub.shares[_item] && (new Date()).getTime() - fpcm.pub.shares[_item] < 30000) {
                            return false;
                        }

                        if (!fpcm.pub.shares[_item]) {
                            fpcm.pub.shares[_item] = 0;
                        }

                        fpcm.pub.shares[_item] = (new Date()).getTime();
                        fpcm.pub.doAjax({
                            action: 'shareClick',
                            type: 'POST',
                            data: {
                                oid: _ev.currentTarget.dataset.oid,
                                item: _item
                            },
                            execDone: function(_result) {

                                if (_item !== 'likebutton') {
                                    return true;
                                }

                                fpcm.pub.addMessage({
                                    type: 'notice',
                                    id: (new Date()).getTime(),
                                    txt: fpcm.vars.ui.lang['PUBLIC_SHARE_LIKE']
                                });

                                return false;
                            }
                        });

                        return _item === 'likebutton' ? false : true;
                    }
                );

                for (var _m in fpcm.modules) {

                    if (!_m.init || typeof _m.init !== 'function') {
                        return true;
                    }

                    _m.init();
                }
            },

            insert: function (smiliecode) {
                aTag = smiliecode;
                eTag = "";
                var input = document.getElementById('newcommenttext');

                input.focus();
                if (typeof input.selectionStart != 'undefined')
                {
                    /* Einfügen des Formatierungscodes */
                    var start = input.selectionStart;
                    var end = input.selectionEnd;
                    var insText = input.value.substring(start, end);
                    input.value = input.value.substr(0, start) + aTag + insText + eTag + input.value.substr(end);
                    /* Anpassen der Cursorposition */
                    var pos;
                    if (insText.length == 0) {
                        pos = start + aTag.length;
                    } else {
                        pos = start + aTag.length + insText.length + eTag.length;
                    }
                    input.selectionStart = pos;
                    input.selectionEnd = pos;
                }
                /* für die übrigen Browser */
                else
                {
                    /* Abfrage der Einfügeposition */
                    var pos;
                    var re = new RegExp('^[0-9]{0,3}$');
                    while (!re.test(pos)) {
                        pos = prompt("Einfügen an Position (0.." + input.value.length + "):", "0");
                    }
                    if (pos > input.value.length) {
                        pos = input.value.length;
                    }
                    /* Einfügen des Formatierungscodes */
                    input.value = input.value.substr(0, pos) + aTag + insText + eTag + input.value.substr(pos);
                }
            },

            addMessage: function (msg) {

                let _msgWrapper = document.getElementById('#fpcm-messages');
                if (!_msgWrapper) {
                    alert(msg.txt);
                    return true;
                }

                let _msg = document.createElement('div');
                let _text = document.createElement('div');

                _msg.classList.add('fpcm-pub-message-box', 'fpcm-pub-message-' + msg.type);
                _msg.id = msg.id;

                _text.classList.add('fpcm-pub-message-box-text');
                _text.innerHTML = msg.txt;

                _msg.appendChild(_text);

                _msgWrapper.appendChild(_msg);
            },

            showMessages: function() {

                if (!fpcm.vars.ui || !fpcm.vars.ui.messages || !fpcm.vars.ui.messages.length) {
                    return false;
                }

                var msg = null;
                for (var i = 0; i < fpcm.vars.ui.messages.length; i++) {
                    fpcm.pub.addMessage(fpcm.vars.ui.messages[i]);
                }

                return true;
            },

            doRefresh: function() {

                if (!fpcm.vars.ajaxActionPath) {
                    return false;
                }

                if (fpcm.vars.ajaxRefreshDisable) {
                    return false;
                }

                fpcm.pub.doAjax({
                    action: 'refresh',
                    data: {
                        t: 1
                    }
                });

                return true;
            },

            doAjax: function (config) {
                
                if (typeof jQuery === 'undefined') {
                    console.error('jQuery is no loaded! Check if you included the libary in your page header or enable inclusion in FanPress CM ACP.');
                    return false;
                }

                if (!fpcm.vars.ajaxActionPath && !config.ajaxActionPath) {
                    console.error('Unable to execute AJAX request due to missing request destination!');
                    console.error(config);
                    return false;
                }

                var _params = {
                    url: (config.ajaxActionPath ? config.ajaxActionPath : fpcm.vars.ajaxActionPath) + config.action,
                    async: config.async !== undefined ? config.async : true,
                    type: config.method ? config.method.toUpperCase() : 'GET'
                }

                if (config.data) {
                    _params.data = config.data;
                }

                if (config.dataType) {
                    _params.dataType = config.dataType;
                }

                if (config.onCode) {
                    _params.statusCode = config.onCode;
                }

                jQuery.ajax(_params).done(function (result) {

                    if (result.search && result.search('FATAL ERROR:') === 3) {
                        console.error('ERROR MESSAGE: ' + errorThrown);
                    }

                    if (typeof config.execDone != 'function') {
                        return true;
                    }

                    config.execDone(result);
                })
                .fail(function (jqXHR, textStatus, errorThrown) {

                    console.error('STATUS MESSAGE: ' + textStatus);
                    console.error('ERROR MESSAGE: ' + errorThrown);

                    if (typeof config.execFail != 'function') {
                        return true;
                    }

                    config.execFail();
                });
            }
        }
    };
}

window.addEventListener('load', (_e) => {
    fpcm.pub.init();
});