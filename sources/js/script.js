// Device menu is global, so keep it eager
import deviceMenu from '@js/common/deviceMenu.js';
deviceMenu();

// Load Home slider only if homepage element exists
if (document.querySelector('.home-slider')) {
    import('@js/common/swiperInit.js').then(({ initHomeSlider }) => {
        initHomeSlider();
    });
}

// Load Fancybox only if gallery/trigger elements exist
if (document.querySelector('[data-fancybox]')) {
    import('@js/common/fancybox').then(({ initFancybox }) => {
        initFancybox();
    });
}
