"use strict";
(globalThis["webpackChunkwpnest"] = globalThis["webpackChunkwpnest"] || []).push([["main"],{

/***/ "./sources/js/common/deviceMenu.js"
/*!*****************************************!*\
  !*** ./sources/js/common/deviceMenu.js ***!
  \*****************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const DeviceMenu = () => {
  var _document$querySelect2;
  /* Responsive Navigation */
  const hamBurger = document.querySelector('.hamburger');
  const overlay = document.querySelector('.mbnav__backdrop');
  const mbnav = document.querySelector('.mbnav');
  const menuWrap = document.querySelector('.mbnav .menu-wrap');
  if (!hamBurger || !mbnav) {
    return;
  }
  const menuClose = () => {
    hamBurger.classList.remove('is-clicked');
    document.body.classList.remove('scroll-fixed');
    mbnav.classList.remove('is-open');
    if (menuWrap) {
      var _document$querySelect;
      const menuItems = menuWrap.querySelectorAll('li');
      menuItems.forEach(item => item.classList.remove('is-open'));
      (_document$querySelect = document.querySelector('.mbnav__inner > .menu-wrap')) === null || _document$querySelect === void 0 || _document$querySelect.style.setProperty('--leftSlide', '0');
    }
  };

  /* Mobile overlay click */
  if (overlay) {
    overlay.addEventListener('click', () => {
      menuClose();
    });
  }
  hamBurger.addEventListener('click', function () {
    if (hamBurger.classList.contains('is-clicked')) {
      menuClose();
    } else {
      hamBurger.classList.add('is-clicked');
      mbnav.classList.add('is-open');
      document.body.classList.add('scroll-fixed');
    }
  });
  const clickable = (_document$querySelect2 = document.querySelector('.mbnav__state')) === null || _document$querySelect2 === void 0 ? void 0 : _document$querySelect2.getAttribute('data-clickable');
  const listItemsWithSubMenu = document.querySelectorAll('.mbnav li:has(ul)');
  listItemsWithSubMenu.forEach(item => item.classList.add('has-sub'));
  const subMenus = document.querySelectorAll('.mbnav li > ul');
  subMenus.forEach(subMenu => subMenu.classList.add('sub-menu'));
  const hasSubLinks = document.querySelectorAll('.mbnav .has-sub > a');
  hasSubLinks.forEach(link => {
    const caret = document.createElement('em');
    caret.classList.add('mbnav__caret');
    link.after(caret);
  });
  const subMenuItems = document.querySelectorAll('.mbnav ul > li:has(ul.sub-menu)');
  subMenuItems.forEach(item => {
    const subMenu = item.querySelector(':scope > ul');
    const parentLink = item.querySelector(':scope > a');
    if (subMenu && parentLink) {
      const backClick = document.createElement('li');
      backClick.classList.add('back-click');
      backClick.textContent = parentLink.textContent.trim();
      subMenu.prepend(backClick);
    }
  });
  if (clickable === 'true') {
    const caretElements = document.querySelectorAll('.mbnav .has-sub > .mbnav__caret');
    caretElements.forEach(caret => caret.classList.add('mbnav__caret'));
  } else {
    const nonClickableLinks = document.querySelectorAll('.mbnav .has-sub > a');
    nonClickableLinks.forEach(link => {
      link.classList.add('mbnav__caret');
      link.setAttribute('href', 'javascript:;');
    });
  }

  // === First part: wrapAll ===
  const menuInner = document.querySelector('.mbnav__inner');
  const children = Array.from(menuInner.children);
  const outerWrap = document.createElement('div');
  outerWrap.classList.add('menu-wrap');
  const innerWrap = document.createElement('div');
  innerWrap.classList.add('menu-inner');
  children.forEach(child => innerWrap.appendChild(child));
  outerWrap.appendChild(innerWrap);
  menuInner.appendChild(outerWrap);
  const submenuElements = document.querySelectorAll('.mbnav__inner ul li.has-sub ul');
  submenuElements.forEach(subMenu => {
    const menuWrap = document.createElement('div');
    menuWrap.classList.add('menu-wrap');
    const innerWrap = document.createElement('div');
    innerWrap.classList.add('menu-inner');
    subMenu.parentNode.replaceChild(menuWrap, subMenu);
    innerWrap.appendChild(subMenu);
    menuWrap.appendChild(innerWrap);
  });

  // Open menu on caret click
  const caretTriggers = document.querySelectorAll('.mbnav .has-sub > .mbnav__caret');
  caretTriggers.forEach(trigger => {
    trigger.addEventListener('click', function () {
      const element = this.parentElement;
      element.classList.add('is-open');
      document.body.classList.add('scroll-fixed');
      const menuLeftMove = document.querySelector('.mbnav__inner > .menu-wrap');
      const backMove = parseInt(window.getComputedStyle(menuLeftMove).getPropertyValue('--leftSlide'), 10) || 0;
      menuLeftMove.style.setProperty('--leftSlide', `${backMove + 100}%`);
    });
  });

  // Handle back click
  const backClicks = document.querySelectorAll('.mbnav__inner .back-click');
  backClicks.forEach(backClick => {
    backClick.addEventListener('click', function () {
      const menuItem = this.closest('.menu-item');
      if (menuItem) {
        menuItem.classList.remove('is-open');
      }
      const menuLeftMove = document.querySelector('.mbnav__inner > .menu-wrap');
      const backMove = parseInt(window.getComputedStyle(menuLeftMove).getPropertyValue('--leftSlide'), 10) || 0;
      menuLeftMove.style.setProperty('--leftSlide', `${backMove - 100}%`);
    });
  });

  // Set padding from header height
  const header = document.querySelector('header.main-header');
  if (header) {
    const headerHeight = header.offsetHeight;
    const menuInners = document.querySelectorAll('.mbnav .menu-wrap .menu-inner');
    menuInners.forEach(inner => {
      inner.style.paddingTop = `${headerHeight}px`;
    });
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DeviceMenu);

/***/ },

/***/ "./sources/js/script.js"
/*!******************************!*\
  !*** ./sources/js/script.js ***!
  \******************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _js_common_deviceMenu_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @js/common/deviceMenu.js */ "./sources/js/common/deviceMenu.js");
// Device menu is global, so keep it eager

(0,_js_common_deviceMenu_js__WEBPACK_IMPORTED_MODULE_0__["default"])();

// Load Home slider only if homepage element exists
if (document.querySelector('.home-slider')) {
  Promise.all(/*! import() */[__webpack_require__.e("library/.pnpm"), __webpack_require__.e("library/common"), __webpack_require__.e("sources_js_common_swiperInit_js")]).then(__webpack_require__.bind(__webpack_require__, /*! @js/common/swiperInit.js */ "./sources/js/common/swiperInit.js")).then(({
    initHomeSlider
  }) => {
    initHomeSlider();
  });
}

// Load Fancybox only if gallery/trigger elements exist
if (document.querySelector('[data-fancybox]')) {
  Promise.all(/*! import() */[__webpack_require__.e("library/.pnpm"), __webpack_require__.e("library/common"), __webpack_require__.e("sources_js_common_fancybox_js")]).then(__webpack_require__.bind(__webpack_require__, /*! @js/common/fancybox */ "./sources/js/common/fancybox.js")).then(({
    initFancybox
  }) => {
    initFancybox();
  });
}

/***/ },

/***/ "./sources/scss/style.scss"
/*!*********************************!*\
  !*** ./sources/scss/style.scss ***!
  \*********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./sources/js/script.js"), __webpack_exec__("./sources/scss/style.scss"));
/******/ }
]);
//# sourceMappingURL=main.js.map