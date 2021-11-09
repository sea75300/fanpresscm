/**
 * FanPress CM UI Pager Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui_dnd = {

    _source: null,
    
    initDnd: function(_params) {

        if (_params === undefined) {
            return false;
        }

        var _el = document.getElementById(_params.destination);
        var _opt = {
            animation: 300,
            chosenClass: 'opacity-50',
        };
        
        if (_params.dropCallback) {

            _opt.onEnd = function(_event) {
                _params.dropCallback(_event);
                return true;
            }

        }
        
        if (_params.moveCallback) {

            _opt.onMove = function(_event) {
                _params.moveCallback(_event);
                return true;
            };

        }
        
        if (_params.group) {
            _opt.group = _params.group;
        }

        return Sortable.create(_el, _opt);

    }

}