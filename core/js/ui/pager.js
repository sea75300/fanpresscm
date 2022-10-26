/**
 * FanPress CM UI Pager Namespace
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.ui.initPager = function (params) {

    if (params === undefined) {
        params = {};
    }

    if (!fpcm.vars.jsvars.pager) {
        return false;
    }

    var backEl = fpcm.dom.fromId('pagerBack');
    var nextEl = fpcm.dom.fromId('pagerNext');
    var selectId = 'pageSelect';

    backEl.unbind('click');
    nextEl.unbind('click');

    var selectEl = fpcm.dom.fromId(selectId);
    selectEl.unbind('change');

    if (!params.backAction) {
        params.backAction = function () {
            fpcm.dom.fromTag(this).attr('href', fpcm.vars.jsvars.pager.linkString.replace('__page__', fpcm.vars.jsvars.pager.showBackButton));
        };
    }

    if (!params.nextAction) {
        params.nextAction = function () {
            fpcm.dom.fromTag(this).attr('href', fpcm.vars.jsvars.pager.linkString.replace('__page__', fpcm.vars.jsvars.pager.showNextButton));
        };
    }

    if (fpcm.vars.jsvars.pager.showBackButton) {
        backEl.removeClass('disabled');
        backEl.click(params.backAction);
    } else if (!fpcm.vars.jsvars.pager.showBackButton && backEl && !backEl.hasClass('disabled')) {
        backEl.addClass('disabled');
    }

    if (fpcm.vars.jsvars.pager.showNextButton) {
        nextEl.removeClass('disabled');
        nextEl.click(params.nextAction);
    } else if (!fpcm.vars.jsvars.pager.showNextButton && nextEl && !nextEl.hasClass('disabled')) {
        nextEl.addClass('disabled');
    }
    
    if (fpcm.vars.jsvars.pager.maxPages) {
        for (i = 1; i <= fpcm.vars.jsvars.pager.maxPages; i++) {
            if (!params.keepSelect) {
                selectEl.empty();
            }

            if (fpcm.vars.jsvars.pager.maxPages) {
                for (i = 1; i <= fpcm.vars.jsvars.pager.maxPages; i++) {
                    selectEl.append('<option ' + (fpcm.vars.jsvars.pager.currentPage === i ? 'selected' : '') + ' value="' + i + '">' + fpcm.ui.translate('GLOBAL_PAGER').replace('{{current}}', i).replace('{{total}}', fpcm.vars.jsvars.pager.maxPages) + '</option>');
                }
            }
        }
    }

    if (!params.selectAction) {
        params.selectAction = function (event, ui) {

            if (ui.value == fpcm.vars.jsvars.pager.currentPage) {
                return false;
            }

            if (ui.value == '1') {
                window.location.href = fpcm.vars.actionPath + fpcm.vars.jsvars.currentModule;
                return true;
            }

            window.location.href = fpcm.vars.actionPath + fpcm.vars.jsvars.currentModule + '&page=' + ui.value;
        };
    }

    fpcm.ui.selectmenu('#' + selectId, {
        change: params.selectAction
    });

}

fpcm.ui.togglePager = function (_hidden) {

    let _el = fpcm.ui.mainToolbar.find('.fpcm-ui-pager-element');
    if (!_el.length) {
        return false;
    }

    if (_hidden) {
        _el.addClass('fpcm-ui-hidden');
        return true;
    }

    _el.removeClass('fpcm-ui-hidden');
    return true;
}