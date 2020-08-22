/**
 * FanPress CM javascript webworker bootstrapper
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

onmessage = function(_msg) {

    if (_msg.data.namespace && _msg.data.function) {

        this.postMessage({
            ns: _msg.data.namespace,
            func: _msg.data.function,
            param: _msg.data.param ? _msg.data.param : null,
            intvl: _msg.data.interval ? _msg.data.interval : null
        });

    }

}