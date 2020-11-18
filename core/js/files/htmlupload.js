/**
 * FanPress CM File Uploader Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.fileuploader = {
    
    _hasDomList: false,

    init: function() {

        fpcm.fileuploader._hasDomList = fpcm.dom.fromId('fpcm-ui-phpupload-filelist').length ? true : false;

        fpcm.dom.fromId('btnAddFile').click(function () {

            fpcm.fileuploader.emptyFileList();

            fpcm.dom.fromTag(this).parent().find('.fpcm-ui-fileinput-select').trigger('click');
            fpcm.dom.fromClass('fpcm-ui-fileinput-select').change(function () {

                fpcm.fileuploader.emptyFileList();
                if (!fpcm.fileuploader._hasDomList || !fpcm.dom.fromTag(this)[0] || !fpcm.dom.fromTag(this)[0].files) {
                    return false;
                }

                var fileList = fpcm.dom.fromTag(this)[0].files;
                for (var i=0;i<fileList.length;i++) {
                    fpcm.ui.appendHtml('#fpcm-ui-phpupload-filelist', '<div class="row no-gutters fpcm-ui-padding-md-tb"><div class="col-12 fpcm-ui-margin-md-top fpcm-ui-margin-md-bottom">' + fpcm.ui.getIcon('file-image', { prefix: 'far', size: 'lg' }) + fileList[i].name +'</div></div>')
                }
                
                fpcm.dom.fromId('fpcm-ui-fileupload-list').fadeIn();
                return false;
            });

            return false;

        });

        fpcm.dom.fromId('btnUploadFile').click(function () {
            fpcm.ui_loader.show();
        });

        fpcm.dom.fromId('btnCancelUpload').click(function () {
            fpcm.dom.fromId('fpcm-ui-fileupload-list').fadeOut();
            fpcm.dom.fromClass('fpcm-ui-fileinput-select').empty();
            fpcm.fileuploader.emptyFileList();
        });
    },
    
    emptyFileList: function() {

        if (!fpcm.fileuploader._hasDomList) {
            return false;
        }

        fpcm.dom.fromId('fpcm-ui-phpupload-filelist').empty();
        return true;
    }


};