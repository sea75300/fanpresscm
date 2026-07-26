/**
 * FanPress CM Editor Videolinks Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor_videolinks = {

    hasLink: function(text) {

        if (text.search('<iframe') >= 0) {
            return false;
        }

        if (text.search('youtube.com') >= 0 && text.search('watch') >= 0) {
            return true;
        }

        if (text.search('vimeo.com') >= 0) {
            return true;
        }

        if (text.search('dailymotion.com/video/') >= 0) {
            return true;
        }

        if (text.search('twitter.com/i/videos/tweet') >= 0) {
            return true;
        }
        
        return false;
    },

    replace: function (text) {
        if (text.search('youtube.com') >= 0 && text.search('watch') >= 0) {
            return text.replace('watch?v=', 'embed/').replace('youtube.com', 'youtube-nocookie.com').replace(/(&amp;).*/i, '').replace(/\&.*/i, '');
        }

        if (text.search('vimeo.com') >= 0) {
            return text.replace('vimeo.com', 'player.vimeo.com/video');
        }

        if (text.search('dailymotion.com/video/') >= 0) {
            return text.replace('/video', '/embed/video');
        }

        if (text.search('twitter.com/i/videos/tweet') >= 0) {
            var val = text.split('?');
            return val[0];
        }

        return text;
    },

    createFrame: function (url, returnOnly) {
        
        if (url === undefined) {
            url = 'http://';
        }

        var code = fpcm.ui.createIFrame({
            src: url,
            options: ['width="500"', 'height="300"', 'frameborder="0"', 'allowfullscreen'],
            id: 'fpcm-articletext-videoframe-' + fpcm.ui.getUniqueID()
        });

        if (!returnOnly) {
            fpcm.editor.insert(code, '');
            return true;
        }

        return code;
    }

};