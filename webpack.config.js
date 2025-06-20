// Webpack config with native ESLint & Stylelint integration during watch

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
import stylelint from "stylelint";
import { ESLint } from "eslint";

const filename = fileURLToPath(import.meta.url);
const dirname = path.dirname(filename);

class NativeLintPlugin {
  constructor({ argv }) {
    this.isDev = argv.mode === "development";
    this.isProd = argv.mode === "production";
    this.rootDir = path.resolve();
  }

  async lintJS() {
    const eslint = new ESLint({
      fix: this.isDev,
      overrideConfigFile: path.resolve(this.rootDir, "config/config.eslint.js"),
    });
    const files = glob.sync("sources/**/*.js");
    const results = await eslint.lintFiles(files);
    if (this.isDev) await ESLint.outputFixes(results);
    const formatter = await eslint.loadFormatter("stylish");
    console.log(formatter.format(results));
    const hasErrors = results.some((r) => r.errorCount > 0);
    if (hasErrors && !this.isDev) throw new Error("ESLint failed with errors.");
  }

  async lintSCSS() {
    const files = glob.sync("sources/**/*.scss");
    const results = await Promise.all(
      files.map((file) =>
        stylelint.lint({
          files: file,
          configFile: path.resolve(this.rootDir, "config/config.stylelint.js"),
          fix: this.isDev,
        })
      )
    );
    const errored = results.filter((r) => r.errored);
    if (errored.length) {
      errored.forEach((r) => console.error(r.output));
      if (!this.isDev) throw new Error("Stylelint failed with errors.");
    }
  }

  apply(compiler) {
    compiler.hooks.watchRun.tapPromise("NativeLintPlugin", async () => {
      await this.lintJS();
      await this.lintSCSS();
    });

    compiler.hooks.run.tapPromise("NativeLintPlugin", async () => {
      await this.lintJS();
      await this.lintSCSS();
    });
  }
}

export default async (env, argv) => {
  const isDev = argv.mode === "development";
  const isProd = argv.mode === "production";
  const enableModules = true;

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
  const cssFilename = isDev ? "css/[name].css" : "css/[name]-[contenthash].css";

  const plugins = [
    new MiniCssExtractPlugin({ filename: cssFilename }),
    new WebpackManifestPlugin({ publicPath: "/" }),
    new WebpackBuildNotifierPlugin({
      title: "Webpack Notification",
      suppressSuccess: true,
      activateTerminalOnError: true,
    }),
    new WebpackBar(),
    new NativeLintPlugin({ argv }),
  ];

  if (isProd) {
    plugins.push(
      new PurgeCSSPlugin({
        paths: glob.sync(`${dirname}/**/*.{php,js}`, { nodir: true }),
        safelist: whitelist.map((item) =>
          item.startsWith("^") || item.endsWith("$") ? new RegExp(item) : item
        ),
        defaultExtractor: (content) => content.match(/[\w-/:]+(?<!:)/g) || [],
        fontFace: true,
        keyframes: true,
      })
    );
  }

  const optimization = {
    splitChunks: {
      chunks: "all",
      cacheGroups: {
        libraries: {
          test: /[\\/]node_modules[\\/]/,
          name(module) {
            const context = module.context;
            if (!context) return "library/common";
            const match = context.match(/[\\/]node_modules[\\/](.*?)([\\/]|$)/);
            const packageName = match ? match[1].replace("@", "") : "common";
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
          compress: { drop_console: isProd, passes: 2 },
          format: { comments: false },
        },
        extractComments: false,
      }),
      new CssMinimizerPlugin({
        parallel: true,
        minimizerOptions: {
          preset: ["default", { discardComments: { removeAll: true } }],
        },
      }),
    ],
    runtimeChunk: "single",
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
      assetModuleFilename: "[name][ext][query]"
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          resolve: { fullySpecified: false },
          use: {
            loader: "babel-loader",
            options: {
              presets: [["@babel/preset-env", { targets: { esmodules: true }, bugfixes: true }]],
              cacheDirectory: true,
            },
          },
        },
        {
          test: /\.(scss|css)$/,
          use: [
            { loader: MiniCssExtractPlugin.loader, options: { publicPath: "../" } },
            {
              loader: "css-loader",
              options: {
                importLoaders: 1,
                sourceMap: isDev,
                modules: { auto: true, namedExport: true },
              },
            },
            {
              loader: "sass-loader",
              options: {
                sourceMap: isDev,
                sassOptions: { outputStyle: "expanded" },
              },
            },
          ],
        },
        {
          test: /\.(png|jpe?g|gif|svg|webp|avif)$/i,
          type: "asset/resource",
          generator: { filename: "images/[name][ext][query]" },
        },
        {
          test: /\.(woff(2)?|ttf|eot)$/,
          type: "asset/resource",
          generator: { filename: "fonts/[name][ext][query]" },
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
    optimization,
    target: ["web", "es2023"],
    devtool: isDev ? "cheap-module-source-map" : "source-map",
    watch: isDev,
    stats: {
      all: false,
      assets: true,
      colors: true,
      timings: true,
      builtAt: true,
      errors: true,
      warnings: true,
      moduleAssets: true,
      assetsSort: "size",
      groupAssetsByChunk: true,
      excludeAssets: [/node_modules/, /\.css.map$/, /\.js.map$/, /^images\//, /^fonts\//],
      excludeModules: true,
    },
    performance: {
      hints: isDev ? false : "warning",
      maxEntrypointSize: 912000,
      maxAssetSize: 912000,
    },
  };
};
