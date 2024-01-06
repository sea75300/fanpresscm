import PhotoSwipeLightBox from './../lib/photoswipe/dist/photoswipe-lightbox.esm.min.js';
import PhotoSwipeCore from './../lib/photoswipe/dist/photoswipe.esm.min.js';

fpcm.lightbox = new PhotoSwipeLightBox({
    gallery: '#fpcm-tab-files-list-pane',
    children: 'a.fpcm.ui-link-fancybox',
    pswpModule: () => PhotoSwipeCore
});

fpcm.lightbox.init();