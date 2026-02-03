"use strict";
(globalThis["webpackChunkwpnest"] = globalThis["webpackChunkwpnest"] || []).push([["modules/ajax-scripts"],{

/***/ "./sources/js/modules/ajax-scripts.js"
/*!********************************************!*\
  !*** ./sources/js/modules/ajax-scripts.js ***!
  \********************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* global fetch, FormData, CustomEvent, wpnest_ajax_object, console */

(function () {
  /**
   * Handles AJAX-based pagination for blog listing.
   * Fetches filtered posts based on category and page number.
   * @param {number} paged - The page number to fetch (default is 1).
   */
  function wpnest_ajaxPagination(paged = 1) {
    if (typeof wpnest_ajax_object === 'undefined') {
      return;
    }
    const postcategoryElement = document.getElementById('postcategory');
    const postcategory = postcategoryElement ? postcategoryElement.value : '';
    const data = new FormData();
    data.append('action', 'wpnest_ajaxsearch_filter');
    data.append('postcategory', postcategory);
    data.append('paged', paged);
    data.append('nonce', wpnest_ajax_object.nonce);
    const loadingIndicator = document.querySelector('.loading');
    if (loadingIndicator) {
      loadingIndicator.style.display = 'block';
    }
    fetch(wpnest_ajax_object.ajax_url, {
      method: 'POST',
      body: data
    }).then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.text();
    }).then(html => {
      const blogListing = document.querySelector('.blog-listing');
      if (blogListing) {
        blogListing.innerHTML = html;
        // Trigger custom event for other scripts to know content updated
        document.dispatchEvent(new CustomEvent('wpnest:ajax_updated'));
      }
    }).catch(error => {
      // eslint-disable-next-line no-console
      console.error('AJAX Error:', error);
    }).finally(() => {
      if (loadingIndicator) {
        loadingIndicator.style.display = 'none';
      }
    });
  }

  /** Trigger AJAX pagination when the category dropdown changes */
  document.addEventListener('change', function (e) {
    if (e.target && e.target.id === 'postcategory') {
      wpnest_ajaxPagination();
    }
  });

  /** Handle pagination clicks */
  document.addEventListener('click', function (e) {
    const target = e.target;
    const pageLink = target.closest('.pagination .page-numbers');
    if (pageLink) {
      e.preventDefault();
      const pageValue = pageLink.textContent.trim();
      const page = parseInt(pageValue, 10);
      if (!isNaN(page)) {
        wpnest_ajaxPagination(page);
      }
    }
  });
})();

/***/ }

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./sources/js/modules/ajax-scripts.js"));
/******/ }
]);
//# sourceMappingURL=ajax-scripts.js.map