# Load all required libraries.
gulp = require 'gulp'
plumber = require 'gulp-plumber'
sass = require 'gulp-sass'
cssmin = require 'gulp-cssmin'

assetPath = 'public/assets'

gulp.task 'bootstrapJs', (done)->
  gulp.src [
    "#{__dirname}/node_modules/bootstrap/dist/js/bootstrap.min.js"
    ]
    .pipe gulp.dest "#{assetPath}/js/libraries"
  done()

gulp.task 'bootstrapCss', (done)->
  sassOptions =
    compass: true
    includePaths: [
      "#{__dirname}/node_modules/bootstrap/scss"
    ]
  gulp.src "#{assetPath}/sass/src/**/*.s{a,c}ss"
    .pipe sass sassOptions
    .pipe cssmin keepSpecialComments: 0
    .pipe gulp.dest "#{assetPath}/sass/build"
  done()