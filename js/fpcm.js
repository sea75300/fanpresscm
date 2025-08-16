/**
 * FanPress CM public javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2025, Stefan Seehafer
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

                fpcm.system._mergeRecursive(fpcm, _newvalue);
            },
            _mergeRecursive: function(_out, ...arguments_) {

                if (!_out) {
                    return false;
                }

                for (const obj of arguments_) {

                  if (!obj) {
                    continue;
                  }

                  for (const [key, value] of Object.entries(obj)) {
                    switch (Object.prototype.toString.call(value)) {
                      case '[object Object]':
                        _out[key] = _out[key] || {};
                        _out[key] = fpcm.system._mergeRecursive(_out[key], value);
                        break;
                      case '[object Array]':
                        _out[key] = fpcm.system._mergeRecursive(new Array(value.length), value);
                        break;
                      default:
                        _out[key] = value;
                    }
                  }
                }

                return _out;
            },
            bindClick: function (_el, _callback) {

                if (_el instanceof HTMLCollection || _el instanceof NodeList) {

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
                    document.querySelectorAll('a.fpcm-pub-sharebutton-count'),
                    (_ev) => {
                        let _item = _ev.currentTarget.dataset.onclick;

                        if (_item === 'likebutton') {
                            _ev.preventDefault();
                        }

                        if (!fpcm.shares[_item]) {
                            fpcm.shares[_item] = 0;
                        }

                        if (fpcm.shares[_item] && (new Date()).getTime() - fpcm.shares[_item] < 30000) {
                            return false;
                        }

                        fpcm.shares[_item] = (new Date()).getTime();

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

                let _msgWrapper = document.getElementById('fpcm-messages');
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

            doAjax: async function (_config) {

                if (!fpcm.vars.ajaxActionPath && !_config.ajaxActionPath) {
                    console.error('Unable to execute AJAX request due to missing request destination!');
                    console.error(_config);
                    return false;
                }


                if (!_config.ajaxActionPath) {
                    _config.ajaxActionPath = fpcm.vars.ajaxActionPath;
                }

                let _url = _config.ajaxActionPath + _config.action;

                if (!_config.method) {
                    _config.method = 'GET';
                }

                const _init = {};

                if (_config.dataType) {
                    _init.headers['Content-Type'] = _config.dataType;
                }

                _init.method = _config.method.toUpperCase();

                if (_config.data && _config.method === 'GET') {

                    let _tmp = new URL(_url);

                    for (var _i in _config.data) {
                        _tmp.searchParams.set(_i, _config.data[_i]);
                    }

                    _url = _tmp.toString();
                }
                else if (_config.data && _config.dataType == 'JSON') {
                    _init.body = JSON.stringify(_config.data);
                }
                else if(_config.data) {
                    _init.body = _config.data;
                }

                const _request = new Request(_url, _init);
                const _response = await fetch(_request);

                if (!_response.ok) {

                    if (!_config.execFail) {
                        throw new Error(`Response status: ${_response.status}`);
                    }

                    _config.execFail(_response.body);
                    throw new Error(`Response status: ${_response.status}`);
                }

                if (_response.ok) {

                    if (!_config.execDone) {
                        return true;
                    }

                    _config.execDone(_response.body);
                }
            }
        }
    };
}

window.addEventListener('load', (_e) => {
    fpcm.pub.init();
});