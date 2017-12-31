/**
 * FanPress CM Editor Videolinks Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.editor_videolinks = {

    replace: function(text) {

        if (text.search('youtube.com') >= 0 && text.search('watch') >= 0) {
            return text.replace('watch?v=', 'embed/');            
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
        return fpcm.editor.insertFrame(url, ['width="500"', 'height="300"', 'frameborder="0"', 'allowfullscreen'], returnOnly);
    }

};