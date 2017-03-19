module.exports = (grunt) ->

  grunt.initConfig

    pkg: grunt.file.readJSON 'package.json'

    coffee:
      compile:
        expand: true
        cwd: 'public/assets/coffee/src/v1'
        src: ['**/*.coffee']
        dest: 'public/assets/coffee/build/v1'
        ext: '.js'

    compass:
      options:
        environment: 'production'
        outputStyle: 'compressed'
        noLineComments: true
        importPath: 'public/assets/bower/bootstrap-sass/assets/stylesheets'

      compile:
        options:
          sassDir: 'public/assets/sass/src/v1'
          cssDir: 'public/assets/sass/build/v1'
          specify: 'public/assets/sass/src/**/*.sass'

    copy:
      main:
        files: [
          expand: true
          cwd: 'public/assets/bower/bootstrap/fonts/'
          src: [
            'glyphicons-halflings-regular.*'
          ]
          dest: 'public/assets/sass/build/fonts/bootstrap/'
        ]

    uglify:
      options:
        mangle:
          except: ['Link', 'Movie', 'Book', 'Song', 'Game']
        preserveComments: false
      build:
        files: {
          'public/assets/js/v1/app.min.js': 'public/assets/coffee/build/v1/app.js'
          'public/assets/js/v1/bookmarklet.min.js': 'public/assets/coffee/build/v1/bookmarklet.js'
          'public/assets/js/v1/bookmarkletLoader.min.js': 'public/assets/coffee/build/v1/bookmarkletLoader.js'
          'public/assets/js/v1/models.min.js': 'public/assets/coffee/build/v1/models/*'
          'public/assets/js/v1/controllers.min.js': 'public/assets/coffee/build/v1/controllers/*'
          'public/assets/js/v1/directives.min.js': 'public/assets/coffee/build/v1/directives/*'
          'public/assets/js/v1/filters.min.js': 'public/assets/coffee/build/v1/filters/*'
          'public/assets/js/v1/services.min.js': 'public/assets/coffee/build/v1/services/*'
          'public/assets/js/v1/resources.min.js': 'public/assets/coffee/build/v1/resources/*'
        }

    clean:
      main: [
        'public/assets/coffee/build/v1/*'
        'public/assets/js/v1/*.js'
      ]
    #TODO: Split this out into karma.conf
    karma:
      options:
        files: [
          'public/assets/bower/angular/angular.js'
          'public/assets/bower/angular-animate/angular-animate.js'
          'public/assets/bower/angular-sanitize/angular-sanitize.js'
          'public/assets/bower/angular-messages/angular-messages.js'
          'public/assets/bower/angular-mocks/angular-mocks.js'
          'public/assets/bower/angular-resource/angular-resource.js'
          'public/assets/bower/angular-bootstrap/ui-bootstrap.min.js'
          'public/assets/bower/jquery/dist/jquery.min.js'
          'public/assets/bower/karma-read-json/karma-read-json.js'
          'public/assets/bower/jasmine-collection-matchers/lib/pack.js'
          'public/assets/bower/angular-fx/dist/angular-fx.min.js'
          'public/assets/bower/angular-typeahead/angular-typeahead.min.js'
          'public/assets/bower/moment/min/moment.min.js'

          'public/assets/coffee/build/v1/**/*.js'

          {
            pattern: 'public/assets/coffee/src/v1/tests/data/**/*.json'
            included: false
          }
        ]
        exclude: [
          'public/assets/coffee/build/v1/tests/e2e/*.js'
        ]
        logLevel: 'ERROR'
        frameworks: ['jasmine']
        reporters: [
          'jasmine-diff'
          'dots'
        ]
        colors: false
        singleRun: true

      dev:
        browsers: ['PhantomJS']
        singleRun: true

      report:
        browsers: ['PhantomJS']
        singleRun: true
        preprocessors:
          'public/assets/coffee/build/v1/**/*.js': ['coverage']
        reporters: ['coverage']

    watch:

      options:
        atBegin: true

      coffee:
        files: 'public/assets/coffee/src/v1/**/*.coffee'
        tasks: [
          'clean'
          'coffee'
          'uglify'
        ]

      compass:
        files: 'public/assets/sass/src/v1/**/*.sass'
        tasks: [
          'compass'
          'copy'
        ]

  grunt.registerTask 'test', [
    'clean'
    'coffee'
    'karma:dev'
  ]

  grunt.registerTask 'cover', [
    'clean'
    'coffee'
    'karma:report'
  ]

  grunt.loadNpmTasks 'grunt-contrib-clean'
  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-compass'
  grunt.loadNpmTasks 'grunt-contrib-copy'
  grunt.loadNpmTasks 'grunt-contrib-watch'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-karma'
