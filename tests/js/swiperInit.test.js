import { describe, expect, it, jest, beforeEach } from '@jest/globals';

// We must mock Swiper since JSDOM doesn't support all of the complex browser sizing & layout APIs it needs
jest.unstable_mockModule('swiper/bundle', () => ({
	default: jest.fn().mockImplementation((selector, config) => {
		// Return a mock instance object so we can assert against the properties passed to it
		return {
			selector,
			config
		};
	})
}));

jest.unstable_mockModule('swiper/modules', () => ({
	Navigation: 'MockNavigation',
	Pagination: 'MockPagination',
}));

jest.unstable_mockModule('swiper/css/bundle', () => ({}));

describe('Swiper Initialization (swiperInit.js)', () => {

	beforeEach(() => {
		jest.clearAllMocks();
	});

	it('should initialize the home slider with correct configurations', async () => {
		// Dynamically import the module AFTER mocks are registered
		const { initHomeSlider } = await import('../../sources/js/common/swiperInit.js');
		const SwiperMock = (await import('swiper/bundle')).default;

		// Execute the function
		const swiperInstance = initHomeSlider();

		// Assertions
		expect(SwiperMock).toHaveBeenCalled();
		expect(swiperInstance.selector).toBe('.home-slider');
		expect(swiperInstance.config.loop).toBe(true);
		expect(swiperInstance.config.navigation.nextEl).toBe('.home-next');
		expect(swiperInstance.config.pagination.el).toBe('.home-pagination');
	});

	it('should initialize the testimonial slider with correct responsive breakpoints', async () => {
		const { initTestimonialSlider } = await import('../../sources/js/common/swiperInit.js');
		const SwiperMock = (await import('swiper/bundle')).default;

		// Execute the function
		const swiperInstance = initTestimonialSlider();

		// Assertions
		expect(SwiperMock).toHaveBeenCalled();
		expect(swiperInstance.selector).toBe('.testimonial-slider');
		expect(swiperInstance.config.slidesPerView).toBe(2);
		expect(swiperInstance.config.spaceBetween).toBe(30);

		// Check responsive breakpoints
		expect(swiperInstance.config.breakpoints[768].slidesPerView).toBe(1);
	});
});
