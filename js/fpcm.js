/**
 * FanPress CM public javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

if (fpcm === undefined) {
    var fpcm = {};
}

window.onload = function() {
    
    if (typeof jQuery !== 'undefined') {
        
        fpcm.pub = {

            init: function () {

                jQuery.noConflict();
                
                if (!window.fpcm.vars.ui.messages) {
                    window.fpcm.vars.ui.messages = [];
                }

                fpcm.pub.doAjax({
                    action: 'refresh',
                    data: {
                        t: 1
                    }
                });

                jQuery('.fpcm-pub-commentsmiley').click(function () {
                    fpcm.pub.insert(' ' + jQuery(this).attr('data-code') + ' ');
                    return false;
                });

                jQuery('.fpcm-pub-readmore-text').hide();
                jQuery('.fpcm-pub-readmore-link').click(function () {
                    jQuery('#fpcm-pub-readmore-text-' + jQuery(this).attr('id')).fadeToggle();
                    return false;
                });

                jQuery('.fpcm-pub-mentionlink').click(function () {
                    fpcm.pub.insert('@#' + jQuery(this).attr('id') + ': ');
                    return false;
                });

                if (!fpcm.pub.shares) {
                    fpcm.pub.shares = {};
                }

                jQuery('a.fpcm-pub-sharebutton-count').click(function () {

                    var item = jQuery(this).attr('data-onclick');
                    if (fpcm.pub.shares[item] && (new Date()).getTime() - fpcm.pub.shares[item] < 30000) {
                        return false;
                    }

                    if (!fpcm.pub.shares[item]) {
                        fpcm.pub.shares[item] = 0;
                    }

                    fpcm.pub.shares[item] = (new Date()).getTime();
                    fpcm.pub.doAjax({
                        action: 'shareClick',
                        type: 'POST',
                        data: {
                            oid: jQuery(this).attr('data-oid'),
                            item: item
                        },
                        execDone: function(result) {

                            if (item !== 'likebutton') {
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

                    return item === 'likebutton' ? false : true;
                });

                if (fpcm.vars.ui.messages.length) {
                    var msg = null;
                    for (var i = 0; i < window.fpcm.vars.ui.messages.length; i++) {
                        fpcm.pub.addMessage(fpcm.vars.ui.messages[i]);
                    }
                } 

            },

            insert: function (smiliecode) {
                aTag = smiliecode;
                eTag = "";
                var input = jQuery('#newcommenttext')[0];

                input.focus();
                /* für Internet Explorer */
                if (typeof document.selection != 'undefined') {
                    /* Einfügen des Formatierungscodes */
                    var range = document.selection.createRange();
                    var insText = range.text;
                    range.text = aTag + insText + eTag;
                    /* Anpassen der Cursorposition */
                    range = document.selection.createRange();
                    if (insText.length == 0) {
                        range.move('character', -eTag.length);
                    } else {
                        range.moveStart('character', aTag.length + insText.length + eTag.length);
                    }
                    range.select();
                }
                /* für neuere auf Gecko basierende Browser */
                else if (typeof input.selectionStart != 'undefined')
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
                jQuery('#fpcm-messages').append(
                    '<div class="fpcm-pub-message-box fpcm-pub-message-' + msg.type +
                    '" id="msgbox-' + msg.id + '"><div class="fpcm-pub-message-box-text">' + msg.txt +
                    '</div></div>'
                );
            },
            
            doAjax: function (config) {

                var _params = {
                    url: fpcm.vars.ajaxActionPath + config.action,
                    async: config.async !== undefined ? config.async : true,
                    type: config.method ? config.method.toUpperCase() : 'GET'
                }

                if (config.data) {
                    _params.data = config.data;
                }

                if (config.dataType) {
                    _params.dataType = config.dataType;                    
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

        fpcm.pub.init();

        if (fpcm.pub.modules) {

            jQuery.each(fpcm.modules, function (idx, object) {

                if (!object.initAfter || typeof object.initAfter !== 'function') {
                    return true;
                }

                object.init();
            });

        }

    } else {
        console.error('jQuery is no loaded! Check if you included the libary in your page header or enable inclusion in FanPress CM ACP.');
    }

};
