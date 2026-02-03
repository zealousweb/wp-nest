"use strict";
(globalThis["webpackChunkwpnest"] = globalThis["webpackChunkwpnest"] || []).push([["sources_js_common_swiperInit_js"],{

/***/ "./sources/js/common/swiperInit.js"
/*!*****************************************!*\
  !*** ./sources/js/common/swiperInit.js ***!
  \*****************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initHomeSlider: () => (/* binding */ initHomeSlider),
/* harmony export */   initTestimonialSlider: () => (/* binding */ initTestimonialSlider)
/* harmony export */ });
/* harmony import */ var swiper_bundle__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! swiper/bundle */ "./node_modules/.pnpm/swiper@12.0.3/node_modules/swiper/swiper-bundle.mjs");
/* harmony import */ var swiper_modules__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! swiper/modules */ "./node_modules/.pnpm/swiper@12.0.3/node_modules/swiper/modules/index.mjs");
/* harmony import */ var swiper_css_bundle__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! swiper/css/bundle */ "./node_modules/.pnpm/swiper@12.0.3/node_modules/swiper/swiper-bundle.css");
// swiperInit.js



const initHomeSlider = () => {
  return new swiper_bundle__WEBPACK_IMPORTED_MODULE_0__["default"]('.home-slider', {
    modules: [swiper_modules__WEBPACK_IMPORTED_MODULE_1__.Navigation, swiper_modules__WEBPACK_IMPORTED_MODULE_1__.Pagination],
    loop: true,
    navigation: {
      nextEl: '.home-next',
      prevEl: '.home-prev'
    },
    pagination: {
      el: '.home-pagination',
      clickable: true
    }
  });
};
const initTestimonialSlider = () => {
  return new swiper_bundle__WEBPACK_IMPORTED_MODULE_0__["default"]('.testimonial-slider', {
    modules: [swiper_modules__WEBPACK_IMPORTED_MODULE_1__.Navigation, swiper_modules__WEBPACK_IMPORTED_MODULE_1__.Pagination],
    loop: true,
    slidesPerView: 2,
    spaceBetween: 30,
    pagination: {
      el: '.testimonial-pagination',
      clickable: true
    },
    breakpoints: {
      768: {
        slidesPerView: 1
      }
    }
  });
};

/***/ }

}]);
//# sourceMappingURL=sources_js_common_swiperInit_js.js.map