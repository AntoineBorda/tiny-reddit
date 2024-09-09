const Encore = require("@symfony/webpack-encore");
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}
Encore.setOutputPath("public/build/")
  .copyFiles({
    from: "./assets/img/",
    to: "img/[path][name].[ext]",
  })
  .setPublicPath("/build")
  .addEntry("app", "./assets/app.js")
  .splitEntryChunks()

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = "usage";
    config.corejs = "3.23";
  })
  .enableSassLoader();

// TWIG
const fullConfig = Encore.getWebpackConfig();
fullConfig.devServer = {
  headers: {
    "Access-Control-Allow-Origin": "*",
  },
  watchFiles: {
    paths: ["templates/**/*.html.twig"],
  },
};
module.exports = fullConfig;
