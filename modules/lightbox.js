import PhotoSwipeLightBox from './../lib/photoswipe/dist/photoswipe-lightbox.esm.min.js';
import PhotoSwipeCore from './../lib/photoswipe/dist/photoswipe.esm.min.js';
import PhotoSwipeDynamicCaption from './../lib/photoswipe/dist/photoswipe-dynamic-caption-plugin.esm.js';

fpcm.lightbox = new PhotoSwipeLightBox({
    gallery: '#fpcm-tab-files-list-pane',
    children: 'a.fpcm.ui-link-fancybox',
    pswpModule: () => PhotoSwipeCore
});

fpcm.lightbox.captionPlugin = new PhotoSwipeDynamicCaption(fpcm.lightbox, {
    type: 'below',
    captionContent: (_slide) => {

        let _el = _slide.data.element;
        let _img = _el.querySelector('img');
        if (_img) {
            return _img.getAttribute('alt');
        }

        return _el.dataset.pswpCaption;
    }
});

fpcm.lightbox.init();

if (fpcm.gsearch !== undefined) {

    fpcm.gsearch._lightbox = new PhotoSwipeLightBox({
        gallery: '#fpcm-id-search-global',
        children: 'a.fpcm.ui-link-fancybox',
        pswpModule: () => PhotoSwipeCore
    });
    
    fpcm.gsearch._lightbox.captionPlugin = new PhotoSwipeDynamicCaption(fpcm.lightbox, {
        type: 'below',
        captionContent: (_slide) => {

            let _el = _slide.data.element;
            let _img = _el.querySelector('img');
            if (_img) {
                return _img.getAttribute('alt');
            }

            return _el.dataset.pswpCaption;
        }
    });

    fpcm.gsearch._lightbox .init();
}

if (fpcm.article !== undefined) {

    let _btnEl = document.getElementById('articleimg');

    if (_btnEl && _btnEl.dataset.pswpWidth === undefined && _btnEl.dataset.pswpHeight === undefined) {
        let _imgObj = new Image();
        _imgObj.src = _btnEl.href;
        _imgObj.onload = function () {
            _btnEl.setAttribute('data-pswp-width',_imgObj.naturalWidth);
            _btnEl.setAttribute('data-pswp-height', _imgObj.naturalHeight);
        };
    }

    fpcm.article._lightbox = new PhotoSwipeLightBox({
        gallery: '#fpcm-ui-toolbar',
        children: 'a.fpcm.ui-link-fancybox',
        pswpModule: () => PhotoSwipeCore
    });

    fpcm.article._lightbox .init();
}
