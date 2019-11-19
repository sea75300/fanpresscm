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
    
    exec: function(action, params) {

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
        
        if (params.pageToken) {            
            params.data._token  = fpcm.vars.jsvars.pageTokens[params.pageToken]
                                ? fpcm.vars.jsvars.pageTokens[params.pageToken]
                                : null;
        }

        if (!params.quiet) {
            fpcm.ui_loader.show(params.loaderMsg ? params.loaderMsg : null);
        }

        jQuery.ajax({
            url         : fpcm.vars.ajaxActionPath + action,
            type        : params.method.toUpperCase(),
            data        : params.data,
            async       : params.async,
            dataType    : params.dataType ? params.dataType : null,
            cache       : params.cache !== undefined ? params.cache : true,
            statusCode: {
                500: function() {
                    fpcm.ajax.showAjaxErrorMessage();
                    fpcm.ui_loader.hide();
                },
                400: function() {
                    fpcm.ui.addMessage({
                        txt: 'CSRF_INVALID',
                        type: 'error'
                    })
                    fpcm.ui_loader.hide();
                },
                401: function() {
                    fpcm.system.showSessionCheckDialog();
                    fpcm.ui_loader.hide();
                },
                404: function() {
                    fpcm.ajax.showAjaxErrorMessage();
                    fpcm.ui_loader.hide();
                }
            }
        })
        .done(function(result) {

            if (result.search && result.search('FATAL ERROR:') === 3) {
                console.error(fpcm.ui.translate('AJAX_RESPONSE_ERROR'));
                console.error('ERROR MESSAGE: ' + errorThrown);
                fpcm.ui_loader.hide();
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
            
            if (!params.quiet) {
                fpcm.ui_loader.hide();
            }

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            
            if (textStatus == 'parsererror') {
                fpcm.ajax.showAjaxErrorMessage();
                fpcm.ui_loader.hide();
            }

            console.error(fpcm.ui.translate('AJAX_RESPONSE_ERROR'));
            console.error('STATUS MESSAGE: ' + textStatus);
            console.error('ERROR MESSAGE: ' + errorThrown);
            fpcm.ui_loader.hide();

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
        console.warn('fpcm.ajax.getResult is deprecated as of FPCM 4.3. Uuse "result" parameter in execDone-callback instead.');
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