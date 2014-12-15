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
          except: ['Link']
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
        }

    watch:
      options:
        atBegin: true
      coffee:
        files: 'public/assets/coffee/src/**/*.coffee'
        tasks: 'coffee'
      compass:
        files: 'public/assets/sass/src/**/*.s{a,c}ss'
        tasks: 'compass'
      uglify:
        files: 'public/assets/coffee/build/**/*.js'
        tasks: 'uglify'

  grunt.loadNpmTasks 'grunt-contrib-compass'
  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-contrib-watch'
