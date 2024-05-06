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
});

fpcm.lightbox.init();

if (fpcm.gsearch !== undefined) {
    fpcm.gsearch._lightbox = new PhotoSwipeLightBox({
        gallery: '#fpcm-id-search-global',
        children: 'a.fpcm.ui-link-fancybox',
        pswpModule: () => PhotoSwipeCore
    });

    fpcm.gsearch._lightbox .captionPlugin = new PhotoSwipeDynamicCaption(fpcm.lightbox, {
      type: 'below',
    });

    fpcm.gsearch._lightbox .init();
}
