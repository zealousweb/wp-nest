/* global fetch, FormData, CustomEvent, wpnest_ajax_object, console */

(function() {
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
            body: data,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then((html) => {
                const blogListing = document.querySelector('.blog-listing');
                if (blogListing) {
                    blogListing.innerHTML = html;
                    // Trigger custom event for other scripts to know content updated
                    document.dispatchEvent(new CustomEvent('wpnest:ajax_updated'));
                }
            })
            .catch((error) => {
                // eslint-disable-next-line no-console
                console.error('AJAX Error:', error);
            })
            .finally(() => {
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'none';
                }
            });
    }

    /** Trigger AJAX pagination when the category dropdown changes */
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'postcategory') {
            wpnest_ajaxPagination();
        }
    });

    /**
     * Handle pagination clicks inside .blog-listing.
     *
     * Supports both numbered page links and prev/next arrow links.
     * For numbered pages: parse the page number from textContent.
     * For prev/next links: parse the page number from the href (?paged=N).
     */
    document.addEventListener('click', function(e) {
        const target = e.target;
        const pageLink = target.closest('.pagination .page-numbers');

        // Only act on links inside .blog-listing pagination
        if (!pageLink || !pageLink.closest('.blog-listing')) {
            return;
        }

        e.preventDefault();

        // Numbered page link: textContent is a digit
        const pageValue = parseInt(pageLink.textContent.trim(), 10);
        if (!isNaN(pageValue)) {
            wpnest_ajaxPagination(pageValue);
            return;
        }

        // Prev / Next link: read page number from href ?paged=N
        const href = pageLink.getAttribute('href') || '';
        const match = href.match(/[?&]paged=(\d+)/);
        if (match) {
            wpnest_ajaxPagination(parseInt(match[1], 10));
        }
    });
})();
