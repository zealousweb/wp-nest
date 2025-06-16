// Webpack config (ES6 Compatible) with full comments

import path from "path";
import { glob } from "glob";
import { fileURLToPath } from "node:url";
import TerserPlugin from "terser-webpack-plugin";
import MiniCssExtractPlugin from "mini-css-extract-plugin";
import CssMinimizerPlugin from "css-minimizer-webpack-plugin";
import { WebpackManifestPlugin } from "webpack-manifest-plugin";
import { PurgeCSSPlugin } from "purgecss-webpack-plugin";
import WebpackBuildNotifierPlugin from "webpack-build-notifier";
import WebpackBar from "webpackbar";
import whitelist from "./config/purgecss-safelist.js";
import ESLintPlugin from "eslint-webpack-plugin";
import StylelintPlugin from "stylelint-webpack-plugin";

// Get current file path and directory
const filename = fileURLToPath(import.meta.url);
const dirname = path.dirname(filename);

// Export Webpack config object
export default (env, argv) => {
	// Detect if we're in development mode
	const isDev = argv.mode === "development";
	const isProd = argv.mode === "production";
	const enableModules = true; // Toggle this to true or false

	const getModuleEntries = () => {
		const entries = {};
		const files = glob.sync("sources/js/modules/**/*.js");

		files.forEach((file) => {
			const name = path
				.relative("sources/js", file)
				.replace(/\.js$/, "")
				.replace(/\\/g, "/");
			entries[name] = path.resolve(dirname, file);
		});
		return entries;
	};

	const jsFilename = isDev ? "js/[name].js" : "js/[name]-[contenthash].js";
	const cssFilename = isDev
		? "css/[name].css"
		: "css/[name]-[contenthash].css";

	// Plugins configuration
	const getPlugins = () => {
		const plugins = [
			new MiniCssExtractPlugin({
				filename: cssFilename,
			}),
			new WebpackManifestPlugin({
				publicPath: "/",
				basePath: "",
			}),

			new WebpackBuildNotifierPlugin({
				title: "Webpack Notification",
				suppressSuccess: true,
				suppressWarning: false,
				suppressCompileStart: true,
				activateTerminalOnError: true,
			}),

			new WebpackBar(),

			new ESLintPlugin({
				extensions: ["js"],
				failOnError: isProd,
				fix: isProd,
				emitWarning: isDev,
				overrideConfigFile: path.resolve(
					dirname,
					"config/config.eslint.js"
				),
			}),

			new StylelintPlugin({
				configFile: path.resolve(dirname, "config/config.stylelint.js"),
				context: path.resolve(dirname, "sources/scss"),
				files: "**/*.scss",
				failOnError: isProd,
				failOnWarning: false,
				emitErrors: isDev,
				fix: isProd,
				emitWarnings: isDev,
				quiet: false,
				lintDirtyModulesOnly: false,
			}),
		];

		// Production-only plugins
		if (isProd) {
			plugins.push(
				new PurgeCSSPlugin({
					paths: glob.sync(`${dirname}/**/*.{php,js}`, {
						nodir: true,
					}),
					safelist: whitelist.map((item) =>
						item.startsWith("^") || item.endsWith("$")
							? new RegExp(item)
							: item
					),
					defaultExtractor: (content) =>
						content.match(/[\w-/:]+(?<!:)/g) || [],
					fontFace: true,
					keyframes: true,
				})
			);
		}
		return plugins;
	};

	// Optimization configuration
	const getOptimization = () => {
		const optimization = {
			splitChunks: {
				chunks: "all",
				cacheGroups: {
					libraries: {
						test: /[\\/]node_modules[\\/]/,
						name(module) {
							const context = module.context;
							if (!context) return "library/common";
							const match = context.match(
								/[\\/]node_modules[\\/](.*?)([\\/]|$)/
							);
							const packageName = match
								? match[1].replace("@", "")
								: "common";
							return `library/${packageName}`;
						},
						enforce: true,
						priority: 10,
					},
				},
			},
			minimize: isProd,
			minimizer: [
				new TerserPlugin({
					parallel: true,
					terserOptions: {
						ecma: 2023,
						compress: {
							drop_console: isProd,
							passes: 2,
						},
						format: {
							comments: false,
						},
					},
					extractComments: false,
				}),
				new CssMinimizerPlugin({
					parallel: true,
					minimizerOptions: {
						preset: [
							"default",
							{
								discardComments: { removeAll: true },
							},
						],
					},
				}),
			],
			runtimeChunk: "single"
		};

		return optimization;
	};

	return {
		mode: isDev ? "development" : "production",

		entry: {
			main: ["./sources/js/script.js", "./sources/scss/style.scss"],
			...(enableModules ? getModuleEntries() : {}),
		},

		output: {
			path: path.resolve(dirname, "assets"),
			filename: jsFilename,
			clean: true,
			publicPath: "/",
		},

		module: {
			rules: [
				{
					test: /\.js$/,
					exclude: /node_modules/,
					resolve: {
						fullySpecified: false,
					},
					use: {
						loader: "babel-loader",
						options: {
							presets: [
								[
									"@babel/preset-env",
									{
										targets: {
											esmodules: true,
										},
										bugfixes: true,
										modules: false,
									},
								],
							],
							cacheDirectory: true,
						},
					},
				},
				{
					test: /\.(scss|css)$/,
					use: [
						{
							loader: MiniCssExtractPlugin.loader,
							options: {
								publicPath: "../",
							},
						},
						{
							loader: "css-loader",
							options: {
								importLoaders: 1,
								url: true,
								sourceMap: isDev,
								esModule: true,
								modules: {
									auto: true,
									namedExport: true,
								},
							},
						},
						{
							loader: "sass-loader",
							options: {
								sourceMap: isDev,
								sassOptions: {
									outputStyle: "expanded",
								},
							},
						},
					],
				},
				{
					test: /\.(png|jpe?g|gif|svg|webp|avif)$/i,
					type: "asset/resource",
					generator: {
						filename: "images/[name][ext][query]",
					},
				},
				// Optional: fonts
				{
					test: /\.(woff(2)?|ttf|eot)$/,
					type: "asset/resource",
					generator: {
						filename: "fonts/[name][ext][query]",
					},
				},
			],
		},

		resolve: {
			extensions: [".scss", ".js"],
			alias: {
				"@scss": path.resolve(dirname, "sources/scss"),
				"@js": path.resolve(dirname, "sources/js"),
				"@img": path.resolve(dirname, "sources/images"),
			},
		},

		plugins: getPlugins(),

		optimization: getOptimization(),

		target: ["web", "es2023"],

		devtool: isDev ? "cheap-module-source-map" : "source-map",

		watch: isDev,

		stats: {
			all: false,
			assets: true,
			chunks: false,
			colors: true,
			timings: true,
			builtAt: true,
			errors: true,
			warnings: true,
			moduleAssets: true,
			assetsSort: "size",
			groupAssetsByChunk: true,
			excludeAssets: [
				/node_modules/,
				/\.css.map$/,
				/\.js.map$/,
				/^images\//,
				/^fonts\//,
			],
			excludeModules: true,
		},

		performance: {
			hints: isDev ? false : "warning",
			maxEntrypointSize: 912000,
			maxAssetSize: 912000,
		},
	};
};
