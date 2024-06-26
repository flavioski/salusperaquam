/* eslint-disable indent,comma-dangle */
/**
 * Salus per Aquam
 * Copyright since 2021 Flavio Pellizzer and Contributors
 * <Flavio Pellizzer> Property
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to flappio.pelliccia@gmail.com so we can send you a copy immediately.
 *
 * @author    Flavio Pellizzer
 * @copyright Since 2021 Flavio Pellizzer
 * @license   https://opensource.org/licenses/MIT
 */
/**
 * fix for webpack 2 deprecation warnings
 * DeprecationWarning: loaderUtils.parseQuery() received a non-string value which can be problematic, see
 * https://github.com/webpack/loader-utils/issues/56
 */
process.traceDeprecation = true;
/**
 * Three mode available:
 *  build = production mode
 *  build:analyze = production mode with bundler analyzer
 *  dev = development mode
 */
const prod = require('./.webpack/prod.js');
const dev = require('./.webpack/dev.js');

module.exports = (env, argv) => (argv !== undefined && (argv.mode === 'production' || !argv.mode) ? prod() : dev());
