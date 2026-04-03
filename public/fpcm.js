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
            getFieldValue: function(_id) {

                let _field = document.getElementById(_id);
                if (!_field) {
                    return '';
                }

                if (_field.type === 'checkbox') {
                    return _field.checked ? 1 : 0;
                }

                return _field.value;
            },
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

                let _first = _el.substring(0,1);
                if (_first === '#') {
                    _el = document.getElementById(_el.substr(1));
                }
                else if (_first === '.') {
                    _el = document.getElementsByClassName(_el.substr(1));
                }
                else {
                    _el = document.querySelectorAll(_el);
                }

                if (!_el) {
                    return;
                }

                if (_el instanceof HTMLCollection || _el instanceof NodeList) {

                    for (var _subEl of _el) {
                        _subEl.addEventListener('click', _callback);
                    }

                    return;
                }

                _el.addEventListener('click', _callback);
            },
            getArticleId: function () {
                let _sp = new URLSearchParams(location.href);
                let _id = _sp.get('id');
                if (!_id) {
                    return false;
                }

                _id = _id.split('-');

                return parseInt(_id[0]);
            }
        },
        pub: {
            init: function () {

                fpcm.pub.doRefresh();
                fpcm.pub.showMessages();

                if (fpcm.vars.jsvars.commentsCount) {
                    fpcm.pub.loadComments();
                }

                fpcm.system.bindClick(
                    '.fpcm-pub-commentsmiley',
                    (_ev) => {
                        _ev.preventDefault();
                        fpcm.pub.insert(' ' + _ev.currentTarget.dataset.code + ' ');
                    }
                );

                fpcm.system.bindClick(
                    '#btnSendComment',
                    (_ev) => {
                        _ev.preventDefault();

                        fpcm.pub.doAjax({
                            action: 'pub/comments',
                            method: 'POST',
                            data: {
                                action: 'save',
                                oid: fpcm.system.getArticleId(),
                                commentCaptcha: fpcm.system.getFieldValue('commentCaptcha'),
                                comment: {
                                    name: fpcm.system.getFieldValue('newcommentname'),
                                    email: fpcm.system.getFieldValue('newcommentemail'),
                                    website: fpcm.system.getFieldValue('newcommentwebsite'),
                                    text: fpcm.system.getFieldValue('newcommenttext'),
                                    private: fpcm.system.getFieldValue('newcommentprivate'),
                                    privacy: fpcm.system.getFieldValue('newcommentprivacy'),
                                }
                            },
                            execDone: (_result) => {

                                if (_result.txt && _result.type) {
                                    fpcm.pub.addMessage(_result);

                                    if (_result.type === 'error') {
                                        return false;
                                    }
                                }

                                fpcm.pub.loadComments();
                            }
                        });
                    }
                );

                fpcm.system.bindClick(
                    'a.fpcm-pub-sharebutton-count',
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
                            action: 'pub/shareClick',
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

                    if (!fpcm.modules[_m] instanceof Object) {
                        console.error(`Item ${_m} must be an object`);
                        continue;
                    }

                    let _mod = fpcm.modules[_m];
                    if (!_mod.init || typeof _mod.init !== 'function') {
                        continue;
                    }

                    try {
                        _mod.init();
                    } catch (_e) {
                        console.error(`Error while initializing module ${_m}!\n\n${_e}`);
                    }

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

                _msgWrapper.innerHTML = '';

                if (fpcm.vars.ui.messages.length) {
                    fpcm.vars.ui.messages = [];
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

                if (fpcm.vars.ajaxRefreshDisable || !fpcm.vars.ajaxActionPath) {
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

            loadComments: function() {

                let _cbox = document.getElementById('fpcm-pub-comments');
                if (!_cbox) {
                    return false;
                }

                let _spinner = document.createElement('img');
                _spinner.src = fpcm.vars.jsvars.spinnerUrl;
                _spinner.classList.add('fpcm-pub-spinner');

                _cbox.innerHTML = '';
                _cbox.appendChild(_spinner);

                fpcm.pub.doAjax({
                    action: 'pub/comments',
                    method: 'POST',
                    data: {
                        action: 'getList',
                        oid: fpcm.system.getArticleId()
                    },
                    execDone: (_result) => {

                        if (_cbox.innerHTML) {
                            _cbox.innerHTML = '';
                        }

                        for (let _box of _result) {
                            _cbox.insertAdjacentHTML('beforeend', _box);
                        }

                        fpcm.system.bindClick(
                            '.fpcm-pub-mentionlink',
                            (_ev) => {
                                _ev.preventDefault();
                                fpcm.pub.insert('@#' + _ev.currentTarget.id + ': ');
                            }
                        );

                    }
                });

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

                const _init = {
                    method: _config.method.toUpperCase(),
                    headers: []
                };

                if (_config.dataType) {
                    _init.headers['Content-Type'] = _config.dataType;
                }

                if (_config.data && _config.method === 'GET') {

                    let _tmp = new URL(_url);

                    for (var _i in _config.data) {
                        _tmp.searchParams.set(_i, _config.data[_i]);
                    }

                    _url = _tmp.toString();
                }
                else if (_config.data && _config.data instanceof Object) {

                    let _body = new FormData();

                    for (var _i in _config.data) {

                        let _val =_config.data[_i];


                        if (_config.dataType === 'application/json') {
                            _val = JSON.stringify(_config.data)
                        }

                        if (_val instanceof Object) {

                            for (var _x in _val) {
                                _body.append(`${_i}[${_x}]`, _val[_x]);
                            }

                            continue;
                        }

                        _body.append(_i, _val);
                    }

                    _init.body = _body;
                }
                else if(_config.data) {
                    _init.body = _config.data;
                }

                try {
                    const _request = new Request(_url, _init);
                    const _response = await fetch(_request);

                    if (!_response.ok) {

                        if (!_config.execFail) {
                            throw new Error(`Response status: ${_response.status}`);
                        }

                        if (_response.headers.get('content-type') === 'application/json') {
                            let _result = await _response.json();
                            _config.execDone(_result);
                        }

                        if (_response.headers.get('content-type').substr(0,9) === 'text/html') {
                            let _result = await _response.text();
                            _config.execDone(_result);
                        }

                        _config.execFail();
                    }

                    if (_response.ok) {

                        if (!_config.execDone) {
                            return true;
                        }

                        if (_response.headers.get('content-type') === 'application/json') {
                            let _result = await _response.json();
                            _config.execDone(_result);
                            return true;
                        }

                        if (_response.headers.get('content-type').substr(0,9) === 'text/html') {
                            let _result = await _response.text();
                            return true;
                        }

                        _config.execDone();
                    }

                }
                catch (_e) {
                    console.warn(_e);
                }

            }
        }
    };
}

window.addEventListener('load', (_e) => {
    fpcm.pub.init();
});