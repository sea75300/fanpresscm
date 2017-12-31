/**
 * FanPress CM javascript module installer functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

var fpcmModuleInstaller = function () {
    
    var self = this;
    
    this.responseData = '';    
    this.startTime = 0;    
    this.moduleListClass = '';    
    this.idx = 0;
    this.key = '';
    this.type = '';
    this.moduleIndex = '';
    this.moduleKeyCount = 0;
    
    this.init = function (type) {

        if (typeof fpcmUpdaterProgressbar == 'undefined') {
            jQuery('.fpcm-updater-progressbar').remove();
        } else {
            self.progressbar(0);            
        }

        self.startTime   = (new Date().getTime());
        self.moduleKeyCount = fpcmModuleKeys.length;

        jQuery('.fpcm-updater-list').empty();
        fpcm.ui.showLoader(true);

        self.idx = 1;
        self.progressbar(1);           
        self.runInstall(fpcmModuleKeys[0], 1, type);
        
        return false;
    };
    
    this.runInstall = function (key, moduleIndex, type) {

        self.moduleListClass     = 'fpcm-updater-list-'+ moduleIndex;
        self.moduleListSpinenrId = self.moduleListClass + '-spinner';
        
        fpcm.ui.appendHtml('.fpcm-updater-list', '<div class="' + self.moduleListClass + ' fpcm-ui-modules-installerbox"></div>');

        self.moduleListClass = '.' + self.moduleListClass;

        self.key = key;
        self.type = type;
        self.moduleIndex = moduleIndex;
        
        self.execRequest(fpcmUpdaterStartStep);

        return true;
    };
    
    this.execRequest = function(idx) {

        if (idx > fpcmUpdaterMaxStep) {
            return false;
        }

        var msgText = fpcmUpdaterMessages[idx + '_START'];

        if (idx == 1) {
            fpcm.ui.appendHtml(self.moduleListClass, '<p><span id="' + self.moduleListSpinenrId + '" class="fa fa-spinner fa-spin fa-fw fa-lg"></span><strong>' + fpcm.ui.translate('statusinfo').replace('{{modulekey}}', self.key.split('_version')[0]) + '</strong></p>');
            msgText = msgText.replace('{{pkglink}}', fpcmModuleUrl.replace('{{pkgkey}}', self.key));
        }

        fpcm.ui.assignHtml('div.fpcm-updater-progressbar div.fpcm-ui-progressbar-label', msgText);

        fpcm.ajax.post('packagemgr/mod' + self.type, {

            data: {
                step : idx,
                key  : self.key,
                midx : self.moduleIndex
            },
           
            execDone: function () {
                self.responseData = fpcm.ajax.fromJSON(fpcm.ajax.getResult('packagemgr/mod' + self.type));
                if (self.responseData.data === undefined) {
                    alert(fpcm.ui.translate('ajaxErrorMessage'));
                    return false;
                }

                if (self.responseData.code != self.responseData.data.current + '_' +1) {
                    fpcm.ui.showLoader(false);
                    fpcm.ui.appendHtml(self.moduleListClass, '<p class="fpcm-ui-important-text">' + fpcmUpdaterMessages[self.responseData.code] + '</p>');
                    return false;
                }

                fpcm.ui.appendHtml(self.moduleListClass, '<p>' + fpcmUpdaterMessages[self.responseData.code] + '</p>');

                if (self.responseData.code && self.responseData.data.current == fpcmUpdaterMaxStep) {
                    fpcm.ui.appendHtml(self.moduleListClass, '<p>' + fpcmUpdaterMessages['EXIT_1'] + '</p>');
                    jQuery('#' + self.moduleListSpinenrId).removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-check');

                    if (self.moduleKeyCount > self.idx) {
                        self.idx++;
                        self.progressbar(self.idx);           
                        self.runInstall(fpcmModuleKeys[(self.idx-1)], self.idx, self.type);            
                        return true;
                    }

                    self.addTimer();
                    return true;
                }

                self.execRequest(self.responseData.data.nextstep);

                return true;
            }
        });
        
        
        return true;
    };
    
    this.addTimer = function() {
        var updateTimer = ((new Date().getTime()) - self.startTime) / 1000;
        fpcm.ui.appendHtml('.fpcm-updater-list', '<p>' + fpcmUpdaterProcessTime + ': ' + updateTimer + 'sec</p>');
        fpcm.ui.assignHtml('div.fpcm-updater-progressbar div.fpcm-ui-progressbar-label', '');
        fpcm.ui.showLoader(false);
    };
    
    this.progressbar = function (pgValue) {
        
        if (typeof fpcmUpdaterProgressbar == 'undefined') return false;  

        fpcm.ui.progressbar('.fpcm-updater-progressbar', {
            max: fpcmProgressbarMax,
            value: pgValue
        });

    };
    
}

fpcm.moduleinstaller = {
    
    init: function() {
        fpcmModuleInstaller = new fpcmModuleInstaller();
        fpcmModuleInstaller.init(fpcmModulesMode);
    }
    
}