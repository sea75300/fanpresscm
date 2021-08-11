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

        var _source;

        if (!_params.dragoverCallback) {
            _params.dragoverCallback = function (_event) {
                _event.preventDefault();
                
                if (_source === undefined) {
                    return false;
                }
                
                if (_event.delegateTarget.id == _source.id) {
                    return false;
                }
            }
        }

        fpcm.dom.bindEvent(
            _params.dropZone,
            'drop',
            function (_event, _ui) {
                _params.dropCallback(_event, _ui);
            }
        );

        fpcm.dom.bindEvent(
            _params.dragStartElement,
            'dragstart',
            function (_event, _ui) {
                _params.dragstartCallback(_event, _ui);
            }
        );

        fpcm.dom.bindEvent(
            _params.dragElement,
            'dragenter',
            function (_event, _ui) {
                _params.dragenterCallback(_event, _ui);
            }
        );

        fpcm.dom.bindEvent(
            _params.dragElement,
            'dragleave',
            function (_event, _ui) {
                _params.dragleaveCallback(_event, _ui);
            }
        );

        fpcm.dom.bindEvent(
            _params.dragElement,
            'dragover',
            function (_event, _ui) {
                _params.dragoverCallback(_event, _ui);
            }
        );

    }

}