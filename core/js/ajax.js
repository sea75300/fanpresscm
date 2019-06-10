/**
 * FanPress CM AJAX Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ajax = {
    
    result   : [],
    workData : [],
    
    exec: function(action, params, _legacy) {

        if (!params) {
            params = {};
        }

        if (!params.method) {
            params.method = 'POST';
        }

        if (params.async === undefined) {
            params.async = true;
        }

        if (!params.data) {
            params.data = [];
        }

        if (!params.execDone) {
            params.execDone = false;
        }

        if (!params.execFail) {
            params.execFail = false;
        }

        if (params.workData) {
            fpcm.ajax.workData[action] = params.workData;
        }

        jQuery.ajax({
            url         : fpcm.vars.ajaxActionPath + action,
            type        : params.method.toUpperCase(),
            data        : params.data,
            async       : params.async,
            statusCode: {
                500: function() {
                    fpcm.ajax.showAjaxErrorMessage();
                    fpcm.ui.showLoader();
                },
                401: function() {
                    fpcm.system.showSessionCheckDialog();
                    fpcm.ui.showLoader();
                },
                404: function() {
                    fpcm.ajax.showAjaxErrorMessage();
                    fpcm.ui.showLoader();
                }
            }
        })
        .done(function(result) {

            if (result.search('FATAL ERROR:') === 3) {
                console.error(fpcm.ui.translate('AJAX_RESPONSE_ERROR'));
                console.error('ERROR MESSAGE: ' + errorThrown);
                fpcm.ui.showLoader();
            }

            if (result.cmd !== undefined) {
                eval(result.cmd);
                return true;
            }

            fpcm.ajax.result[action] = result;
            if (typeof params.execDone == 'string') {
                eval(params.execDone);
            }

            if (typeof params.execDone == 'function') {
                params.execDone(result);
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error(fpcm.ui.translate('AJAX_RESPONSE_ERROR'));
            console.error('STATUS MESSAGE: ' + textStatus);
            console.error('ERROR MESSAGE: ' + errorThrown);
            fpcm.ui.showLoader();

            if (typeof params.execFail == 'string') {
                eval(params.execFail);
            }

            if (typeof params.execFail == 'function') {
                params.execFail();
            }
        });   

    },
    
    get: function(action, params) {
        
        if (!params) {
            params = {};
        }
        
        params.method = 'GET';
        fpcm.ajax.exec(action, params);
    },
    
    post: function(action, params) {

        if (!params) {
            params = {};
        }

        params.method = 'POST';
        fpcm.ajax.exec(action, params);
    },

    getResult: function(action, isJson) {
        return fpcm.ajax.result[action] ? (isJson ? fpcm.ajax.fromJSON(fpcm.ajax.result[action]) : fpcm.ajax.result[action])  : null;
    },

    getWorkData: function(action) {
        return fpcm.ajax.workData[action] ? fpcm.ajax.workData[action] : null;
    },

    fromJSON: function(data) {
        
        if (data instanceof Object || data instanceof Array) {
            return data;
        }

        return JSON.parse(data);
    },
    
    toJSON: function(data) {

        var isArray = data instanceof Array ? true : false;
        var isObject = data instanceof Object ? true : false;
        if (!isArray && !isObject) {
            return '';
        }

        return JSON.stringify(data);
    },
    
    showAjaxErrorMessage: function () {
        fpcm.ui.addMessage({ txt: 'AJAX_RESPONSE_ERROR', type: 'error' }, true);
    }
    
};