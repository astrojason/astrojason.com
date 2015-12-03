module.exports = (grunt) ->

  grunt.initConfig

    pkg: grunt.file.readJSON 'package.json'

    coffee:
      files:
        expand: true
        cwd: 'public/assets/coffee/src'
        src: '**/*.coffee'
        dest: 'public/assets/coffee/build'
        ext: '.js'

    compass:
      options:
        environment: 'production'
        outputStyle: 'compressed'
        noLineComments: true
        importPath: 'public/'

      compile:
        options:
          sassDir: 'public/assets/sass/src'
          cssDir: 'public/assets/sass/build'
          specify: 'public/assets/sass/src/**/*.s{a,c}ss'

    uglify:
      options:
        mangle:
          except: ['Link', 'Movie', 'Book', 'Song', 'Game']
        preserveComments: false
      build:
        files: {
          'public/assets/js/vendor/bootstrap.min.js': 'public/assets/bower/bootstrap-sass-official/assets/javascripts/bootstrap.js'
          'public/assets/js/app.min.js': 'public/assets/coffee/build/app.js'
          'public/assets/js/bookmarklet.min.js': 'public/assets/coffee/build/bookmarklet.js'
          'public/assets/js/bookmarkletLoader.min.js': 'public/assets/coffee/build/bookmarkletLoader.js'
          'public/assets/js/models.min.js': 'public/assets/coffee/build/models/*'
          'public/assets/js/controllers.min.js': 'public/assets/coffee/build/controllers/*'
          'public/assets/js/directives.min.js': 'public/assets/coffee/build/directives/*'
          'public/assets/js/filters.min.js': 'public/assets/coffee/build/filters/*'
          'public/assets/js/services.min.js': 'public/assets/coffee/build/services/*'
          'public/assets/js/resources.min.js': 'public/assets/coffee/build/resources/*'
        }

    clean: [
      'public/assets/coffee/build/*',
      'public/assets/js/*.js'
    ]

    karma:
      options:
        files: [
          'public/assets/bower/angular/angular.js'
          'public/assets/bower/angular-animate/angular-animate.js'
          'public/assets/bower/angular-messages/angular-messages.js'
          'public/assets/bower/angular-mocks/angular-mocks.js'
          'public/assets/bower/angular-resource/angular-resource.js'
          'public/assets/bower/jquery/dist/jquery.min.js'
          'public/assets/bower/karma-read-json/karma-read-json.js'

          'public/assets/coffee/build/**/*.js'

          {
            pattern: 'public/assets/coffee/src/tests/data/**/*.json'
            included: false
          }
        ]
        logLevel: 'ERROR'
        frameworks: ['jasmine']
        singleRun: true

      dev:
        browsers: ['PhantomJS']
        singleRun: true

      report:
        browsers: ['PhantomJS']
        singleRun: true
        preprocessors:
          'public/assets/coffee/build/**/*.js': ['coverage']
        reporters: ['coverage']

    watch:

      options:
        atBegin: true

      coffee:
        files: 'public/assets/coffee/src/**/*.coffee'
        tasks: ['clean', 'coffee']

      compass:
        files: 'public/assets/sass/src/**/*.s{a,c}ss'
        tasks: 'compass'

      uglify:
        files: 'public/assets/coffee/build/**/*.js'
        tasks: 'uglify'

  grunt.registerTask 'test', [
    'clean'
    'coffee'
    'karma:dev'
  ]

  grunt.loadNpmTasks 'grunt-contrib-compass'
  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-contrib-watch'
  grunt.loadNpmTasks 'grunt-contrib-clean'
  grunt.loadNpmTasks 'grunt-karma'
