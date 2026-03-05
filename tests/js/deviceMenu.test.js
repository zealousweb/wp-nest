import { describe, expect, it, beforeEach } from '@jest/globals';
import DeviceMenu from '../../sources/js/common/deviceMenu';

describe('DeviceMenu', () => {
	beforeEach(() => {
		// Mock the HTML structure expected by DeviceMenu
		document.body.innerHTML = `
            <header class="main-header" style="height: 60px;"></header>
            <div class="hamburger"></div>
            <div class="mbnav" data-clickable="true">
                <div class="mbnav__backdrop"></div>
                <div class="mbnav__state" data-clickable="true">
                    <div class="mbnav__inner">
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li>
                                <a href="#">Services</a>
                                <ul>
                                    <li><a href="#">Web Design</a></li>
                                    <li><a href="#">SEO</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        `;
		// Clear body classes
		document.body.className = '';
	});

	it('should not throw error if elements are missing', () => {
		document.body.innerHTML = ''; // empty dom
		expect(() => DeviceMenu()).not.toThrow();
	});

	it('should toggle mobile menu when hamburger is clicked', () => {
		DeviceMenu();

		const hamburger = document.querySelector('.hamburger');
		const mbnav = document.querySelector('.mbnav');

		// Initial state
		expect(hamburger.classList.contains('is-clicked')).toBe(false);
		expect(mbnav.classList.contains('is-open')).toBe(false);
		expect(document.body.classList.contains('scroll-fixed')).toBe(false);

		// Click to open
		hamburger.click();
		expect(hamburger.classList.contains('is-clicked')).toBe(true);
		expect(mbnav.classList.contains('is-open')).toBe(true);
		expect(document.body.classList.contains('scroll-fixed')).toBe(true);

		// Click to close
		hamburger.click();
		expect(hamburger.classList.contains('is-clicked')).toBe(false);
		expect(mbnav.classList.contains('is-open')).toBe(false);
		expect(document.body.classList.contains('scroll-fixed')).toBe(false);
	});

	it('should close menu when backdrop is clicked', () => {
		DeviceMenu();

		const hamburger = document.querySelector('.hamburger');
		const overlay = document.querySelector('.mbnav__backdrop');
		const mbnav = document.querySelector('.mbnav');

		// Open menu first
		hamburger.click();
		expect(mbnav.classList.contains('is-open')).toBe(true);

		// Click backdrop to close
		overlay.click();
		expect(mbnav.classList.contains('is-open')).toBe(false);
		expect(hamburger.classList.contains('is-clicked')).toBe(false);
	});
});
