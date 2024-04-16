const webpack = require('webpack');
const keepLicense = require('uglify-save-license');
const common = require('./common.js');

/**
 * Returns the production webpack config,
 * by merging production specific configuration with the common one.
 *
 */
function prodConfig(analyze) {
  let prod = Object.assign(
    common,
    {
      stats: 'minimal',
    }
  );

  // Required for Vue production environment
  prod.plugins.push(
    new webpack.optimize.UglifyJsPlugin({
      sourceMap: true,
      uglifyOptions: {
        compress: {
          drop_console: true
        },
        output: {
          comments: keepLicense
        }
      },
    })
  );

  return prod;
}

module.exports = prodConfig;
