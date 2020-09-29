const Encore = require('@symfony/webpack-encore');
const dotenv = require('dotenv');
const fs = require('fs');
const os = require('os');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

var setEnvVar = (state) => {
  dotenv.config();
  const file = process.cwd() + '/.env';
  let envFile = fs.readFileSync(file, 'utf8');
  const write = (content) => fs.writeFile(file, content, (err) => console.log(err));
  if (state && process.env.NODE_ENABLED === 'false') {
    write(envFile.replace('NODE_ENABLED=false', 'NODE_ENABLED=true'));
  }
  if (state && !envFile.includes('NODE_ENABLED')) {
    envFile += os.EOL + 'NODE_ENABLED=true';
    write(envFile);
  }
  if (!state && process.env.NODE_ENABLED === 'true') {
    write(envFile.replace('NODE_ENABLED=true', 'NODE_ENABLED=false'));
  }
};

setEnvVar(false);


Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('app', './assets/js/app.js')
  .addEntry('tictactoe', './assets/js/tictactoe.js')
  .splitEntryChunks()
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage';
    config.corejs = 3;
  })
  .enablePostCssLoader()
  .enableSassLoader(function (options) {
  }, {
    resolveUrlLoader: false,
  })
  .copyFiles({
    from: './assets/image',
    to: 'images/[path][name].[hash:8].[ext]',
  })
//.enableTypeScriptLoader()
//.enableIntegrityHashes(Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();
