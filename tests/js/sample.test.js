import { describe, expect, it } from '@jest/globals';

describe('Sample JS Test', () => {
	it('should verify basic arithmetic operations', () => {
		expect(1 + 1).toBe(2);
	});

	it('should test DOM manipulation in jsdom', () => {
		document.body.innerHTML = '<div class="test-element">Hello</div>';
		const el = document.querySelector('.test-element');
		expect(el.textContent).toBe('Hello');
	});
});
