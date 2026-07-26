import { defineConfig, loadEnv } from "vite";
import path from "path";
import { globSync } from "glob";
import liveReload from "vite-plugin-live-reload";
import tailwindcss from "@tailwindcss/vite";

const getModuleEntries = () => {
	const entries = {};
	const files = globSync("sources/js/modules/**/*.js");

	files.forEach((file) => {
		const name = path
			.relative("sources/js", file)
			.replace(/\.js$/, "")
			.replace(/\\/g, "/");

		entries[name] = path.resolve(process.cwd(), file);
	});

	return entries;
};

export default defineConfig(({ mode }) => {
	const env = loadEnv(mode, process.cwd(), "");

	const devServer = env.VITE_DEV_SERVER || "http://127.0.0.1";
	const devHost = devServer.replace(/^https?:\/\//, "");

	return {
		plugins: [
			liveReload([
				"**/*.php",
				"template-parts/**/*.php",
				"includes/**/*.php",
				"templates/**/*.php",
			]),
			tailwindcss(),
		],

		base:
			mode === "development"
				? "/"
				: `/wp-content/themes/${env.VITE_DEV_THEME_NAME || path.basename(process.cwd())}/assets/`,

		build: {
			outDir: "assets",
			assetsDir: "",
			manifest: true,
			sourcemap: mode === "development",

			minify: "esbuild",

			cssCodeSplit: true,

			rollupOptions: {
				input: {
					main: path.resolve(process.cwd(), "sources/js/script.js"),
					style: path.resolve(process.cwd(), "sources/css/style.css"),
					...getModuleEntries(),
				},

				output: {
					entryFileNames: "js/[name]-[hash].js",
					chunkFileNames: "js/[name]-[hash].js",

					assetFileNames: (assetInfo) => {
						const assetName = assetInfo.name ?? "";

						if (assetName.endsWith(".css")) {
							return "css/[name]-[hash][extname]";
						}

						if (
							/\.(png|jpe?g|gif|svg|webp|avif)$/i.test(assetName)
						) {
							return "images/[name][extname]";
						}

						if (/\.(woff(2)?|ttf|eot)$/i.test(assetName)) {
							return "fonts/[name][extname]";
						}

						return "[name]-[hash][extname]";
					},

					manualChunks: (id) => {
						if (id.includes("node_modules")) {
							const match = id.match(
								/[\\/]node_modules[\\/](?:@([^\\/]+)[\\/])?([^\\/]+)/,
							);

							if (match) {
								const scope = match[1];
								const pkg = match[2];

								const packageName = scope
									? `${scope}-${pkg}`
									: pkg;

								const heavyLibs = ["swiper", "fancyapps"];

								if (
									heavyLibs.some((lib) =>
										packageName.includes(lib),
									)
								) {
									return `library/${packageName}`;
								}

								return "library/vendor";
							}

							return "library/vendor";
						}
					},
				},
			},
		},

		css: {
			devSourcemap: true,
		},

		resolve: {
			alias: {
				"@scss": path.resolve(process.cwd(), "sources/css"),
				"@js": path.resolve(process.cwd(), "sources/js"),
				"@img": path.resolve(process.cwd(), "sources/images"),
			},
		},

		server: {
			cors: true,
			strictPort: true,
			host: "0.0.0.0",
			port: Number(env.VITE_DEV_SERVER_PORT) || 5173,

			hmr: {
				host: devHost ? devHost : "localhost",
				port: Number(env.VITE_DEV_SERVER_PORT) || 5173,
			},
		},
	};
});
