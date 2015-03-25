module.exports = function(grunt) {
    require('jit-grunt')(grunt);
    grunt.initConfig({
            pkg: grunt.file.readJSON('package.json'),
            less: {
                development: {
                    options: {
                        paths: ['resources/less'],
                        optimization: 1,
                        compress: true,
                        cleancss: true,
                        nospawn: true
                    },
                    files: [{
                        expand: true,
                        cwd: 'resources/less/',
                        src: ['**/*.less'],
                        dest: 'resources/css/',
                        ext: '.css'
                    }]
                }
            },
            imagemin: {
                compress: {
                    options: {
                        optimizationLevel: 3,
                        svgoPlugins: [{
                            removeViewBox: false
                        }]
                    },
                    files: [{
                        expand: true,
                        cwd: 'resources/images/',
                        src: ['**/*.{png,jpg,gif}'],
                        dest: 'resources/images/built/'
                    }]
                }
            },
            jshint: {
                options: {
                    curly: true,
                    eqeqeq: true,
                    eqnull: true,
                    browser: true,
                    globals: {
                        jQuery: true
                    }
                },
                myFiles: ['resources/js/**/*.js']
            },
            watch: {
              livereload: {
                options: {
                  livereload: false
                },
                files: [
                    'resources/less/**/*.less'
                ],
                tasks: [
                    'less'
                ]
              }
            }
    });
    grunt.registerTask('compile', ['less']);
    grunt.registerTask('test', ['jshint']);
    grunt.registerTask('default', ['compile']);
}
