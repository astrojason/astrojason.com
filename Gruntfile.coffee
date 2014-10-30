module.exports = (grunt) ->

  grunt.initConfig
    pkg: grunt.file.readJSON 'package.json'

    bower:
      install:
        options:
          verbose: true
          bowerOptions:
            production: true

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
        mangle: true
        preserveComments: false
      build:
        files: {
          'public/assets/coffee/build/min/vendor/bootstrap.min.js': 'public/assets/bower/bootstrap-sass-official/assets/javascripts/bootstrap.js'
          'public/assets/coffee/build/min/app.min.js': 'public/assets/coffee/build/app.js'
        }

    watch:
      options:
        atBegin: true
      bower:
        files: 'bower.json'
        tasks: 'bower:install'
      coffee:
        files: 'public/assets/coffee/src/**/*.coffee'
        tasks: 'coffee'
      compass:
        files: 'public/assets/sass/src/**/*.s{a,c}ss'
        tasks: 'compass'
      uglify:
        files: 'public/assets/coffee/build/**/*.js'
        tasks: 'uglify'


  grunt.loadNpmTasks 'grunt-bower-task'
  grunt.loadNpmTasks 'grunt-contrib-compass'
  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-contrib-watch'