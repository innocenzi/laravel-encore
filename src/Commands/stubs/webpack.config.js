const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  /*
	|--------------------------------------------------------------------------
	| Define your entries
	|--------------------------------------------------------------------------
	*/
  .addEntry('app', './resources/js/app.js')

  /*
	|--------------------------------------------------------------------------
	| Configure your output path
	|--------------------------------------------------------------------------
	*/
  .setOutputPath('public/build/')
  .setPublicPath('/build')

  /*
	|--------------------------------------------------------------------------
	| Enable PostCSS
	|--------------------------------------------------------------------------
	| You'll need to add postcss-loader to your package.json,
	| and to create a postcss.config.js file.
	*/
  // .enablePostCssLoader()

  /*
	|--------------------------------------------------------------------------
	| Optimizations
	|--------------------------------------------------------------------------
	*/
  .splitEntryChunks()
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction());

module.exports = Encore.getWebpackConfig();
