# Load all required libraries.
gulp = require 'gulp'
plumber = require 'gulp-plumber'
sass = require 'gulp-sass'
cssmin = require 'gulp-cssmin'
coffee = require 'gulp-coffee'
watch = require 'gulp-watch'
uglify = require 'gulp-uglify'
concat = require 'gulp-concat'
gulpif = require 'gulp-if'
rename = require 'gulp-rename'
notifier = require 'node-notifier'
del = require 'del'
browserify = require 'browserify'
source = require 'vinyl-source-stream'
buffer = require 'vinyl-buffer'

assetPath = 'public/assets'
coffeePath = "#{assetPath}/coffee"

uglifyFiles = true
notifications = false

plumberErr = (err)->
  notifier.notify
    title: 'Gulp'
    subtitle: 'error'
    message: err.message
    sound: "Glass"
  console.log err.message

gulp.task 'cleanJs', ->
  del [
    "#{assetPath}/coffee/build/v2/**/*"
    "#{assetPath}/js/v2/**/*"
  ]

gulp.task 'cleanCss', ->
  del [
    "#{assetPath}/sass/build/v2/**/*"
  ]

gulp.task 'clean',  gulp.series [
  'cleanJs'
  'cleanCss'
  ]

gulp.task 'toggleNotifications', (done)->
  notifications = true
  done()

gulp.task 'bootstrapJs', (done)->
  gulp.src [
    "#{__dirname}/node_modules/bootstrap/dist/js/bootstrap.min.js"
    ]
    .pipe plumber errorHandler: plumberErr
    .pipe gulp.dest "#{assetPath}/js/libraries"
  done()

gulp.task 'bootstrapCss', (done)->
  sassOptions =
    compass: true
    includePaths: [
      "#{__dirname}/node_modules/bootstrap/scss"
    ]
  gulp.src "#{assetPath}/sass/src/**/*.scss"
    .pipe plumber errorHandler: plumberErr
    .pipe sass sassOptions
    .pipe cssmin keepSpecialComments: 0
    .pipe gulp.dest "#{assetPath}/sass/build"
  done()

gulp.task 'astroBase', (done)->
  gulp.src [
    "#{coffeePath}/src/v2/*.coffee"
  ]
    .pipe plumber errorHandler: plumberErr
    .pipe coffee()
    .pipe gulpif uglifyFiles, uglify()
    .pipe rename suffix: '.min'
    .pipe gulp.dest "#{coffeePath}/build/v2/"
  done()

gulp.task 'controllers', (done)->
  gulp.src [
    "#{coffeePath}/src/v2/controllers/*.coffee"
  ]
    .pipe plumber errorHandler: plumberErr
    .pipe coffee()
    .pipe concat 'controllers.min.js'
    .pipe gulpif uglifyFiles, uglify()
    .pipe gulp.dest "#{coffeePath}/build/v2/"
  done()

gulp.task 'directives', (done)->
  gulp.src [
    "#{coffeePath}/src/v2/directives/*.coffee"
  ]
    .pipe plumber errorHandler: plumberErr
    .pipe coffee()
    .pipe concat 'directives.min.js'
    .pipe gulpif uglifyFiles, uglify()
    .pipe gulp.dest "#{coffeePath}/build/v2/"
  done()

gulp.task 'factories', (done)->
  gulp.src [
    "#{coffeePath}/src/v2/factories/*.coffee"
  ]
    .pipe plumber errorHandler: plumberErr
    .pipe coffee()
    .pipe concat 'factories.min.js'
    .pipe gulpif uglifyFiles, uglify()
    .pipe gulp.dest "#{coffeePath}/build/v2/"
  done()

gulp.task 'resources', (done)->
  gulp.src [
    "#{coffeePath}/src/v2/resources/*.coffee"
  ]
    .pipe plumber errorHandler: plumberErr
    .pipe coffee()
    .pipe concat 'resources.min.js'
    .pipe gulpif uglifyFiles, uglify()
    .pipe gulp.dest "#{coffeePath}/build/v2/"
  done()

gulp.task 'build:jsx', ->
  bundler = browserify "./#{assetPath}/jsx/app.jsx"

  options =
    presets: [
      'es2015'
      'react'
    ]

  bundler.transform 'babelify', options
    .bundle()
    .pipe source('app.js')
    .pipe buffer()
    .pipe gulp.dest("#{assetPath}/js/v2")

gulp.task 'coffee', gulp.series [
  'astroBase'
  'controllers'
  'directives'
  'factories'
  'resources'
]

gulp.task 'prepare', gulp.series [
  'clean'
  'bootstrapJs'
  'bootstrapCss'
  'coffee'
  'build:jsx'
]

gulp.task 'watch', ->
  coffeeSrc = gulp.watch "#{coffeePath}/src/v2/**/*.coffee"
  coffeeSrc.on 'all', gulp.series [
    'cleanJs'
    'coffee'
  ]
  cssSrc = gulp.watch "#{assetPath}/sass/src/v2/*.scss"
  cssSrc.on 'all', gulp.series [
    'cleanCss'
    'bootstrapCss'
  ]

  jsxSource = gulp.watch "#{assetPath}/jsx/**/*.jsx"
  jsxSource.on 'all', gulp.series [
    'cleanJs'
    'build:jsx'
  ]

gulp.task 'dev', gulp.series [
  'prepare'
  'toggleNotifications'
  'watch'
]