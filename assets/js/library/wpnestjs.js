"use strict";
(globalThis["webpackChunkwpnest"] = globalThis["webpackChunkwpnest"] || []).push([["library/wpnestjs"],{

/***/ "./node_modules/wpnestjs/dist/index.js":
/*!*********************************************!*\
  !*** ./node_modules/wpnestjs/dist/index.js ***!
  \*********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   addClass: () => (/* binding */ addClass),
/* harmony export */   closest: () => (/* binding */ closest),
/* harmony export */   fadeIn: () => (/* binding */ fadeIn),
/* harmony export */   fadeOut: () => (/* binding */ fadeOut),
/* harmony export */   find: () => (/* binding */ find),
/* harmony export */   hide: () => (/* binding */ hide),
/* harmony export */   removeClass: () => (/* binding */ removeClass),
/* harmony export */   show: () => (/* binding */ show),
/* harmony export */   slideDown: () => (/* binding */ slideDown),
/* harmony export */   slideUp: () => (/* binding */ slideUp),
/* harmony export */   toggleClass: () => (/* binding */ toggleClass)
/* harmony export */ });
function addClass(element, className) {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof Element) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    elements.forEach((el) => el.classList.add(className));
}

function removeClass(element, classNames) {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof Element) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    // Support comma or space-separated class names
    const classList = classNames.split(/[\s,]+/).filter(Boolean);
    elements.forEach((el) => {
        classList.forEach((cls) => {
            el.classList.remove(cls);
        });
    });
}

function toggleClass(element, className) {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof Element) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    elements.forEach((el) => el.classList.toggle(className));
}

function fadeIn(element, duration = 400) {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof Element) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    elements.forEach((el) => {
        const htmlEl = el;
        htmlEl.style.opacity = '0';
        htmlEl.style.display = 'block';
        htmlEl.style.transition = `opacity ${duration}ms`;
        requestAnimationFrame(() => {
            htmlEl.style.opacity = '1';
        });
    });
}

function fadeOut(element, duration = 400) {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof Element) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    elements.forEach((el) => {
        const htmlEl = el;
        htmlEl.style.transition = `opacity ${duration}ms`;
        htmlEl.style.opacity = '0';
        setTimeout(() => {
            htmlEl.style.display = 'none';
        }, duration);
    });
}

function slideUp(element, duration = 400) {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof Element) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    elements.forEach((el) => {
        const htmlEl = el;
        htmlEl.style.transition = `height ${duration}ms, padding ${duration}ms, margin ${duration}ms`;
        htmlEl.style.boxSizing = 'border-box';
        htmlEl.style.height = `${htmlEl.offsetHeight}px`;
        htmlEl.offsetHeight; // force reflow
        htmlEl.style.overflow = 'hidden';
        htmlEl.style.height = '0';
        htmlEl.style.paddingTop = '0';
        htmlEl.style.paddingBottom = '0';
        htmlEl.style.marginTop = '0';
        htmlEl.style.marginBottom = '0';
        setTimeout(() => {
            htmlEl.style.display = 'none';
        }, duration);
    });
}

function slideDown(element, duration = 400) {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof HTMLElement) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    elements.forEach((el) => {
        el.style.removeProperty('display');
        let display = window.getComputedStyle(el).display;
        if (display === 'none')
            display = 'block';
        el.style.display = display;
        const height = el.offsetHeight;
        el.style.overflow = 'hidden';
        el.style.height = '0';
        el.style.paddingTop = '0';
        el.style.paddingBottom = '0';
        el.style.marginTop = '0';
        el.style.marginBottom = '0';
        el.offsetHeight; // force reflow
        el.style.transition = `height ${duration}ms, padding ${duration}ms, margin ${duration}ms`;
        el.style.height = `${height}px`;
        setTimeout(() => {
            el.style.removeProperty('height');
            el.style.removeProperty('overflow');
            el.style.removeProperty('transition');
            el.style.removeProperty('padding-top');
            el.style.removeProperty('padding-bottom');
            el.style.removeProperty('margin-top');
            el.style.removeProperty('margin-bottom');
        }, duration);
    });
}

function hide(element) {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof Element) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    elements.forEach((el) => {
        el.style.display = 'none';
    });
}

function show(element, displayType = 'block') {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof Element) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    elements.forEach((el) => {
        el.style.display = displayType;
    });
}

function find(element, selector) {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof Element) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    const found = [];
    elements.forEach((el) => {
        found.push(...Array.from(el.querySelectorAll(selector)));
    });
    return found;
}

function closest(element, selector) {
    let elements = [];
    if (typeof element === 'string') {
        elements = Array.from(document.querySelectorAll(element));
    }
    else if (element instanceof Element) {
        elements = [element];
    }
    else if (element instanceof NodeList || Array.isArray(element)) {
        elements = Array.from(element);
    }
    return elements
        .map((el) => el.closest(selector))
        .filter((el) => el !== null);
}


//# sourceMappingURL=index.js.map


/***/ })

}]);
//# sourceMappingURL=wpnestjs.js.map