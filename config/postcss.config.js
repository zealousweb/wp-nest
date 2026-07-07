// postcss.config.js
import autoprefixer from 'autoprefixer';
import purgecss from '@fullhuman/postcss-purgecss';
import { glob } from 'glob';
import { fileURLToPath } from 'node:url';
import path from 'path';
import whitelist from './purgecss-safelist.js';

const __filename = fileURLToPath(import.meta.url);
// config/ is one level below the theme root, so go up one directory
const themeRoot = path.resolve(path.dirname(__filename), '..');

const isProd = process.env.NODE_ENV === 'production';

/** @type {import('postcss').ProcessOptions} */
export default {
	plugins: [
		autoprefixer({
			overrideBrowserslist: [
				'last 2 versions',
				'Safari >= 9',
				'iOS >= 9',
				'not dead',
			],
		}),

		// PurgeCSS — production only, matches webpack PurgeCSSPlugin behaviour
		...(isProd
			? [
				purgecss({
					// Scan all PHP templates and compiled JS for used class names
					content: glob.sync(`${themeRoot}/**/*.{php,js}`, {
						ignore: [`${themeRoot}/node_modules/**`, `${themeRoot}/vendor/**`],
						nodir: true,
					}),

					// Safelist mirrors config/purgecss-safelist.js
					safelist: {
						standard: whitelist
							.filter((item) => !item.startsWith('^') && !item.endsWith('$'))
							.map((item) => item),
						deep: whitelist
							.filter((item) => item.startsWith('^') || item.endsWith('$'))
							.map((item) => new RegExp(item)),
						greedy: [],
					},

					defaultExtractor: (content) => content.match(/[\w-/:]+((?<!:))/g) || [],
					fontFace:  true,
					keyframes: true,
					variables: false,
				}),
			]
			: []),
	],
};
