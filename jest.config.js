export default {
	testEnvironment: 'jsdom',
	transform: {
		'^.+\\.jsx?$': 'babel-jest',
	},
	moduleNameMapper: {
		'\\.(css|less|scss|sass)$': '<rootDir>/tests/js/__mocks__/styleMock.js',
	},
	testMatch: [
		'<rootDir>/tests/js/**/*.test.js'
	],
	verbose: true,
};
