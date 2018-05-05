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

                jQuery.ajax({
                    url: fpcm.vars.ajaxActionPath + 'cronasync',
                    type: 'GET'
                });

                jQuery('.fpcm-pub-commentsmiley').click(function () {
                    fpcm.pub.insert(' ' + jQuery(this).attr('smileycode') + ' ');
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

                if (window.fpcm.vars.ui.messages && fpcm.vars.ui.messages.length) {
                    var msg = null;
                    for (var i = 0; i < window.fpcm.vars.ui.messages.length; i++) {
                        msg = fpcm.vars.ui.messages[i];
                        jQuery('#fpcm-messages').append('<div class="fpcm-pub-message-box fpcm-pub-message-' + msg.type +
                                                        '" id="msgbox-' + msg.id + '"><div class="fpcm-pub-message-box-text">' + msg.txt +
                                                        '</div></div>');
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

        }

        fpcm.pub.init();

    } else {
        console.error('jQuery is no loaded! Check if you included the libary in your page header or enable inclusion in FanPress CM ACP.');
    }

};
