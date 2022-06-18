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
        
        if (params.pageToken) {            
            params.data._token  = fpcm.vars.jsvars.pageTokens[params.pageToken]
                                ? fpcm.vars.jsvars.pageTokens[params.pageToken]
                                : null;
        }

        if (!params.quiet) {
            fpcm.ui_loader.show(params.loaderMsg ? params.loaderMsg : null);
        }

        jQuery.ajax({
            url         : fpcm.vars.ajaxActionPath + action.replace(fpcm.vars.ajaxActionPath, ''),
            type        : params.method.toUpperCase(),
            data        : params.data,
            async       : params.async,
            dataType    : params.dataType ? params.dataType : null,
            cache       : params.cache !== undefined ? params.cache : true,
            processData : params.processData !== undefined ? params.processData : true,
            contentType : params.contentType !== undefined ? params.contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
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
        fpcm.ui.addMessage({
            txt: 'AJAX_RESPONSE_ERROR',
            type: 'error'
        }, true );
    },
    
    execFunction: function (_action, _function, _params) {
        _params.data.fn = _function;
        _params.dataType = 'json';
        fpcm.ajax.post(_action, _params);
    },
    
    getItemList: function (_params) {
        
        if (!_params.module) {
            console.error('Invalid module given.');
            return false;
        }
        
        if (!_params.dataType) {
            _params.dataType = 'json';
        }

        let _data = {
            mode: _params.mode ? _params.mode : null,
            page: _params.page !== undefined ? parseInt(_params.page) : 1,    
        };
        
        if (_params.filter instanceof Object) {
            _data.filter = _params.filter;
        }

        fpcm.ajax.post(_params.module + '/lists', {
            data: _data,
            quiet: (_data.filter !== undefined && _data.filter !== null ) || _params.loader ? false : true,
            execDone: function (result)
            {
                if (!result) {
                    return false;
                }
                
                if (_params.dataType !== 'json') {
                    fpcm.dom.assignHtml(_params.destination, result);
                    _params.onAssignHtmlAfter(result);
                    return true;
                }
                
                if (result.html) {
                    fpcm.ui.togglePager(_params.filter ? true : false);
                    fpcm.dom.assignHtml(_params.destination, result.html);
                    _params.onAssignHtmlAfter(result);
                    return true;
                }

                if (result.message && result.message.txt && result.message.type) {
                    fpcm.ui.addMessage(result.message);
                    return false;
                }
                
                fpcm.vars.jsvars.dataviews[result.dataViewName] = result.dataViewVars;
                fpcm.dataview.updateAndRender(result.dataViewName, {
                    onRenderAfter: _params.onRenderDataViewAfter
                });
                
                if (_params.filter) {
                    fpcm.ui.togglePager(true);
                }
                else if (result.pager && !_params.filter) {
                    fpcm.ui.togglePager(false);
                    fpcm.vars.jsvars.pager.currentPage = result.pager.currentPage;
                    fpcm.vars.jsvars.pager.maxPages = result.pager.maxPages;
                    fpcm.vars.jsvars.pager.showBackButton = result.pager.showBackButton;
                    fpcm.vars.jsvars.pager.showNextButton = result.pager.showNextButton;

                    fpcm.ui.initPager({
                        nextAction: function (event, ui) {

                            if (!fpcm.vars.jsvars.pager.showNextButton || fpcm.vars.jsvars.pager.currentPage >= fpcm.vars.jsvars.pager.maxPages) {
                                return false;
                            }

                            _params.onPagerNext(event, ui);
                            return true;
                        },
                        backAction: function (event, ui) {
                                
                            if (!fpcm.vars.jsvars.pager.showBackButton) {
                                return false;
                            }

                            _params.onPagerBack(event, ui);
                            return true;

                        },
                        selectAction: function( event, ui ) {
                            
                            if (ui.item.value == fpcm.vars.jsvars.pager.currentPage) {
                                return false;
                            }

                            _params.onPagerSelect(event, ui);
                            return true;
                        }
                    });
                }

                return true;
            }
        });

    }
    
};