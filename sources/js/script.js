import { initHomeSlider } from '@js/common/swiperInit.js';
import deviceMenu from '@js/common/deviceMenu.js';
import { initFancybox } from '@js/common/fancybox.js';

import { removeClass } from 'wpnestjs';

initHomeSlider();
deviceMenu();
initFancybox();

removeClass('.home','wp-theme-wp-nest,blog');