/**
 * FanPress CM system javascript functions
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2015-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.gsearch = {

    _lightbox: null,

    init: function ()
    {

        fpcm.dom.bindEvent('#fpcm-id-search-global-btn', 'hidden.bs.dropdown', function (_e, _ui) {
            fpcm.dom.fromClass('fpcm.ui-search-global-results').remove();
            fpcm.dom.fromId('fpcm-id-search-global-text').val('');
        });

        fpcm.dom.bindEvent('#fpcm-id-search-global-btn', 'shown.bs.dropdown', function (_e, _ui) {
            fpcm.dom.fromId('fpcm-id-search-global-text').focus();
        });
        
        fpcm.dom.bindEvent('#fpcm-id-search-global-text', 'keydown', function (_e,_ui) {

            if (!_ui.value || _ui.value.length < 3) {
                return true;
            }

            fpcm.gsearch.results(_ui.value);
        }, false, true);
        
        fpcm.dom.bindClick('#fpcm-id-search-global-start', function () {
            
            var _sterm = fpcm.dom.fromId('fpcm-id-search-global-text').val();
            if (!_sterm || _sterm.length < 3) {
                return false;
            }
            
            fpcm.gsearch.results(_sterm);
        });
        
    },
    
    results: function (_sterm) {
        
        fpcm.ui.replaceIcon('fpcm-id-search-global-start', 'magnifying-glass-arrow-right', 'circle-notch fa-spin-pulse');
        
        fpcm.ajax.post('searchall', {
            quiet: true,
            data: {
                term: _sterm
            },
            execDone: function (_result) {

                fpcm.dom.fromClass('fpcm.ui-search-global-results').remove();

                let _list = '';
                let _resCss = 'dropdown-item-text fpcm ui-search-global-results text-truncate';

                if (_result.count < 1 || !_result.items) {
                    let _l = fpcm.ui.getIcon('list-ul', {
                        stack: 'ban fpcm-ui-important-text',
                        stackTop: true,
                    }) + ' ' + fpcm.ui.translate('GLOBAL_NOTFOUND2');
                    _list += `<div class="${_resCss}"><div class="alert alert-secondary mb-0" role="alert">${_l}</div></div>`;
                }
                else {
                    if (_result.count > _result.items.length) {
                        let _l = fpcm.ui.translate('LABEL_SEARCH_GLOBAL_RESULTSIZE').replace('{{result_count}}', _result.count);
                        _list += `<div class="${_resCss}"><div class="alert alert-warning" role="alert">${_l}</div></div>`;
                    }

                    for (var i = 0; i < _result.items.length; i++) {

                        if (!_result.items[i]) {
                            continue;
                        }

                        let link = _result.items[i].link;
                        let text = _result.items[i].text;
                        let icon = _result.items[i].icon;
                        let linkCss = _result.items[i].lightbox ? 'fpcm ui-link-fancybox' : '';

                        let linkData = '';
                        if (_result.items[i].lightbox) {
                            
                            _img = new Image();
                            _img.src = link;

                            linkData = `data-pswp-width="${_img.naturalWidth}" data-pswp-height="${_img.naturalHeight}"`;
                        }
                        

                        _list += `<div class="${_resCss}"><a href="${link}" target="_blank" class="text-truncate ${linkCss}" ${linkData}>${icon}${text}</a></div>`;
                    }
                }

                fpcm.dom.appendHtml('#fpcm-id-search-global', `<div class="fpcm ui-search-global-results"><hr class="dropdown-divider"></div>${_list}`);

                if (_result.lightbox) {
                    fpcm.ui.initLightbox();
                }
                
                fpcm.ui.replaceIcon('fpcm-id-search-global-start', 'circle-notch fa-spin-pulse', 'magnifying-glass-arrow-right');
            }
        });
        
    }

};