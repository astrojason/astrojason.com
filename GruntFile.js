module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    watch: {
    scripts: {
      files: 'source/javascript/*.js',
      tasks: ['uglify:build']
    },
    styles: {
      files: 'source/styles/*.scss',
      tasks: ['compass:build']
    }
    },

    uglify: {
     build: {
       files: {
         'public/js/main.min.js': 'source/javascript/main.js',
         'public/js/bookmarklet.min.js': 'source/javascript/bookmarklet.js',
       }
     }
    },

    compass: {
      build: {
        options: {
          config: 'config.rb',
          environment: 'production',
          outputStyle: 'compressed'
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-compass');

  grunt.registerTask('default', ['uglify', 'compass']);
};
