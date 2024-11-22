const Encore = require('@symfony/webpack-encore');
const Dotenv = require('dotenv-webpack');
const path = require('path');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
	//addPlugin priority high
    .addPlugin(
		new Dotenv({
			path: '.env.dev.local',
			defaults: '.env',
			systemvars: true,
			allowEmptyValues: true,
		}),
	)
    .addPlugin(
		new Dotenv({
			path: '.env.dev',
			defaults: '.env',
			systemvars: true,
			allowEmptyValues: true,
		}),
	)
    .addPlugin(
		new Dotenv({
			path: '.env.local',
			defaults: '.env',
			systemvars: true,
			allowEmptyValues: true,
		}),
	)
    .addPlugin(
		new Dotenv({
			path: '.env',
			defaults: '.env',
			systemvars: true,
			allowEmptyValues: true,
		}),
	)
	//addPlugin priority low

	.copyFiles({
		from: './assets/image',
		to: 'image/[path][name].[hash:8].[ext]',
	})
	
	/*
	.copyFiles({
		from: 'node_modules/bootstrap-icons/font/fonts',
		to: 'fonts/[path][name].[hash:8].[ext]',
	})
	*/
	
	// directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    .enableSvelte()

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/js/controllers.json')

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
	.configureSplitChunks(function (splitChunks) {
		splitChunks.automaticNameDelimiter = '_'
		splitChunks.chunks = 'all'
	})

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    //.enableVersioning(Encore.isProduction())
    .enableVersioning(true)
    
	.enablePostCssLoader(options => {
	/*
		options.postcssOptions = {
			
		}
	*/
	})

    // configure Babel
    .configureBabel((config) => {
        //config.plugins.push('@babel/a-babel-plugin');
    })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

const config = Encore.getWebpackConfig()

config.resolve.conditionNames = ['browser', 'import', 'svelte']

module.exports = config