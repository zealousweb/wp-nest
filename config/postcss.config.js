// postcss.config.js
import autoprefixer from "autoprefixer";

/** @type {import('postcss').ProcessOptions} */
export default {
  plugins: [
    autoprefixer({
      overrideBrowserslist: [
        "last 2 versions",
        "Safari >= 9",
        "iOS >= 9",
        "not dead"
      ]
    })
  ]
};
