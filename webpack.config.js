// Webpack config (ES6 Compatible) with full comments

import path from "path";
import { fileURLToPath } from "node:url";
import TerserPlugin from "terser-webpack-plugin";
import MiniCssExtractPlugin from "mini-css-extract-plugin";
import CssMinimizerPlugin from "css-minimizer-webpack-plugin";
import { WebpackManifestPlugin } from "webpack-manifest-plugin";
import { PurgeCSSPlugin } from "purgecss-webpack-plugin";
import { glob } from "glob";
import WebpackBuildNotifierPlugin from "webpack-build-notifier";
import WebpackBar from "webpackbar";
import whitelist from './config/purgecss-safelist.js';


// Export Webpack config object
export default (env, argv) => {
	// Get current file path and directory
	const filename = fileURLToPath(import.meta.url);
	const dirname = path.dirname(filename);

	// Detect if we're in development mode
	const isDev = argv.mode === "development";
	const isProd = argv.mode === "production";

	const enableModules = true; // Toggle this to true or false
	const getModuleEntries = () => {
		const files = glob.sync("sources/js/modules/**/*.js");
		const entries = {};

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

	// Base plugins always applied
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
			successSound: false,
			warningSound: "Glass",
			failureSound: "Basso",
			activateTerminalOnError: true,
		}),

		new WebpackBar(),
	];

	
	// Purging
	const safelist = whitelist.map((item) => {
		return item.startsWith("^") || item.endsWith("$")
			? new RegExp(item)
			: item;
	});
	if (isProd) {
		plugins.push(
			new PurgeCSSPlugin({
				paths: glob.sync(`${dirname}/**/*.{php,js}`, {
					nodir: true,
				}),
				safelist,
				defaultExtractor: (content) => content.match(/[\w-]+/g) || [],
				verbose: true,
			})
		);
	}

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

		plugins,

		optimization: {
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
						chunks: "all",
						enforce: true,
						priority: 10,
					},
				},
			},
			minimize: isProd,
			minimizer: [
				new TerserPlugin({
					terserOptions: {
						ecma: 2023,
						compress: {
							drop_console: isProd,
						},
					},
				}),
				new CssMinimizerPlugin(),
			],
		},

		target: ["web", "es2023"],

		devtool: "cheap-module-source-map",

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
			excludeAssets: [/node_modules/, /\.map$/, /^images\//, /^fonts\//],
			excludeModules: true,
		},

		performance: {
			hints: isDev ? false : "warning",
			maxEntrypointSize: 912000,
			maxAssetSize: 912000,
		},
	};
};
