/**
 * FanPress CM File Uploader Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2017-2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.fileuploader = {

    init: function() {

        fpcm.fileuploader._hasDomList = fpcm.dom.fromId('fpcm-id-upload-list').length ? true : false;
        
        fpcm.dom.bindClick('#btnAddFile', function (_e, _ui) {

            fpcm.fileuploader.emptyFileList();

            if (_ui.dataset.clickTrigger != undefined) {               
                fpcm.dom.fromId('fpcm-id-' + _ui.dataset.clickTrigger).click();
            }
            else {
                fpcm.dom.fromTag(this).parent().find('.fpcm-ui-fileinput-select').trigger('click');
            }

            fpcm.dom.bindEvent('.fpcm-ui-fileinput-select', 'change', function (_e, _ui) {

                fpcm.fileuploader.emptyFileList();
                if (!_ui.files) {
                    return false;
                }

                let _html = '';
                let _icon = fpcm.ui.getIcon('file-image', {
                    prefix: 'far',
                    size: 'lg'
                });
                
                for (var _i=0;_i<_ui.files.length;_i++) {
                    _html += `<div class="list-group-item">${_icon} ${_ui.files[_i].name}</div>`
                }
                
                _html += '';
                
                fpcm.dom.appendHtml('#fpcm-id-upload-list', _html);
            });

        });

        fpcm.dom.bindClick('#btnCancelUpload', function () {
            fpcm.dom.fromClass('fpcm-ui-fileinput-select').empty();
            fpcm.fileuploader.emptyFileList();
        });

    },
    
    emptyFileList: function() {

        if (!fpcm.fileuploader._hasDomList) {
            return false;
        }

        fpcm.dom.fromId('fpcm-id-upload-list').empty();
        return true;
    }


};