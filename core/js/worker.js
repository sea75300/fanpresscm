/**
 * FanPress CM javascript webworker bootstrapper
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

var _workerQueue = {};
onmessage = function(_msg) {

    if (_msg.data.cmd  && _msg.data.cmd === 'remove' && _workerQueue[_msg.data.id]) {
        _workerQueue[_msg.data.id] = false;
        return true;
    }

    if (_workerQueue[_msg.data.id]) {
        console.warn(_msg.data.id + ' already running');
        return false;
    }

    _workerQueue[_msg.data.id] = true;
    if (_msg.data.namespace && _msg.data.function) {
        this.postMessage({
            ns: _msg.data.namespace,
            func: _msg.data.function,
            param: _msg.data.param ? _msg.data.param : null,
            intvl: _msg.data.interval ? _msg.data.interval : null,
            id: _msg.data.id
        });

        return true;

    }
}