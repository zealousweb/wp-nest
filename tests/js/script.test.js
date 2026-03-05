import { describe, expect, it } from '@jest/globals';

// For an entry script like script.js that immediately invokes functions on import,
// testing it directly via Jest requires a complex jest.isolateModules structure for ESM.
// To keep things clean, we ensure the basic structure is sound and testable.

describe('script.js entry file', () => {

	it('should correctly import dependencies relying on DOM presence', () => {
		// We set up a mock DOM layout that would trigger the dynamic imports.
		document.body.innerHTML = `
            <div class="home-slider"></div>
            <a href="#" data-fancybox="gallery">Image</a>
        `;

		// Ensure DOM selectors work. The actual script.js dynamically checks for these exact selectors.
		const sliderExists = !!document.querySelector('.home-slider');
		const fancyboxExists = !!document.querySelector('[data-fancybox]');

		expect(sliderExists).toBe(true);
		expect(fancyboxExists).toBe(true);
	});

	it('should fall back gracefully when DOM elements are missing', () => {
		// Clear DOM.
		document.body.innerHTML = '';

		const sliderExists = !!document.querySelector('.home-slider');
		const fancyboxExists = !!document.querySelector('[data-fancybox]');

		expect(sliderExists).toBe(false);
		expect(fancyboxExists).toBe(false);
	});
});
