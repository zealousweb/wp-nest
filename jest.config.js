export default {
	testEnvironment: 'jsdom',
	transform: {
		'^.+\\.jsx?$': 'babel-jest',
	},
	moduleNameMapper: {
		'\\.(css|less|scss|sass)$': '<rootDir>/tests/js/__mocks__/styleMock.js',
		'^@js/(.*)$': '<rootDir>/sources/js/$1',
	},
	testMatch: [
		'<rootDir>/tests/js/**/*.test.js'
	],
	verbose: true,
};
